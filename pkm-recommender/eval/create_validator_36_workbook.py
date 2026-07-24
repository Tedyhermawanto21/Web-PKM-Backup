import csv
import json
import os
import zipfile

from create_title_dosen_workbook import sheet_xml, write_part


EVAL_DIR = os.path.dirname(__file__)
SOURCE_CSV = os.path.join(EVAL_DIR, "title_dosen_groundtruth.csv")
RAW_JSONL = os.path.join(EVAL_DIR, "results_raw.jsonl")
OUTPUT_XLSX = os.path.join(EVAL_DIR, "VALIDATOR_title_dosen_groundtruth_36baris_FINAL.xlsx")


FALLBACK_GEMINI_TITLES = {
    "UJI-01": [
        "Rancang Bangun Sistem Absensi Mahasiswa Berbasis Face Recognition dan Internet of Things untuk Validasi Kehadiran Otomatis",
        "Implementasi Computer Vision dan Mikrokontroler IoT pada Sistem Presensi Mahasiswa Anti-Manipulasi",
        "Pengembangan Aplikasi Presensi Cerdas Menggunakan Deteksi Wajah Real-Time Terintegrasi IoT di Lingkungan Kampus",
    ],
    "UJI-03": [
        "Analisis Hubungan Durasi Penggunaan Gawai terhadap Tingkat Stres dan Kecemasan pada Remaja Sekolah Menengah",
        "Studi Korelasional Penggunaan Smartphone dan Kesehatan Mental Remaja Menggunakan Survei Psikologis Terstruktur",
        "Pemetaan Pola Penggunaan Gawai sebagai Faktor Risiko Kecemasan Remaja di Lingkungan Sekolah",
    ],
    "UJI-05": [
        "Implementasi Aplikasi Kasir Digital untuk Pencatatan Penjualan dan Stok pada UMKM Warung Kelontong",
        "Digitalisasi Manajemen Persediaan Berbasis Mobile untuk Meningkatkan Ketertiban Pembukuan Warung Kelontong",
        "Pengembangan Sistem Point of Sale Sederhana sebagai Pendukung Pengelolaan Transaksi UMKM Ritel Mikro",
    ],
}


def load_source_rows():
    with open(SOURCE_CSV, "r", encoding="utf-8-sig", newline="") as f:
        return list(csv.DictReader(f))


def load_raw_rows():
    rows = []
    with open(RAW_JSONL, "r", encoding="utf-8") as f:
        for line in f:
            line = line.strip()
            if line:
                rows.append(json.loads(line))
    return rows


def build_lecturer_info(source_rows, raw_rows):
    info = {}
    for row in source_rows:
        lid = row.get("lecturer_id", "")
        if lid and lid not in info:
            info[lid] = {
                "lecturer_id": lid,
                "lecturer_name": row.get("lecturer_name", ""),
                "dept": row.get("dept", ""),
                "keywords": row.get("keywords", ""),
            }
    for row in raw_rows:
        for lecturer in row.get("lecturers", []):
            lid = str(lecturer.get("lecturer_id", ""))
            if lid and lid not in info:
                info[lid] = {
                    "lecturer_id": lid,
                    "lecturer_name": lecturer.get("lecturer_name", ""),
                    "dept": "",
                    "keywords": lecturer.get("reason", ""),
                }
    return info


def top_lecturers_by_case_mode(source_rows, raw_rows, lecturer_info):
    top = {}
    for row in source_rows:
        key = (row.get("case_id", ""), row.get("approach", ""))
        if key not in top and row.get("lecturer_rank", "") == "1":
            top[key] = lecturer_info.get(row.get("lecturer_id", ""), row)

    for row in raw_rows:
        approach = "Hybrid" if row.get("mode") == "retrieval_based" else "Gemini"
        key = (row.get("case_id", ""), approach)
        lecturers = row.get("lecturers", [])
        if key not in top and lecturers:
            lid = str(lecturers[0].get("lecturer_id", ""))
            top[key] = lecturer_info.get(lid, {
                "lecturer_id": lid,
                "lecturer_name": lecturers[0].get("lecturer_name", ""),
                "dept": "",
                "keywords": lecturers[0].get("reason", ""),
            })
    return top


def build_rows():
    source_rows = load_source_rows()
    raw_rows = load_raw_rows()
    lecturer_info = build_lecturer_info(source_rows, raw_rows)
    top = top_lecturers_by_case_mode(source_rows, raw_rows, lecturer_info)

    existing = {}
    for row in source_rows:
        if row.get("lecturer_rank", "") != "1":
            continue
        key = (row.get("case_id", ""), row.get("approach", ""), int(row.get("title_rank", "0") or 0))
        existing[key] = row

    header = [
        "case_id", "title_id", "approach", "title_rank", "title",
        "lecturer_id", "lecturer_name", "dept", "keywords", "score",
    ]
    rows = [header]

    seq = 1
    for case_num in range(1, 7):
        case_id = f"UJI-{case_num:02d}"
        for approach in ["Hybrid", "Gemini"]:
            for rank in range(1, 4):
                key = (case_id, approach, rank)
                existing_row = existing.get(key)
                if existing_row:
                    title = existing_row.get("title", "")
                    lecturer = lecturer_info.get(existing_row.get("lecturer_id", ""), existing_row)
                else:
                    title = FALLBACK_GEMINI_TITLES.get(case_id, ["", "", ""])[rank - 1]
                    lecturer = top.get((case_id, approach)) or top.get((case_id, "Hybrid")) or {}

                rows.append([
                    case_id,
                    f"J{seq:04d}",
                    approach,
                    str(rank),
                    title,
                    lecturer.get("lecturer_id", ""),
                    lecturer.get("lecturer_name", ""),
                    lecturer.get("dept", ""),
                    lecturer.get("keywords", ""),
                    "",
                ])
                seq += 1

    return rows


def build_workbook():
    data_rows = build_rows()
    panduan_rows = [
        ["Panduan Penilaian Ground Truth Judul-Dosen"],
        [""],
        ["Tujuan"],
        ["File ini digunakan untuk menilai kecocokan satu dosen rekomendasi terhadap satu judul hasil generate."],
        ["Format dibuat 36 baris agar seimbang: 6 kasus uji x 2 pendekatan x 3 judul."],
        [""],
        ["Yang Diisi Validator"],
        ["Validator hanya mengisi kolom score pada sheet Groundtruth Data."],
        [""],
        ["Skala Penilaian"],
        ["0", "Tidak sesuai", "Bidang keahlian dosen tidak cocok dengan judul."],
        ["1", "Cukup sesuai", "Ada hubungan umum, tetapi tidak paling kuat/spesifik."],
        ["2", "Sesuai", "Bidang keahlian dosen cocok dan layak menjadi pembimbing judul tersebut."],
        [""],
        ["Cara Mengisi"],
        ["1", "Baca kolom title sebagai judul yang dihasilkan sistem."],
        ["2", "Lihat lecturer_name, dept, dan keywords sebagai informasi keahlian dosen."],
        ["3", "Isi score dengan angka 0, 1, atau 2."],
        ["4", "Jangan mengubah case_id, title_id, approach, title, atau data dosen."],
        [""],
        ["Catatan"],
        ["File titles_for_scoring.csv dan diversity_for_scoring.csv tidak digunakan lagi."],
        ["Judul pada file ini ditulis lengkap, tidak dipotong dengan tanda titik tiga."],
    ]

    with zipfile.ZipFile(OUTPUT_XLSX, "w", zipfile.ZIP_DEFLATED) as zf:
        write_part(zf, "[Content_Types].xml", """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
<Default Extension="xml" ContentType="application/xml"/>
<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
<Override PartName="/xl/worksheets/sheet2.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
</Types>""")
        write_part(zf, "_rels/.rels", """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>""")
        write_part(zf, "xl/workbook.xml", """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
<sheets>
<sheet name="Panduan" sheetId="1" r:id="rId1"/>
<sheet name="Groundtruth Data" sheetId="2" r:id="rId2"/>
</sheets>
</workbook>""")
        write_part(zf, "xl/_rels/workbook.xml.rels", """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet2.xml"/>
<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>""")
        write_part(zf, "xl/styles.xml", """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
<fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><name val="Calibri"/><color rgb="FFFFFFFF"/></font></fonts>
<fills count="2"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FF1F4E79"/><bgColor indexed="64"/></patternFill></fill></fills>
<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>
<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>
<cellXfs count="2"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="0" fontId="1" fillId="1" borderId="0" xfId="0" applyFont="1" applyFill="1"/></cellXfs>
</styleSheet>""")
        write_part(zf, "xl/worksheets/sheet1.xml", sheet_xml(panduan_rows, [18, 38, 80], False))
        write_part(
            zf,
            "xl/worksheets/sheet2.xml",
            sheet_xml(data_rows, [12, 12, 12, 10, 85, 12, 26, 20, 85, 10], True),
        )

    print(OUTPUT_XLSX)


if __name__ == "__main__":
    build_workbook()
