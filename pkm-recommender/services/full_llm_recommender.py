"""
Full LLM Recommender (Pendekatan B) — pembanding pipeline Hybrid (Pendekatan A).

Satu panggilan LLM terstruktur menghasilkan: prediksi skema, 3 judul per skema
(gaya natural/menarik seperti judul juara), dan Top-3 dosen dari 40 kandidat
(hasil penalaran LLM, bukan cosine similarity). Keluaran dibuat BERSTRUKTUR
IDENTIK dengan endpoint /recommendation agar bisa dibandingkan pada UI yang sama.

Skalabilitas: cache exact-match + semaphore konkurensi + rotasi API key +
failover provider (Groq -> Gemini). Bila semua gagal, fungsi mengembalikan None
sehingga pemanggil (route) dapat fallback anggun ke Pendekatan A.
"""
import hashlib
import json
import logging
import os
import re
import socket
import threading
import urllib.error
import urllib.request
from itertools import cycle
from typing import Any

import pandas as pd

from core.config import settings
from services.dosen_matcher import dosen_matcher

logger = logging.getLogger(__name__)

SCHEMA_CATALOG = {
    "PKM-KC": "Karsa Cipta: rancang bangun alat/sistem/purwarupa fungsional.",
    "PKM-RE": "Riset Eksakta: eksperimen/pemodelan/pengujian sains atau komputasi.",
    "PKM-RSH": "Riset Sosial Humaniora: kajian sosial, perilaku, psikologi, humaniora.",
    "PKM-PM": "Pengabdian Masyarakat: pemberdayaan/pelatihan komunitas non-profit.",
    "PKM-PI": "Penerapan Iptek: teknologi tepat guna untuk mitra usaha/produktif.",
    "PKM-K": "Kewirausahaan: produk/jasa kreatif bernilai jual.",
    "PKM-KI": "Karya Inovatif: karya siap pakai berpotensi HKI/komersial.",
    "PKM-VGK": "Video Gagasan Konstruktif: gagasan solutif dalam bentuk video.",
    "PKM-GFT": "Gagasan Futuristik Tertulis: gagasan masa depan yang visioner.",
    "PKM-AI": "Artikel Ilmiah: artikel dari kegiatan akademik yang telah dilakukan.",
}


class LLMUnavailable(Exception):
    pass


# ── Rotasi API key & kontrol konkurensi ─────────────────────────────────────
def _parse_keys(primary: str, plural_env: str) -> list[str]:
    raw = os.environ.get(plural_env, "") or ""
    keys = [k.strip() for k in raw.split(",") if k.strip()]
    if not keys and primary:
        keys = [primary]
    return keys


_groq_keys = _parse_keys(settings.GROQ_API_KEY, "GROQ_API_KEYS")
_gemini_keys = _parse_keys(getattr(settings, "GEMINI_API_KEY", ""), "GEMINI_API_KEYS")
_groq_cycle = cycle(_groq_keys) if _groq_keys else None
_gemini_cycle = cycle(_gemini_keys) if _gemini_keys else None
_key_lock = threading.Lock()

_llm_semaphore = threading.Semaphore(int(getattr(settings, "FULL_LLM_MAX_CONCURRENCY", 4)))
_CACHE: dict[str, dict[str, Any]] = {}
_cache_lock = threading.Lock()


def _next_key(provider: str):
    with _key_lock:
        if provider == "groq" and _groq_cycle:
            return next(_groq_cycle)
        if provider == "gemini" and _gemini_cycle:
            return next(_gemini_cycle)
    return None


# ── Data pendukung: judul juara (few-shot) & daftar dosen ───────────────────
def _load_winner_titles() -> list[str]:
    try:
        if os.path.exists(settings.WINNERS_FILE):
            df = pd.read_csv(settings.WINNERS_FILE)
            col = "title" if "title" in df.columns else df.columns[0]
            return [str(t) for t in df[col].dropna().tolist() if str(t).strip()]
    except Exception as exc:  # pragma: no cover - defensif
        logger.warning("Gagal memuat judul juara: %s", exc)
    return []


_WINNER_TITLES = _load_winner_titles()


def _pick_examples(idea: str, n: int = 8) -> list[str]:
    """Pilih judul juara sebagai contoh gaya. Diutamakan yang beririsan kata
    dengan ide; sisanya dilengkapi contoh beragam agar LLM belajar variasi gaya."""
    if not _WINNER_TITLES:
        return []
    idea_words = {w for w in re.findall(r"[a-z]+", idea.lower()) if len(w) > 3}
    scored = []
    for t in _WINNER_TITLES:
        tw = set(re.findall(r"[a-z]+", t.lower()))
        scored.append((len(idea_words & tw), t))
    scored.sort(key=lambda x: x[0], reverse=True)
    top = [t for s, t in scored[:n] if s > 0]
    if len(top) < n:
        for _, t in scored:
            if t not in top:
                top.append(t)
            if len(top) >= n:
                break
    return top[:n]


def _all_dosen() -> list[dict]:
    df = getattr(dosen_matcher, "df", None)
    if df is None:
        return []
    out = []
    for _, row in df.iterrows():
        name = str(row.get("name") or "").strip()
        if not name:
            continue
        out.append({
            "name": name,
            "dept": str(row.get("dept") or "").strip(),
            "keywords": str(row.get("keywords") or "").strip(),
        })
    return out


# ── Prompt ──────────────────────────────────────────────────────────────────
def _build_prompt(idea, has_partner, partner_is_profit, examples, dosen):
    schema_lines = "\n".join(f"- {k}: {v}" for k, v in SCHEMA_CATALOG.items())
    dosen_lines = "\n".join(
        f"{i + 1}. {d['name']} | Prodi: {d['dept']} | Keahlian: {d['keywords']}"
        for i, d in enumerate(dosen)
    ) or "(daftar dosen tidak tersedia)"
    ex_lines = "\n".join(f"- {t}" for t in examples) or "- (tidak ada contoh)"

    system = (
        "Anda Pakar Reviewer Nasional PKM Indonesia sekaligus pembuat judul kreatif. "
        "Anda menghasilkan rekomendasi PKM lengkap: prediksi skema, judul, dan dosen "
        "pembimbing. Balas HANYA JSON valid sesuai format, tanpa teks tambahan."
    )
    user = (
        f'IDE MAHASISWA:\n"{idea}"\n'
        f"Ada mitra: {has_partner}; Mitra profit: {partner_is_profit}\n\n"
        f"KATALOG SKEMA PKM:\n{schema_lines}\n\n"
        "CONTOH JUDUL PKM JUARA (acuan GAYA saja — tiru gaya menarik & ilmiahnya, "
        "boleh pakai nama/akronim + tagline, lugas, tidak kaku; JANGAN meniru topiknya "
        f"bila tidak relevan):\n{ex_lines}\n\n"
        "DAFTAR DOSEN KANDIDAT (pilih 3 paling cocok dengan ide):\n"
        f"{dosen_lines}\n\n"
        "TUGAS:\n"
        "1. Tentukan 3 skema paling sesuai (urut dari paling cocok); tiap skema beri "
        "confidence 0..1 dan alasan singkat.\n"
        "2. Untuk tiap skema itu buat TEPAT 3 judul PKM kreatif sesuai karakter skema. "
        "Pertahankan subjek inti ide mahasiswa. JANGAN menaruh metode komputasi "
        "(machine learning/IoT/sensor) pada skema sosial (PKM-RSH/PKM-PM) kecuali relevan.\n"
        "3. Pilih TEPAT 3 dosen terbaik dari daftar dengan match_score (0-100), "
        "expertise ringkas, dan alasan 1-2 kalimat.\n\n"
        "FORMAT JSON WAJIB:\n"
        "{\n"
        '  "schemas": [{"schema":"PKM-XX","confidence":0.0,"reason":"..."}],\n'
        '  "schema_titles": [{"schema":"PKM-XX","titles":["...","...","..."]}],\n'
        '  "dosen": [{"name":"...","match_score":0,"expertise":"...","reason":"..."}],\n'
        '  "notes": "ringkas dasar rekomendasi"\n'
        "}"
    )
    return system, user


# ── Provider LLM (Groq utama -> Gemini cadangan) ────────────────────────────
def _groq_chat(system: str, user: str, timeout: int = 45):
    key = _next_key("groq")
    if not key:
        raise LLMUnavailable("Groq API key tidak tersedia")
    payload = {
        "model": settings.GROQ_PRIMARY_MODEL,
        "messages": [
            {"role": "system", "content": system},
            {"role": "user", "content": user},
        ],
        "temperature": 0.5,
        "max_tokens": 3000,
        "response_format": {"type": "json_object"},
    }
    data = json.dumps(payload).encode("utf-8")
    req = urllib.request.Request(
        settings.GROQ_API_URL, data=data, method="POST",
        headers={"Authorization": f"Bearer {key}", "Content-Type": "application/json"},
    )
    try:
        with urllib.request.urlopen(req, timeout=timeout) as resp:
            body = json.loads(resp.read().decode("utf-8"))
        return body["choices"][0]["message"]["content"], settings.GROQ_PRIMARY_MODEL
    except (urllib.error.URLError, urllib.error.HTTPError, socket.timeout, KeyError, IndexError) as exc:
        raise LLMUnavailable(f"Groq gagal: {exc}") from exc


def _gemini_generate(system: str, user: str, timeout: int = 45):
    key = _next_key("gemini")
    if not key:
        raise LLMUnavailable("Gemini API key tidak tersedia")
    model = getattr(settings, "GEMINI_MODEL", "gemini-2.0-flash")
    base = getattr(settings, "GEMINI_API_URL", "https://generativelanguage.googleapis.com/v1beta/models")
    url = f"{base}/{model}:generateContent?key={key}"
    payload = {
        "contents": [{"parts": [{"text": system + "\n\n" + user}]}],
        "generationConfig": {"response_mime_type": "application/json", "temperature": 0.5},
    }
    data = json.dumps(payload).encode("utf-8")
    req = urllib.request.Request(
        url, data=data, method="POST", headers={"Content-Type": "application/json"}
    )
    try:
        with urllib.request.urlopen(req, timeout=timeout) as resp:
            body = json.loads(resp.read().decode("utf-8"))
        text = body["candidates"][0]["content"]["parts"][0]["text"]
        return text, f"gemini/{model}"
    except (urllib.error.URLError, urllib.error.HTTPError, socket.timeout, KeyError, IndexError) as exc:
        raise LLMUnavailable(f"Gemini gagal: {exc}") from exc


def _call_llm(system: str, user: str):
    errors = []
    for provider, fn in (("groq", _groq_chat), ("gemini", _gemini_generate)):
        try:
            content, model = fn(system, user)
            return content, provider, model
        except LLMUnavailable as exc:
            logger.warning("%s", exc)
            errors.append(str(exc))
            continue
    raise LLMUnavailable("; ".join(errors) or "Tidak ada provider LLM tersedia")


def _parse_json(content: str) -> dict:
    try:
        return json.loads(content)
    except json.JSONDecodeError:
        m = re.search(r"\{.*\}", content, re.DOTALL)
        if not m:
            raise
        return json.loads(m.group(0))


# ── Bangun envelope keluaran (identik struktur dengan Pendekatan A) ─────────
def _clean_titles(titles) -> list[str]:
    out = []
    for t in titles or []:
        s = re.sub(r"^[\-\*\d\.\)\(\s]+", "", str(t)).strip().strip('"').strip("'")
        if s and s not in out:
            out.append(s)
    return out[:3]


def _num_score(value, default=0.0):
    if not isinstance(value, (int, float)):
        return default
    return round(value * 100, 1) if value <= 1 else round(value, 1)


def _build_envelope(parsed, provider, model, examples, cached=False, notes_override=None):
    schema_titles = {}
    for g in parsed.get("schema_titles") or []:
        if not isinstance(g, dict):
            continue
        m = re.search(r"PKM-[A-Z]+", str(g.get("schema") or "").upper())
        if m:
            schema_titles[m.group(0)] = _clean_titles(g.get("titles"))

    schema_recommendations, schema_title_groups = [], []
    for s in (parsed.get("schemas") or [])[:3]:
        if not isinstance(s, dict):
            continue
        m = re.search(r"PKM-[A-Z]+", str(s.get("schema") or "").upper())
        code = m.group(0) if m else ""
        if not code:
            continue
        conf = _num_score(s.get("confidence", 0))
        reason = s.get("reason", "")
        schema_recommendations.append(
            {"predicted_schema": code, "confidence_score": conf, "details": reason}
        )
        schema_title_groups.append(
            {"schema": code, "confidence_score": conf, "reason": reason,
             "titles": schema_titles.get(code, [])}
        )

    primary = schema_recommendations[0] if schema_recommendations else {
        "predicted_schema": None, "confidence_score": 0, "details": ""
    }
    schema_result = dict(primary)
    schema_result["alternatives"] = schema_recommendations

    dept_by_name = {d["name"].lower(): d["dept"] for d in _all_dosen()}
    dosen_out = []
    for d in (parsed.get("dosen") or [])[:3]:
        if not isinstance(d, dict):
            continue
        name = str(d.get("name") or "").strip()
        if not name:
            continue
        dosen_out.append({
            "name": name,
            "program_studi": dept_by_name.get(name.lower()),
            "match_score": _num_score(d.get("match_score", 0)),
            "expertise": str(d.get("expertise") or "")[:160],
            "reason": d.get("reason") or "",
        })

    recommended_titles = schema_title_groups[0]["titles"] if schema_title_groups else []
    historical = [{"title": t, "similarity_score": None} for t in examples[:3]]

    return {
        "input": {},
        "schema": schema_result,
        "schema_recommendations": schema_recommendations,
        "schema_title_groups": schema_title_groups,
        "raw_dosen_recommendations": parsed.get("dosen") or [],
        "historical_titles": historical,
        "local_titles": recommended_titles,
        "dosen_recommendations": dosen_out,
        "recommended_titles": recommended_titles,
        "llm": {
            "provider": provider,
            "model": model,
            "used_live_search": False,
            "approach": "B",
            "cached": cached,
            "notes": notes_override or parsed.get("notes")
            or "Rekomendasi penuh dihasilkan LLM (Pendekatan B / Full LLM).",
        },
    }


# ── Entry point ─────────────────────────────────────────────────────────────
def _norm(idea: str) -> str:
    return re.sub(r"\s+", " ", str(idea).strip().lower())


def generate_full_llm(idea: str, has_partner: bool = False, partner_is_profit: bool = False):
    """Kembalikan envelope Pendekatan B, atau None bila LLM tidak tersedia
    (agar pemanggil bisa fallback ke Pendekatan A)."""
    cache_key = None
    if getattr(settings, "FULL_LLM_CACHE_ENABLED", True):
        cache_key = hashlib.sha256(
            f"B|{_norm(idea)}|{int(bool(has_partner))}|{int(bool(partner_is_profit))}".encode("utf-8")
        ).hexdigest()
        with _cache_lock:
            if cache_key in _CACHE:
                env = json.loads(json.dumps(_CACHE[cache_key]))
                env["llm"]["cached"] = True
                logger.info("Full LLM cache hit")
                return env

    examples = _pick_examples(idea)
    dosen = _all_dosen()
    system, user = _build_prompt(idea, has_partner, partner_is_profit, examples, dosen)

    acquired = _llm_semaphore.acquire(timeout=float(getattr(settings, "FULL_LLM_QUEUE_TIMEOUT", 60)))
    if not acquired:
        logger.warning("Antrean LLM penuh (timeout) — fallback ke Pendekatan A")
        return None
    try:
        content, provider, model = _call_llm(system, user)
    except LLMUnavailable as exc:
        logger.error("Full LLM tidak tersedia: %s", exc)
        return None
    finally:
        _llm_semaphore.release()

    try:
        parsed = _parse_json(content)
    except Exception as exc:
        logger.error("Gagal parse JSON LLM: %s", exc)
        return None

    env = _build_envelope(parsed, provider, model, examples)
    if cache_key:
        with _cache_lock:
            _CACHE[cache_key] = json.loads(json.dumps(env))
    return env
