import csv
import json
import os
import zipfile

from create_title_dosen_workbook import sheet_xml, write_part
from create_validator_36_workbook import FALLBACK_GEMINI_TITLES


EVAL_DIR = os.path.dirname(__file__)
SOURCE_CSV = os.path.join(EVAL_DIR, "title_dosen_groundtruth.csv")
RAW_JSONL = os.path.join(EVAL_DIR, "results_raw.jsonl")
OUTPUT_XLSX = os.path.join(EVAL_DIR, "VALIDATOR_title_dosen_groundtruth_36baris_TOP3.xlsx")


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


def lecturer_text(row):
    if not row:
        return ""
    name = row.get("lecturer_name", "")
    dept = row.get("dept", "")
    keywords = row.get("keywords", "")
    if keywords:
        return f"{name} | {dept} | {keywords}"
    return f"{name} | {dept}"


def lecturer_text_from_raw(row):
    if not row:
        return ""
    name = row.get("lecturer_name", "")
    reason = row.get("reason", "")
    return f"{name} | {reason}" if reason else name


def build_existing_groups(source_rows):
    groups = {}
    for row in source_rows:
        key = (
            row.get("case_id", ""),
            row.get("approach", ""),
            row.get("title_rank", ""),
        )
        groups.setdefault(key, {
            "title": row.get("title", ""),
            "lecturers": [],
        })
        groups[key]["lecturers"].append(row)

    for value in groups.values():
        value["lecturers"] = sorted(
            value["lecturers"],
            key=lambda r: int(r.get("lecturer_rank") or 0),
        )[:3]
    return groups


def build_fallback_lecturers(source_rows, raw_rows):
    fallback = {}
    for row in source_rows:
        key = (row.get("case_id", ""), row.get("approach", ""))
        fallback.setdefault(key, []).append(row)

    for key, rows in list(fallback.items()):
        seen = set()
        unique = []
        for row in sorted(rows, key=lambda r: int(r.get("lecturer_rank") or 0)):
            lid = row.get("lecturer_id", "")
            if lid and lid not in seen:
                unique.append(row)
                seen.add(lid)
            if len(unique) == 3:
                break
        fallback[key] = unique

    for raw in raw_rows:
        approach = "Hybrid" if raw.get("mode") == "retrieval_based" else "Gemini"
        key = (raw.get("case_id", ""), approach)
        if key in fallback and len(fallback[key]) >= 3:
            continue
        lecturers = []
        for lecturer in raw.get("lecturers", [])[:3]:
            lecturers.append({
                "lecturer_name": lecturer.get("lecturer_name", ""),
                "dept": "",
                "keywords": lecturer.get("reason", ""),
                "lecturer_rank": lecturer.get("rank", ""),
            })
        if lecturers:
            fallback[key] = lecturers

    return fallback


def build_data_rows():
    source_rows = load_source_rows()
    raw_rows = load_raw_rows()
    existing = build_existing_groups(source_rows)
    fallback = build_fallback_lecturers(source_rows, raw_rows)

    rows = [[
        "case_id", "title_id", "approach", "title",
        "dosen_1", "skor_1", "dosen_2", "skor_2", "dosen_3", "skor_3",
    ]]

    seq = 1
    for case_num in range(1, 7):
        case_id = f"UJI-{case_num:02d}"
        for approach in ["Hybrid", "Gemini"]:
            for rank in range(1, 4):
                key = (case_id, approach, str(rank))
                group = existing.get(key)
                if group:
                    title = group["title"]
                    lecturers = group["lecturers"]
                else:
                    title = FALLBACK_GEMINI_TITLES.get(case_id, ["", "", ""])[rank - 1]
                    lecturers = (
                        fallback.get((case_id, approach))
                        or fallback.get((case_id, "Hybrid"))
                        or []
                    )

                out = [case_id, f"J{seq:04d}", approach, title]
                for idx in range(3):
                    lecturer = lecturers[idx] if idx < len(lecturers) else {}
                    out.extend([lecturer_text(lecturer), ""])
                rows.append(out)
                seq += 1

    return rows


def build_workbook():
    data_rows = build_data_rows()
    panduan_rows = [
        ["Panduan Penilaian Ground Truth Judul-Dosen"],
        [""],
        ["Format"],
        ["File ini berisi 36 baris: 6 kasus uji x 2 pendekatan x 3 judul."],
        ["Setiap baris berisi satu judul dan tiga kandidat dosen."],
        [""],
        ["Yang Diisi Validator"],
        ["Isi kolom skor_1, skor_2, dan skor_3."],
        ["skor_1 untuk dosen_1, skor_2 untuk dosen_2, dan skor_3 untuk dosen_3."],
        [""],
        ["Skala Penilaian"],
        ["0", "Tidak sesuai", "Bidang keahlian dosen tidak cocok dengan judul."],
        ["1", "Cukup sesuai", "Ada hubungan umum, tetapi tidak paling kuat/spesifik."],
        ["2", "Sesuai", "Bidang keahlian dosen cocok dan layak menjadi pembimbing judul tersebut."],
        [""],
        ["Catatan"],
        ["Judul ditulis penuh dan tidak dipotong dengan tanda titik tiga."],
        ["File titles_for_scoring.csv dan diversity_for_scoring.csv tidak digunakan lagi."],
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
            sheet_xml(data_rows, [12, 12, 12, 85, 55, 10, 55, 10, 55, 10], True),
        )

    print(OUTPUT_XLSX)


if __name__ == "__main__":
    build_workbook()
