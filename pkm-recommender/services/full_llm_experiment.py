"""
Full LLM Recommender (Pendekatan B) — MURNI LLM.

Dosen & judul dihasilkan langsung oleh SATU provider LLM (dari env) melalui
prompt terstruktur. LARANGAN (dijaga): TIDAK meng-import/memanggil Sentence-BERT,
FAISS, Template Mining, atau MMR. Dosen hanya boleh dipilih dari data yang
diberikan; ID/nama di luar dataset dibuang saat validasi.
"""
import json
import re
import time

import pandas as pd

from core.config import settings
from services import llm_client
from services.llm_client import LLMError

# ── Prompt sistem (sesuai ketentuan eksperimen) ─────────────────────────────
DOSEN_SYSTEM = (
    "Anda adalah mesin rekomendasi dosen pembimbing Program Kreativitas Mahasiswa.\n"
    "Gunakan hanya data dosen yang diberikan pada input.\n"
    "Dilarang membuat nama, ID, bidang keahlian, atau publikasi baru.\n"
    "Pilih tepat {top_k} dosen yang paling sesuai dengan topik proposal.\n"
    "Dasar penilaian:\n"
    "1. Kesesuaian bidang keahlian.\n"
    "2. Kesesuaian publikasi atau riwayat penelitian.\n"
    "3. Kesesuaian metode penelitian.\n"
    "4. Kesesuaian objek dan konteks proposal.\n"
    "Kembalikan JSON valid tanpa markdown dan tanpa penjelasan tambahan.\n"
    "Format:\n"
    '{"lecturer_recommendations":[{"lecturer_id":"ID dari input","lecturer_name":'
    '"nama dari input","rank":1,"reason":"alasan ringkas berdasarkan data yang '
    'tersedia","score":0.0}]}\n'
    "Score harus berada pada rentang 0 sampai 1.\n"
    "Tidak boleh memilih dosen yang tidak terdapat dalam input."
)

TITLE_SYSTEM = (
    "Anda adalah mesin rekomendasi judul proposal Program Kreativitas Mahasiswa.\n"
    "Buat tepat tiga alternatif judul berdasarkan informasi pengguna.\n"
    "Setiap judul harus:\n"
    "1. Relevan dengan ide utama.\n"
    "2. Mengandung metode yang SPESIFIK sesuai domain topik, bukan istilah payung generik.\n"
    "3. Memiliki objek atau target yang jelas dan operasional.\n"
    "4. Menjelaskan tujuan utama.\n"
    "5. Memiliki konteks penerapan.\n"
    "6. Menggunakan Bahasa Indonesia akademik yang mudah dipahami.\n"
    "7. Tidak membuat klaim hasil penelitian yang belum dilakukan.\n"
    "8. Tidak menyalin mentah judul referensi.\n"
    "9. Jangan memaksakan metode teknologi/IT jika topik lebih cocok dengan pendekatan "
    "sosial, edukasi, kesehatan masyarakat, pengabdian, kewirausahaan, lingkungan, atau seni budaya.\n"
    "10. Hindari metode generik seperti 'Machine Learning', 'Teknologi', 'Kecerdasan Buatan', "
    "'Pendekatan Inovatif', 'Edukasi', atau 'Pelatihan' jika masih bisa dibuat lebih spesifik.\n"
    "11. Untuk topik teknologi, turunkan istilah umum menjadi metode/algoritma/arsitektur yang konkret.\n"
    "12. Untuk topik non-teknologi, gunakan metode bidang yang konkret seperti survei kuantitatif, "
    "wawancara mendalam, studi fenomenologi, analisis tematik, Participatory Action Research, "
    "design thinking, Business Model Canvas, uji organoleptik, ADDIE, quasi experiment, "
    "edukasi partisipatif, pendampingan komunitas, atau metode lain yang sesuai.\n"
    "13. Tiga judul harus memakai metode yang berbeda agar tampak sebagai alternatif nyata.\n"
    "Kembalikan JSON valid tanpa markdown dan tanpa teks tambahan.\n"
    "Format:\n"
    '{"title_recommendations":[{"rank":1,"title":"judul lengkap","method":"metode",'
    '"object":"objek atau target","goal":"tujuan","context":"konteks"}]}'
)


# ── Dataset dosen (dibaca langsung; BUKAN via FAISS/SBERT) ───────────────────
_DOSEN_DF = None


def _load_dosen():
    global _DOSEN_DF
    if _DOSEN_DF is None:
        try:
            _DOSEN_DF = pd.read_excel(settings.LECTURERS_FILE)
        except Exception:
            _DOSEN_DF = pd.DataFrame(columns=["lecturer_id", "name", "dept", "keywords"])
    return _DOSEN_DF


def _dosen_records() -> list[dict]:
    df = _load_dosen()
    recs = []
    for _, r in df.iterrows():
        name = str(r.get("name") or "").strip()
        if not name:
            continue
        lid = r.get("lecturer_id")
        recs.append({
            "lecturer_id": str(lid) if pd.notnull(lid) else "",
            "name": name,
            "dept": str(r.get("dept") or "").strip(),
            "keywords": str(r.get("keywords") or "").strip(),
        })
    return recs


# ── Prompt pengguna ─────────────────────────────────────────────────────────
def _build_dosen_user(idea: str, abstract: str, dataset: list[dict], top_k: int) -> str:
    lines = "\n".join(
        f'- lecturer_id={d["lecturer_id"]} | nama={d["name"]} | prodi={d["dept"]} | keahlian={d["keywords"]}'
        for d in dataset
    )
    return (
        f"TOPIK/IDE PROPOSAL:\n{idea or '-'}\n\n"
        f"ABSTRAK:\n{abstract or '-'}\n\n"
        f"DAFTAR DOSEN TERSEDIA (pilih HANYA dari daftar ini, gunakan lecturer_id & nama persis):\n{lines}\n\n"
        f"Pilih tepat {top_k} dosen paling sesuai."
    )


def _build_title_user(idea: str, abstract: str, pkm_category: str) -> str:
    guidance = _method_guidance_for_topic(f"{idea}\n{abstract}")
    return (
        f"IDE PROPOSAL:\n{idea or '-'}\n\n"
        f"ABSTRAK/DESKRIPSI:\n{abstract or '-'}\n\n"
        f"KATEGORI PKM: {pkm_category or '-'}\n\n"
        f"ARAHAN METODE SPESIFIK:\n{guidance}\n\n"
        "Buat tepat tiga alternatif judul. Gunakan pola M-O-T-K: Metode, Objek, "
        "Tujuan, dan Konteks. Isi field method dengan nama metode spesifik yang "
        "benar-benar muncul di judul. Jangan mengisi method hanya dengan 'Machine Learning'."
    )


def _method_guidance_for_topic(text: str) -> str:
    lower = str(text or "").lower()
    if any(term in lower for term in ["literasi digital", "pelatihan", "pendampingan", "penyuluhan", "edukasi", "pemberdayaan", "desa", "ibu rumah tangga", "masyarakat", "komunitas"]):
        return (
            "- Domain pengabdian masyarakat/edukasi komunitas terdeteksi.\n"
            "- Jangan memaksakan machine learning, IoT, CNN, atau metode komputasi jika tidak diminta input.\n"
            "- Gunakan metode intervensi sosial yang spesifik, misalnya: Participatory Action Research (PAR), "
            "Community-Based Education, pelatihan partisipatif, pendampingan komunitas, penyuluhan berbasis modul, "
            "pretest-posttest edukasi, atau Training of Trainers.\n"
            "- Objek disarankan: kelompok sasaran yang disebut input, perilaku/literasi/keterampilan yang ingin ditingkatkan.\n"
            "- Konteks disarankan: desa, sekolah, komunitas, keluarga, UMKM, atau mitra non-profit sesuai input."
        )
    if any(term in lower for term in ["pengaruh", "dampak", "perilaku", "persepsi", "remaja", "gawai", "gadget", "hp", "kesehatan mental", "sosial", "budaya", "humaniora"]):
        return (
            "- Domain sosial humaniora terdeteksi.\n"
            "- Jangan memaksakan metode teknologi/IT jika topik berupa perilaku, persepsi, dampak sosial, atau kesehatan mental.\n"
            "- Gunakan metode riset sosial yang spesifik, misalnya: survei kuantitatif, wawancara mendalam, "
            "studi fenomenologi, analisis tematik, analisis korelasional, SEM-PLS, regresi ordinal, "
            "atau mixed methods sesuai input.\n"
            "- Objek disarankan: perilaku/persepsi/kelompok sosial yang disebut input.\n"
            "- Konteks disarankan: sekolah, keluarga, komunitas remaja, kampus, atau masyarakat sesuai input."
        )
    if any(term in lower for term in ["produk", "camilan", "keripik", "usaha", "bisnis", "kewirausahaan", "nilai jual", "pemasaran", "umkm", "warung", "komersial"]):
        return (
            "- Domain kewirausahaan/UMKM terdeteksi.\n"
            "- Gunakan metode bisnis atau pengembangan produk yang spesifik, misalnya: Design Thinking, "
            "Business Model Canvas, analisis SWOT, uji organoleptik, uji preferensi konsumen, "
            "Minimum Viable Product (MVP), strategi STP, atau bauran pemasaran 4P.\n"
            "- Jangan memaksakan algoritma AI jika input hanya tentang produk/usaha non-digital.\n"
            "- Objek disarankan: produk/jasa/mitra usaha yang disebut input.\n"
            "- Konteks disarankan: pasar mahasiswa, UMKM, konsumen lokal, atau kanal pemasaran sesuai input."
        )
    if any(term in lower for term in ["pembelajaran", "media belajar", "siswa", "guru", "sekolah", "kurikulum", "e-learning", "edukatif", "pendidikan"]):
        return (
            "- Domain pendidikan terdeteksi.\n"
            "- Gunakan metode pendidikan yang spesifik, misalnya: ADDIE, Research and Development (R&D), "
            "quasi experiment, pretest-posttest control group, Problem-Based Learning, Project-Based Learning, "
            "atau evaluasi usability media pembelajaran.\n"
            "- Metode teknologi hanya digunakan jika input memang menyebut aplikasi, media digital, AI, atau sistem.\n"
            "- Objek disarankan: media, proses belajar, kompetensi, atau kelompok siswa yang disebut input."
        )
    if any(term in lower for term in ["kesehatan", "gizi", "stunting", "posyandu", "diabetes", "hipertensi", "skrining", "pola makan", "sanitasi"]):
        return (
            "- Domain kesehatan masyarakat terdeteksi.\n"
            "- Gunakan metode kesehatan/promosi kesehatan yang spesifik, misalnya: edukasi kesehatan berbasis KAP, "
            "skrining risiko, intervensi promotif-preventif, survei cross-sectional, pretest-posttest, "
            "pendampingan kader, atau analisis faktor risiko.\n"
            "- Jangan memaksakan model AI jika input tidak menyebut analisis data komputasional."
        )
    if any(term in lower for term in ["sampah", "limbah", "kompos", "komposting", "eco enzyme", "lingkungan", "daur ulang", "polusi", "bioplastik"]):
        return (
            "- Domain lingkungan terdeteksi.\n"
            "- Gunakan metode lingkungan yang spesifik, misalnya: komposting aerob, eco-enzyme, bioremediasi, "
            "bank sampah berbasis partisipasi warga, audit sampah, uji biodegradabilitas, atau edukasi perilaku lingkungan.\n"
            "- Metode IoT/AI hanya dipilih jika input memang menyebut sensor, monitoring otomatis, atau prediksi data."
        )
    if any(term in lower for term in ["curah hujan", "hujan", "cuaca", "meteorologi", "iklim"]):
        return (
            "- Domain cuaca/curah hujan terdeteksi.\n"
            "- Gunakan metode prediksi deret waktu atau regresi yang spesifik, misalnya: "
            "Long Short-Term Memory (LSTM), Gated Recurrent Unit (GRU), Random Forest Regression, "
            "XGBoost Regression, SARIMA, Prophet, atau ensemble LSTM-XGBoost.\n"
            "- Objek disarankan: curah hujan harian lokal, data historis cuaca, atau informasi cuaca harian.\n"
            "- Konteks disarankan: meteorologi lokal, wilayah/kecamatan tertentu, atau layanan informasi cuaca masyarakat."
        )
    if any(term in lower for term in ["gambar", "citra", "wajah", "kamera", "deteksi objek", "computer vision"]):
        return (
            "- Domain computer vision terdeteksi.\n"
            "- Gunakan metode spesifik seperti YOLOv8, CNN, ResNet, MobileNet, Haar Cascade, "
            "atau Vision Transformer sesuai objek input."
        )
    if any(term in lower for term in ["sentimen", "teks", "ulasan", "komentar", "chatbot", "bahasa"]):
        return (
            "- Domain NLP/teks terdeteksi.\n"
            "- Gunakan metode spesifik seperti IndoBERT, TF-IDF + SVM, Naive Bayes, LSTM, "
            "atau Retrieval-Augmented Generation jika konteksnya pencarian dokumen."
        )
    if any(term in lower for term in ["iot", "sensor", "monitoring", "arduino", "esp32", "mikrokontroler"]):
        return (
            "- Domain IoT/embedded terdeteksi.\n"
            "- Gunakan metode/teknologi spesifik seperti ESP32, MQTT, sensor DHT22, sensor kelembaban tanah, "
            "edge monitoring, atau rule-based alerting sesuai objek input."
        )
    return (
        "- Pilih metode spesifik sesuai domain input, bukan otomatis teknologi/IT.\n"
        "- Jika input bersifat sosial, gunakan metode riset sosial atau intervensi komunitas.\n"
        "- Jika input bersifat bisnis/produk, gunakan metode pengembangan produk dan validasi pasar.\n"
        "- Jika input bersifat pendidikan, gunakan metode R&D, ADDIE, eksperimen pembelajaran, atau evaluasi media.\n"
        "- Jika input menyebut machine learning secara umum, baru turunkan menjadi algoritma yang lebih konkret "
        "seperti Random Forest, XGBoost, SVM, Naive Bayes, K-Means, LSTM, atau CNN sesuai jenis masalah."
    )


# ── Parsing & validasi ──────────────────────────────────────────────────────
def _extract_json(content: str) -> dict:
    try:
        return json.loads(content)
    except json.JSONDecodeError:
        m = re.search(r"\{.*\}", content or "", re.DOTALL)
        if not m:
            raise
        return json.loads(m.group(0))


def _call_and_parse(system: str, user: str):
    """Panggil LLM; bila JSON tidak valid, retry SATU kali. Token dijumlahkan."""
    content, usage, model = llm_client.chat(system, user)
    try:
        return _extract_json(content), usage, model
    except Exception:
        content2, usage2, model = llm_client.chat(system, user)
        parsed = _extract_json(content2)  # bila masih gagal -> raise ke pemanggil
        usage = {k: usage.get(k, 0) + usage2.get(k, 0) for k in ("input_tokens", "output_tokens", "total_tokens")}
        return parsed, usage, model


def _validate_lecturers(parsed: dict, dataset: list[dict], top_k: int) -> list[dict]:
    by_id = {d["lecturer_id"]: d for d in dataset if d["lecturer_id"]}
    by_name = {d["name"].lower(): d for d in dataset}
    out, seen = [], set()
    for item in (parsed.get("lecturer_recommendations") or []):
        if not isinstance(item, dict):
            continue
        lid = str(item.get("lecturer_id") or "").strip()
        lname = str(item.get("lecturer_name") or "").strip()
        match = by_id.get(lid) or by_name.get(lname.lower())
        if not match:                       # buang dosen yang tidak ada di dataset
            continue
        if match["lecturer_id"] in seen:    # cegah ID ganda
            continue
        seen.add(match["lecturer_id"])
        try:
            score = float(item.get("score", 0))
        except (TypeError, ValueError):
            score = 0.0
        score = max(0.0, min(1.0, score))
        out.append({
            "lecturer_id": match["lecturer_id"],
            "lecturer_name": match["name"],  # nama otoritatif dari dataset
            "rank": 0,
            "reason": str(item.get("reason") or "").strip(),
            "score": round(score, 4),
        })
        if len(out) >= top_k:               # maksimal Top-K
            break
    for i, o in enumerate(out, start=1):
        o["rank"] = i
    return out


def _validate_titles(parsed: dict, total: int) -> list[dict]:
    out = []
    for item in (parsed.get("title_recommendations") or []):
        if not isinstance(item, dict):
            continue
        title = str(item.get("title") or "").strip()
        if not title:
            continue
        out.append({
            "rank": 0,
            "title": title,
            "method": str(item.get("method") or "").strip(),
            "object": str(item.get("object") or "").strip(),
            "goal": str(item.get("goal") or "").strip(),
            "context": str(item.get("context") or "").strip(),
        })
    out = out[:total]                       # pastikan tidak lebih dari yang diminta
    for i, o in enumerate(out, start=1):
        o["rank"] = i
    return out


# ── Entry point ─────────────────────────────────────────────────────────────
def recommend(idea: str, abstract: str = "", pkm_category: str = "",
              top_k_lecturers: int = 3, total_titles: int = 3) -> dict:
    start = time.perf_counter()
    provider = settings.FULL_LLM_PROVIDER
    model_name = settings.FULL_LLM_MODEL
    lecturers, titles = [], []
    input_tokens = output_tokens = total_tokens = 0
    error = None

    dataset = _dosen_records()
    try:
        if not dataset:
            raise LLMError("Data dosen kosong. Tidak dapat menjalankan Pendekatan B.")

        # 1) Rekomendasi dosen (satu panggilan LLM)
        dosen_sys = DOSEN_SYSTEM.replace("{top_k}", str(top_k_lecturers))
        dosen_user = _build_dosen_user(idea, abstract, dataset, top_k_lecturers)
        parsed_d, usage_d, model_name = _call_and_parse(dosen_sys, dosen_user)
        lecturers = _validate_lecturers(parsed_d, dataset, top_k_lecturers)

        # 2) Rekomendasi judul (provider & model yang sama)
        title_user = _build_title_user(idea, abstract, pkm_category)
        parsed_t, usage_t, model_name = _call_and_parse(TITLE_SYSTEM, title_user)
        titles = _validate_titles(parsed_t, total_titles)

        input_tokens = usage_d["input_tokens"] + usage_t["input_tokens"]
        output_tokens = usage_d["output_tokens"] + usage_t["output_tokens"]
        total_tokens = usage_d["total_tokens"] + usage_t["total_tokens"]
    except LLMError as exc:
        error = str(exc)
    except json.JSONDecodeError:
        error = "Output LLM bukan JSON valid setelah percobaan ulang."
    except Exception as exc:  # pragma: no cover - defensif
        error = f"Kesalahan tak terduga: {exc}"

    latency_ms = int((time.perf_counter() - start) * 1000)
    return {
        "mode": "full_llm",
        "method_label": "Pendekatan B",
        "lecturer_recommendations": lecturers,
        "title_recommendations": titles,
        "experiment_metadata": {
            "provider": provider,
            "model_name": model_name,
            "latency_ms": latency_ms,
            "input_tokens": input_tokens,
            "output_tokens": output_tokens,
            "total_tokens": total_tokens,
            "error": error,
        },
    }
