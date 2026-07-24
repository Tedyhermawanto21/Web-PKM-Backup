"""
Hitung metrik perbandingan Pendekatan A (retrieval_based) vs B (full_llm)
berdasarkan ground truth kesesuaian dosen terhadap judul hasil generate.

Prasyarat:
- eval/results_raw.jsonl
- eval/title_dosen_groundtruth.csv, dengan score_v1 dan score_v2 terisi 0-2

Jalankan dari folder pkm-recommender:
    .venv\\Scripts\\python.exe eval\\evaluate.py

Output:
- eval/metrics_summary.csv

Metrik:
- Precision@3
- nDCG@3
- rata-rata latency
"""
import json
import math
import os

import numpy as np
import pandas as pd

EVAL_DIR = os.path.dirname(__file__)
K = 3
REL_THRESHOLD = 1.0


def _p(name):
    return os.path.join(EVAL_DIR, name)


def _load_raw():
    rows = []
    with open(_p("results_raw.jsonl"), "r", encoding="utf-8") as f:
        for line in f:
            line = line.strip()
            if line:
                rows.append(json.loads(line))
    return rows


def _load_title_dosen_groundtruth():
    compact_path = _p("title_dosen_groundtruth_compact.csv")
    if os.path.exists(compact_path):
        compact = pd.read_csv(compact_path, dtype={"title_id": str})
        score_cols = [
            c for c in compact.columns
            if c.startswith("score_v") or c.startswith("skor_dosen_")
        ]
        compact_has_scores = False
        for col in score_cols:
            if pd.to_numeric(compact[col], errors="coerce").notna().any():
                compact_has_scores = True
                break
        if compact_has_scores:
            rows = []
            for _, row in compact.iterrows():
                for rank in range(1, K + 1):
                    score_v1 = row.get(f"skor_dosen_{rank}", np.nan)
                    if pd.isna(score_v1):
                        score_v1 = row.get(f"score_v1_dosen_{rank}", np.nan)
                    rows.append({
                        "title_id": row.get("title_id", ""),
                        "mode": row.get("mode", ""),
                        "lecturer_rank": rank,
                        "score_v1": score_v1,
                        "score_v2": row.get(f"score_v2_dosen_{rank}", np.nan),
                    })
            df = pd.DataFrame(rows)
            df["score_v1"] = pd.to_numeric(df["score_v1"], errors="coerce")
            df["score_v2"] = pd.to_numeric(df["score_v2"], errors="coerce")
            df["rel"] = df[["score_v1", "score_v2"]].mean(axis=1)
            return df.dropna(subset=["rel"])

    df = pd.read_csv(
        _p("title_dosen_groundtruth.csv"),
        dtype={"title_id": str, "lecturer_id": str},
    )
    df["score_v1"] = pd.to_numeric(df["score_v1"], errors="coerce")
    df["score_v2"] = pd.to_numeric(df["score_v2"], errors="coerce")
    df["rel"] = df[["score_v1", "score_v2"]].mean(axis=1)
    return df.dropna(subset=["rel"])


def _dcg(rels):
    return sum(rel / math.log2(i + 2) for i, rel in enumerate(rels))


def _dosen_metrics(df):
    per_mode = {}
    if df.empty:
        return per_mode

    for (mode, title_id), group in df.groupby(["mode", "title_id"], sort=False):
        ranked = group.sort_values("lecturer_rank").head(K)
        gains = [float(v) for v in ranked["rel"].tolist()]
        if not gains:
            continue

        precision = sum(1 for g in gains if g >= REL_THRESHOLD) / K
        ideal = sorted(gains, reverse=True)[:K]
        idcg = _dcg(ideal)
        ndcg = (_dcg(gains) / idcg) if idcg > 0 else 0.0

        metrics = per_mode.setdefault(mode, {
            "precision": [],
            "ndcg": [],
            "title_count": set(),
        })
        metrics["precision"].append(precision)
        metrics["ndcg"].append(ndcg)
        metrics["title_count"].add(title_id)

    return per_mode


def _latency(raw):
    per_mode = {}
    for row in raw:
        mode = row.get("mode")
        if not mode:
            continue
        metrics = per_mode.setdefault(mode, {"latency": []})
        if row.get("latency_ms") is not None:
            metrics["latency"].append(row["latency_ms"])
    return per_mode


def _avg(values):
    return round(float(np.mean(values)), 4) if values else None


def main():
    raw = _load_raw()
    groundtruth = _load_title_dosen_groundtruth()
    dosen = _dosen_metrics(groundtruth)
    latency = _latency(raw)

    rows = []
    for mode in ["retrieval_based", "full_llm"]:
        d = dosen.get(mode, {})
        l = latency.get(mode, {})
        rows.append({
            "mode": mode,
            "precision@3": _avg(d.get("precision", [])),
            "ndcg@3": _avg(d.get("ndcg", [])),
            "jumlah_judul_dinilai": len(d.get("title_count", set())),
            "latency_ms_rata2": _avg(l.get("latency", [])),
        })

    df = pd.DataFrame(rows)
    out_path = _p("metrics_summary.csv")
    df.to_csv(out_path, index=False)

    print("\n=== RINGKASAN METRIK: Pendekatan A vs B ===")
    print(df.to_string(index=False))
    print(f"\nTersimpan: {out_path}")
    if groundtruth.empty:
        print("\n[!] title_dosen_groundtruth.csv belum diisi skor -> Precision/nDCG kosong.")


if __name__ == "__main__":
    main()
