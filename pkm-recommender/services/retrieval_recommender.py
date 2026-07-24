"""
Retrieval-Based Recommender (Pendekatan A) — MURNI tanpa LLM.

Dosen : input topik/abstrak -> Sentence-BERT -> FAISS -> cosine -> Top-K.
Judul : input ide -> Sentence-BERT -> FAISS judul historis -> Template M-O-T-K -> MMR -> 3.

LARANGAN (dijaga): modul ini TIDAK meng-import maupun memanggil Groq/Gemini/LLM
apa pun. Alasan kecocokan dosen dibuat dari template data (keywords/prodi),
bukan dari LLM.
"""
import time

from core.config import settings
from services.dosen_matcher import dosen_matcher
from services.title_generator import title_generator


def _reason(keywords: str, dept: str) -> str:
    kw = (keywords or "").strip()
    dp = (dept or "").strip()
    if kw and dp:
        return f"Bidang keahlian ({kw}) pada program studi {dp} relevan dengan topik proposal."
    if kw:
        return f"Bidang keahlian ({kw}) relevan dengan topik proposal."
    return "Profil keahlian dosen dinilai relevan berdasarkan kemiripan semantik topik."


def _lecturers(text: str, top_k: int) -> list[dict]:
    results = dosen_matcher.suggest_dosen(text, top_k=top_k)
    if isinstance(results, dict) and results.get("error"):
        return []
    out = []
    for rank, item in enumerate(results, start=1):
        d = item.get("dosen", {}) or {}
        lid = d.get("lecturer_id")
        out.append({
            "lecturer_id": (str(lid) if lid is not None else None),
            "lecturer_name": d.get("name") or "-",
            "rank": rank,
            "reason": _reason(d.get("keywords"), d.get("dept") or d.get("program_studi")),
            "score": round(float(item.get("similarity_score", 0.0)), 4),
        })
    return out


def recommend(idea: str, abstract: str = "", pkm_category: str = "",
              top_k_lecturers: int = 3, total_titles: int = 3) -> dict:
    start = time.perf_counter()
    text = " ".join(t for t in [idea, abstract] if t).strip() or idea

    lecturers = _lecturers(text, top_k_lecturers)
    titles = title_generator.generate_titles_structured(idea or text, total_titles)

    latency_ms = int((time.perf_counter() - start) * 1000)
    return {
        "mode": "retrieval_based",
        "method_label": "Pendekatan A",
        "lecturer_recommendations": lecturers,
        "title_recommendations": titles,
        "experiment_metadata": {
            "provider": "local",
            "model_name": settings.SENTENCE_BERT_MODEL,
            "latency_ms": latency_ms,
            "input_tokens": 0,
            "output_tokens": 0,
            "total_tokens": 0,
            "error": None,
        },
    }
