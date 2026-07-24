"""
Hitung kesepakatan antar-penilai (inter-rater agreement) untuk ground truth dosen.

Membaca eval/dosen_groundtruth.csv (kolom score_v1 & score_v2 berisi 0/1/2),
lalu menghitung:
- Persentase persetujuan (exact agreement)
- Cohen's Kappa (bobot linear, karena skor bersifat ordinal 0-2)

Jalankan:
    .venv\\Scripts\\python.exe eval\\kappa.py

Interpretasi umum Kappa: <0.20 buruk, 0.21-0.40 lemah, 0.41-0.60 sedang,
0.61-0.80 baik, 0.81-1.00 sangat baik.
"""
import os

import pandas as pd

EVAL_DIR = os.path.dirname(__file__)


def interpret(k):
    if k < 0.20:
        return "buruk"
    if k < 0.40:
        return "lemah"
    if k < 0.60:
        return "sedang"
    if k < 0.80:
        return "baik"
    return "sangat baik"


def main():
    path = os.path.join(EVAL_DIR, "dosen_groundtruth.csv")
    df = pd.read_csv(path)
    df["score_v1"] = pd.to_numeric(df["score_v1"], errors="coerce")
    df["score_v2"] = pd.to_numeric(df["score_v2"], errors="coerce")
    df = df.dropna(subset=["score_v1", "score_v2"])

    if df.empty:
        print("[!] Belum ada pasangan skor v1 & v2 yang terisi di dosen_groundtruth.csv.")
        return

    v1 = df["score_v1"].astype(int)
    v2 = df["score_v2"].astype(int)

    agreement = float((v1 == v2).mean())
    print(f"Jumlah pasangan dinilai : {len(df)}")
    print(f"Persentase persetujuan  : {agreement * 100:.2f}%")

    try:
        from sklearn.metrics import cohen_kappa_score
        kappa = cohen_kappa_score(v1, v2, weights="linear")
        print(f"Cohen's Kappa (linear)  : {kappa:.3f} ({interpret(kappa)})")
    except Exception as exc:  # pragma: no cover
        print(f"Cohen's Kappa gagal dihitung: {exc}")


if __name__ == "__main__":
    main()
