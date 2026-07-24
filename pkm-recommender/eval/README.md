# Toolkit Evaluasi — Data Uji & Ground Truth (BAB IV)

Mendukung eksperimen komparatif **Pendekatan A (retrieval_based)** vs
**Pendekatan B (full_llm)**. Versi ringan agar beban validator kecil.

Semua perintah dijalankan dari folder `pkm-recommender`.

## Rancangan (6 kasus uji, Top-3)

Dengan 6 kasus uji, beban penilaian:
- **Rekomendasi dosen**: 6 kasus × maks 6 kandidat (gabungan Top-3 kedua metode) = **maks 36 skor** (biasanya 24–30 karena ada nama yang sama di kedua metode).
- **Kualitas judul**: 6 kasus × 2 pendekatan × 3 judul = **36 judul**, tiap judul diberi 5 nilai.
- **Keberagaman judul**: 6 kasus × 2 pendekatan = **12 kelompok**, tiap kelompok 1 nilai.

## Alur

1. **Kasus uji** — 6 contoh (6 skema PKM) di `eval/testcases.csv`.

2. **Jalankan eksperimen**:
   ```
   .venv\Scripts\python.exe eval\run_experiment.py
   ```
   Menghasilkan di `eval/`:
   - `results_raw.jsonl` — output mentah + latency.
   - `dosen_groundtruth.csv` — **hanya pool gabungan Top-3 kedua metode** (bukan 40 dosen); isi `score_v1` & `score_v2` (0–2).
   - `titles_for_scoring.csv` — judul ter-blind (J####); isi 5 aspek (1–5).
   - `diversity_for_scoring.csv` — set 3 judul ter-blind (S####); isi `keberagaman` (1–5).
   - `titles_key.csv`, `diversity_key.csv` — kunci blind. **JANGAN diberikan ke validator.**

   > Set `EXPERIMENT_MODE=true` di `.env` agar latency asli terukur.

3. **Bagikan ke validator** (2–3 dosen) hanya 3 file: `dosen_groundtruth.csv`,
   `titles_for_scoring.csv`, `diversity_for_scoring.csv`. Skala:
   - Dosen: 0 = tidak relevan, 1 = cukup relevan, 2 = sangat relevan.
   - Judul & keberagaman: 1 (sangat tidak baik) … 5 (sangat baik).

4. **Hitung metrik**:
   ```
   .venv\Scripts\python.exe eval\evaluate.py
   ```
   `eval/metrics_summary.csv` berisi per mode: `precision@3`, `recall@3(pool)`,
   `ndcg@3`, `judul_skor_ahli(1-5)`, `keberagaman_ahli(1-5)`,
   `novelty_trigram(0-1)`, `latency_ms_rata2`.

5. **Kesepakatan penilai**:
   ```
   .venv\Scripts\python.exe eval\kappa.py
   ```

## Catatan
- Ground truth bersifat **pooled** (gabungan Top-3 kedua metode) → `recall@3(pool)`
  adalah recall dalam pool penilaian, bukan recall absolut atas 40 dosen. Sebutkan hal ini di metodologi.
- `lecturer_id` konsisten dengan `data/lecturers.xlsx` dan sama untuk kedua pendekatan.
- Judul dinilai **blind** (kode acak) agar validator tidak tahu metodenya.
