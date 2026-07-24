"""
Pencatatan hasil eksperimen komparatif (Pendekatan A vs B).

Menulis ke JSONL dan CSV di folder `pkm-recommender/logs/`. Latency asli diukur
di masing-masing modul recommend(); cache tidak dipakai pada jalur eksperimen.
Tidak pernah menyimpan API key.
"""
import csv
import hashlib
import json
import os
import uuid
from datetime import datetime, timezone

LOG_DIR = os.path.abspath(os.path.join(os.path.dirname(__file__), "..", "logs"))
JSONL_PATH = os.path.join(LOG_DIR, "experiments.jsonl")
CSV_PATH = os.path.join(LOG_DIR, "experiments.csv")

FIELDS = [
    "experiment_id", "proposal_id", "mode", "model_name", "input_hash",
    "generated_at", "latency_ms", "input_tokens", "output_tokens",
    "total_tokens", "lecturer_results", "title_results", "success", "error_message",
]


def compute_input_hash(payload: dict) -> str:
    basis = json.dumps(
        {k: payload.get(k) for k in ("idea", "abstract", "pkm_category", "mode",
                                     "top_k_lecturers", "total_titles")},
        ensure_ascii=False, sort_keys=True,
    )
    return hashlib.sha256(basis.encode("utf-8")).hexdigest()


def log_experiment(payload: dict, result: dict) -> dict:
    os.makedirs(LOG_DIR, exist_ok=True)
    meta = result.get("experiment_metadata", {}) or {}
    record = {
        "experiment_id": str(uuid.uuid4()),
        "proposal_id": payload.get("proposal_id"),
        "mode": result.get("mode"),
        "model_name": meta.get("model_name"),
        "input_hash": compute_input_hash(payload),
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "latency_ms": meta.get("latency_ms"),
        "input_tokens": meta.get("input_tokens"),
        "output_tokens": meta.get("output_tokens"),
        "total_tokens": meta.get("total_tokens"),
        "lecturer_results": result.get("lecturer_recommendations"),
        "title_results": result.get("title_recommendations"),
        "success": meta.get("error") is None,
        "error_message": meta.get("error"),
    }

    with open(JSONL_PATH, "a", encoding="utf-8") as f:
        f.write(json.dumps(record, ensure_ascii=False) + "\n")

    write_header = not os.path.exists(CSV_PATH)
    with open(CSV_PATH, "a", encoding="utf-8", newline="") as f:
        writer = csv.DictWriter(f, fieldnames=FIELDS)
        if write_header:
            writer.writeheader()
        row = dict(record)
        row["lecturer_results"] = json.dumps(record["lecturer_results"], ensure_ascii=False)
        row["title_results"] = json.dumps(record["title_results"], ensure_ascii=False)
        writer.writerow(row)

    return record
