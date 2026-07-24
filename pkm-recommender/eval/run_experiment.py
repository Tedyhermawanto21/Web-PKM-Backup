"""
Jalankan eksperimen komparatif pada seluruh kasus uji, lalu siapkan berkas
ground truth kesesuaian dosen terhadap judul hasil generate.

Jalankan dari folder pkm-recommender:
    .venv\\Scripts\\python.exe eval\\run_experiment.py

Menghasilkan (di folder eval/):
- results_raw.jsonl             : keluaran mentah tiap kasus x mode
- title_dosen_groundtruth.csv   : pasangan judul hasil generate x Top-3 dosen,
                                  dengan kolom score_v1 dan score_v2 untuk validator.

Skor validator:
- 0 = tidak sesuai
- 1 = cukup sesuai
- 2 = sesuai

Set EXPERIMENT_MODE=true di .env agar latency asli terukur.
"""
import csv
import json
import os
import sys

BASE = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
if BASE not in sys.path:
    sys.path.insert(0, BASE)

EVAL_DIR = os.path.dirname(__file__)
SEED = 42
MODES = ["retrieval_based", "full_llm"]
APPROACH_LABEL = {
    "retrieval_based": "Hybrid",
    "full_llm": "Gemini",
}
TOP_K = 3
TOTAL_TITLES = 3


def _load_testcases():
    with open(os.path.join(EVAL_DIR, "testcases.csv"), "r", encoding="utf-8") as f:
        return list(csv.DictReader(f))


def _run_all(cases):
    from services import retrieval_recommender, full_llm_experiment
    raw = []
    for i, c in enumerate(cases, start=1):
        for mode in MODES:
            print(f"[{i}/{len(cases)}] {c['id']} :: {mode} ...", flush=True)
            engine = retrieval_recommender if mode == "retrieval_based" else full_llm_experiment
            try:
                res = engine.recommend(
                    c["idea"], c.get("abstract", ""), c.get("pkm_category", ""),
                    top_k_lecturers=TOP_K, total_titles=TOTAL_TITLES,
                )
                meta = res.get("experiment_metadata", {})
                raw.append({
                    "case_id": c["id"], "mode": mode,
                    "lecturers": res.get("lecturer_recommendations", []),
                    "titles": res.get("title_recommendations", []),
                    "latency_ms": meta.get("latency_ms"),
                    "model_name": meta.get("model_name"),
                    "error": meta.get("error"),
                })
            except Exception as exc:  # pragma: no cover
                raw.append({"case_id": c["id"], "mode": mode, "lecturers": [],
                            "titles": [], "latency_ms": None, "model_name": None,
                            "error": f"runner error: {exc}"})
    return raw


def _write_csv(name, header, rows):
    path = os.path.join(EVAL_DIR, name)
    with open(path, "w", encoding="utf-8", newline="") as f:
        w = csv.writer(f)
        w.writerow(header)
        w.writerows(rows)
    print("Tulis:", path, f"({len(rows)} baris)")


def _write_raw(raw):
    path = os.path.join(EVAL_DIR, "results_raw.jsonl")
    with open(path, "w", encoding="utf-8") as f:
        for r in raw:
            f.write(json.dumps(r, ensure_ascii=False) + "\n")
    print("Tulis:", path)


def _write_title_dosen_groundtruth(raw):
    """Ground truth baru: validator menilai kecocokan judul hasil generate dengan Top-3 dosen."""
    from services.full_llm_experiment import _dosen_records
    info = {d["lecturer_id"]: d for d in _dosen_records()}

    rows = []
    title_seq = 1
    for r in raw:
        mode = r.get("mode", "")
        approach = APPROACH_LABEL.get(mode, mode)
        lecturers = r.get("lecturers", [])[:TOP_K]
        if not lecturers:
            continue
        for title in r.get("titles", [])[:TOTAL_TITLES]:
            title_id = f"J{title_seq:04d}"
            title_seq += 1
            for lecturer in lecturers:
                lid = str(lecturer.get("lecturer_id", ""))
                d = info.get(lid, {})
                rows.append([
                    r.get("case_id", ""),
                    title_id,
                    mode,
                    approach,
                    title.get("rank", ""),
                    title.get("title", ""),
                    lecturer.get("rank", ""),
                    lid,
                    d.get("name", lecturer.get("lecturer_name", "")),
                    d.get("dept", ""),
                    d.get("keywords", ""),
                    "",
                    "",
                ])
    _write_csv(
        "title_dosen_groundtruth.csv",
        [
            "case_id", "title_id", "mode", "approach", "title_rank", "title",
            "lecturer_rank", "lecturer_id", "lecturer_name", "dept", "keywords",
            "score_v1", "score_v2",
        ],
        rows,
    )


def main():
    cases = _load_testcases()
    print(f"Memuat {len(cases)} kasus uji (Top-{TOP_K} dosen, {TOTAL_TITLES} judul/pendekatan).")
    raw = _run_all(cases)
    _write_raw(raw)
    _write_title_dosen_groundtruth(raw)
    print("\nSelesai. Validator mengisi:")
    print("- title_dosen_groundtruth.csv -> score_v1 & score_v2 (0-2)")
    print("Lalu jalankan: .venv\\Scripts\\python.exe eval\\evaluate.py")


if __name__ == "__main__":
    main()
