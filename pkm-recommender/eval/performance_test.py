"""
Uji kinerja layanan rekomendasi untuk BAB IV.

Jalankan dari folder pkm-recommender:
    python eval\\performance_test.py

Script ini mengirim request terkontrol ke endpoint:
    POST /api/experiment/recommend

Output:
    eval/performance_raw.csv
    eval/performance_summary.csv

Catatan:
- Pastikan FastAPI recommender berjalan lebih dulu, misalnya di port 8002.
- Gemini dapat terkena rate limit, karena itu default jumlah request kecil.
"""
from __future__ import annotations

import argparse
import csv
import json
import os
import time
import urllib.error
import urllib.request
from datetime import datetime
from statistics import mean


EVAL_DIR = os.path.dirname(__file__)
DEFAULT_URL = os.environ.get(
    "PKM_RECOMMENDER_EXPERIMENT_URL",
    "http://127.0.0.1:8002/api/experiment/recommend",
)

DEFAULT_PAYLOAD = {
    "idea": "Prediksi curah hujan lokal menggunakan machine learning",
    "abstract": "Prediksi cuaca harian skala lokal menggunakan data historis cuaca.",
    "pkm_category": "PKM-RE",
    "top_k_lecturers": 3,
    "total_titles": 3,
}

RAW_FIELDS = [
    "run_at",
    "scenario",
    "request_no",
    "mode",
    "http_status",
    "success",
    "latency_ms",
    "wall_time_ms",
    "model_name",
    "error_message",
]

SUMMARY_FIELDS = [
    "scenario",
    "jumlah_request",
    "mode",
    "latency_rata2_ms",
    "wall_time_rata2_ms",
    "berhasil",
    "gagal",
    "success_rate",
]


def _post_json(url: str, payload: dict, timeout: int) -> tuple[int | None, dict | None, str | None]:
    body = json.dumps(payload).encode("utf-8")
    request = urllib.request.Request(
        url,
        data=body,
        method="POST",
        headers={"Content-Type": "application/json", "Accept": "application/json"},
    )
    try:
        with urllib.request.urlopen(request, timeout=timeout) as response:
            text = response.read().decode("utf-8")
            return response.status, json.loads(text), None
    except urllib.error.HTTPError as exc:
        detail = exc.read().decode("utf-8", errors="ignore")[:300]
        return exc.code, None, detail or str(exc)
    except Exception as exc:
        return None, None, str(exc)


def _run_one(url: str, mode: str, scenario: str, request_no: int, timeout: int) -> dict:
    payload = dict(DEFAULT_PAYLOAD)
    payload["mode"] = mode

    started = time.perf_counter()
    http_status, response, request_error = _post_json(url, payload, timeout)
    wall_time_ms = int((time.perf_counter() - started) * 1000)

    data = (response or {}).get("data") or {}
    meta = data.get("experiment_metadata") or {}
    service_error = meta.get("error")
    response_status = (response or {}).get("status")
    success = bool(http_status == 200 and response_status == "success" and not service_error and not request_error)

    return {
        "run_at": datetime.now().isoformat(timespec="seconds"),
        "scenario": scenario,
        "request_no": request_no,
        "mode": mode,
        "http_status": http_status or "",
        "success": "true" if success else "false",
        "latency_ms": meta.get("latency_ms") if meta.get("latency_ms") is not None else "",
        "wall_time_ms": wall_time_ms,
        "model_name": meta.get("model_name") or "",
        "error_message": service_error or request_error or (response or {}).get("message") or "",
    }


def _write_csv(path: str, rows: list[dict], fields: list[str]) -> None:
    with open(path, "w", encoding="utf-8", newline="") as file:
        writer = csv.DictWriter(file, fieldnames=fields)
        writer.writeheader()
        writer.writerows(rows)


def _summarize(rows: list[dict]) -> list[dict]:
    grouped: dict[tuple[str, str], list[dict]] = {}
    for row in rows:
        grouped.setdefault((row["scenario"], row["mode"]), []).append(row)

    summary = []
    for (scenario, mode), items in grouped.items():
        successes = [item for item in items if item["success"] == "true"]
        failed = len(items) - len(successes)
        latency_values = [
            int(item["latency_ms"])
            for item in items
            if str(item.get("latency_ms") or "").isdigit()
        ]
        wall_values = [
            int(item["wall_time_ms"])
            for item in items
            if str(item.get("wall_time_ms") or "").isdigit()
        ]
        summary.append({
            "scenario": scenario,
            "jumlah_request": len(items),
            "mode": mode,
            "latency_rata2_ms": round(mean(latency_values), 2) if latency_values else "",
            "wall_time_rata2_ms": round(mean(wall_values), 2) if wall_values else "",
            "berhasil": len(successes),
            "gagal": failed,
            "success_rate": f"{(len(successes) / len(items) * 100):.1f}%" if items else "0.0%",
        })
    return sorted(summary, key=lambda r: (r["scenario"], r["mode"]))


def parse_counts(value: str) -> list[int]:
    counts = []
    for part in value.split(","):
        count = int(part.strip())
        if count <= 0:
            raise argparse.ArgumentTypeError("Jumlah request harus lebih dari 0.")
        counts.append(count)
    return counts


def main() -> None:
    parser = argparse.ArgumentParser(description="Uji kinerja endpoint rekomendasi PKM.")
    parser.add_argument("--url", default=DEFAULT_URL, help="URL endpoint /api/experiment/recommend.")
    parser.add_argument("--counts", type=parse_counts, default=[3, 5, 10], help="Daftar jumlah request, contoh: 3,5,10.")
    parser.add_argument("--modes", default="retrieval_based,full_llm", help="Mode yang diuji, contoh: retrieval_based,full_llm.")
    parser.add_argument("--delay", type=float, default=2.0, help="Jeda antar request dalam detik.")
    parser.add_argument("--timeout", type=int, default=180, help="Timeout request dalam detik.")
    args = parser.parse_args()

    modes = [mode.strip() for mode in args.modes.split(",") if mode.strip()]
    rows = []

    print(f"Endpoint: {args.url}")
    print(f"Counts  : {args.counts}")
    print(f"Modes   : {modes}")
    print(f"Delay   : {args.delay} detik")

    for count in args.counts:
        scenario = f"{count} request"
        for mode in modes:
            for request_no in range(1, count + 1):
                row = _run_one(args.url, mode, scenario, request_no, args.timeout)
                rows.append(row)
                status = "OK" if row["success"] == "true" else "GAGAL"
                print(
                    f"[{scenario}] {mode} #{request_no}: {status}, "
                    f"latency={row['latency_ms'] or '-'} ms, error={row['error_message'] or '-'}"
                )
                time.sleep(args.delay)

    raw_path = os.path.join(EVAL_DIR, "performance_raw.csv")
    summary_path = os.path.join(EVAL_DIR, "performance_summary.csv")
    _write_csv(raw_path, rows, RAW_FIELDS)
    _write_csv(summary_path, _summarize(rows), SUMMARY_FIELDS)

    print(f"\nRaw     : {raw_path}")
    print(f"Summary : {summary_path}")


if __name__ == "__main__":
    main()
