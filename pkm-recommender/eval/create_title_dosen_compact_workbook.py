import csv
import os
import zipfile
from collections import OrderedDict
from xml.sax.saxutils import escape


EVAL_DIR = os.path.dirname(__file__)
SOURCE_CSV = os.path.join(EVAL_DIR, "title_dosen_groundtruth.csv")
COMPACT_CSV = os.path.join(EVAL_DIR, "title_dosen_groundtruth_compact.csv")
COMPACT_XLSX = os.path.join(EVAL_DIR, "VALIDATOR_title_dosen_groundtruth_27baris_FINAL.xlsx")


def cell_ref(row_idx, col_idx):
    name = ""
    while col_idx:
        col_idx, rem = divmod(col_idx - 1, 26)
        name = chr(65 + rem) + name
    return f"{name}{row_idx}"


def row_xml(row_idx, values, style_by_col=None):
    style_by_col = style_by_col or {}
    cells = []
    for col_idx, value in enumerate(values, start=1):
        value = "" if value is None else str(value)
        style = style_by_col.get(col_idx, "")
        style_attr = f' s="{style}"' if style != "" else ""
        cells.append(
            f'<c r="{cell_ref(row_idx, col_idx)}" t="inlineStr"{style_attr}>'
            f"<is><t>{escape(value)}</t></is></c>"
        )
    return f'<row r="{row_idx}">{"".join(cells)}</row>'


def sheet_xml(rows, widths=None, frozen=False):
    last_row = max(len(rows), 1)
    last_col = max((len(r) for r in rows), default=1)
    dimension = f"A1:{cell_ref(last_row, last_col)}"
    cols = ""
    if widths:
        cols = "<cols>" + "".join(
            f'<col min="{i}" max="{i}" width="{w}" customWidth="1"/>'
            for i, w in enumerate(widths, start=1)
        ) + "</cols>"
    views = ""
    if frozen:
        views = (
            '<sheetViews><sheetView workbookViewId="0">'
            '<pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/>'
            "</sheetView></sheetViews>"
        )
    body = []
    for idx, row in enumerate(rows, start=1):
        if idx == 1:
            body.append(row_xml(idx, row, {i: 1 for i in range(1, len(row) + 1)}))
        else:
            body.append(row_xml(idx, row))
    return (
        '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
        '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
        'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
        f'<dimension ref="{dimension}"/>'
        f"{views}{cols}"
        f'<sheetData>{"".join(body)}</sheetData>'
        "</worksheet>"
    )


def write_part(zf, name, content):
    zf.writestr(name, content.encode("utf-8"))


def build_compact_rows():
    with open(SOURCE_CSV, "r", encoding="utf-8-sig", newline="") as f:
        source_rows = list(csv.DictReader(f))

    grouped = OrderedDict()
    for row in source_rows:
        key = (
            row["case_id"], row["title_id"], row["mode"], row["approach"],
            row["title_rank"], row["title"],
        )
        grouped.setdefault(key, []).append(row)

    header = [
        "case_id", "title_id", "mode", "approach", "title_rank", "title",
        "rekomendasi_dosen_top3",
        "skor_dosen_1", "skor_dosen_2", "skor_dosen_3",
    ]
    compact = [header]

    for key, rows in grouped.items():
        sorted_rows = sorted(rows, key=lambda r: int(r.get("lecturer_rank") or 0))
        out = list(key)
        candidates = []
        for idx in range(3):
            row = sorted_rows[idx] if idx < len(sorted_rows) else {}
            if row:
                candidates.append(
                    f"{idx + 1}. ID {row.get('lecturer_id', '')} - {row.get('lecturer_name', '')} | "
                    f"Prodi: {row.get('dept', '')} | "
                    f"Keahlian: {row.get('keywords', '')}"
                )
        out.append("\n".join(candidates))
        for idx in range(3):
            row = sorted_rows[idx] if idx < len(sorted_rows) else {}
            out.append(row.get("score_v1", ""))
        compact.append(out)

    return compact


def write_compact_csv(rows):
    with open(COMPACT_CSV, "w", encoding="utf-8", newline="") as f:
        writer = csv.writer(f)
        writer.writerows(rows)


def write_compact_xlsx(rows):
    panduan_rows = [
        ["Panduan Penilaian Ground Truth Judul-Dosen Versi Ringkas"],
        [""],
        ["Format Ringkas"],
        ["Satu baris mewakili satu judul hasil generate."],
        ["Tiga kandidat dosen ditampilkan dalam satu kolom rekomendasi_dosen_top3."],
        ["Dengan format ini, 27 judul menjadi 27 baris, bukan 81 baris."],
        [""],
        ["Yang Diisi Validator"],
        ["Isi skor_dosen_1, skor_dosen_2, dan skor_dosen_3."],
        ["skor_dosen_1 adalah skor untuk dosen nomor 1 pada kolom rekomendasi_dosen_top3."],
        ["skor_dosen_2 adalah skor untuk dosen nomor 2 pada kolom rekomendasi_dosen_top3."],
        ["skor_dosen_3 adalah skor untuk dosen nomor 3 pada kolom rekomendasi_dosen_top3."],
        ["File ini dibuat untuk satu validator, jadi tidak ada kolom score_v2."],
        [""],
        ["Skala Penilaian"],
        ["0", "Tidak sesuai", "Bidang keahlian dosen tidak cocok dengan judul."],
        ["1", "Cukup sesuai", "Ada hubungan umum, tetapi tidak paling kuat/spesifik."],
        ["2", "Sesuai", "Bidang keahlian dosen cocok dan layak menjadi pembimbing judul tersebut."],
        [""],
        ["Cara Menilai"],
        ["1", "Baca kolom title."],
        ["2", "Lihat tiga kandidat pada kolom rekomendasi_dosen_top3."],
        ["3", "Beri skor 0, 1, atau 2 untuk masing-masing dosen."],
        ["4", "Jangan mengubah kolom case_id, title_id, mode, approach, title, atau data dosen."],
        [""],
        ["Catatan"],
        ["File ini menggantikan title scoring dan diversity scoring."],
        ["Metrik akhir yang dihitung adalah Precision@3 dan nDCG@3."],
    ]

    with zipfile.ZipFile(COMPACT_XLSX, "w", zipfile.ZIP_DEFLATED) as zf:
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
        write_part(zf, "xl/worksheets/sheet1.xml", sheet_xml(panduan_rows, [18, 38, 70], False))
        widths = [10, 10, 16, 12, 10, 60, 90, 12, 12, 12]
        write_part(zf, "xl/worksheets/sheet2.xml", sheet_xml(rows, widths, True))


def main():
    rows = build_compact_rows()
    write_compact_csv(rows)
    write_compact_xlsx(rows)
    print(f"Tulis: {COMPACT_CSV} ({len(rows) - 1} baris data)")
    print(f"Tulis: {COMPACT_XLSX}")


if __name__ == "__main__":
    main()
