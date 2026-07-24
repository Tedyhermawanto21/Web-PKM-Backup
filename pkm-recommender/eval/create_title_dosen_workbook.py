import csv
import os
import zipfile
from xml.sax.saxutils import escape


EVAL_DIR = os.path.dirname(__file__)
CSV_PATH = os.path.join(EVAL_DIR, "title_dosen_groundtruth.csv")
XLSX_PATH = os.path.join(EVAL_DIR, "VALIDATOR_title_dosen_groundtruth_81baris.xlsx")


def cell_ref(row_idx, col_idx):
    name = ""
    while col_idx:
        col_idx, rem = divmod(col_idx - 1, 26)
        name = chr(65 + rem) + name
    return f"{name}{row_idx}"


def row_xml(row_idx, values, style_by_col=None):
    cells = []
    style_by_col = style_by_col or {}
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
    dimension = f"A1:{cell_ref(max(len(rows), 1), max((len(r) for r in rows), default=1))}"
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


def build_workbook():
    data_rows = [[
        "case_id", "title_id", "approach", "title_rank", "title",
        "lecturer_rank", "lecturer_id", "lecturer_name", "dept", "keywords",
        "score_v1",
    ]]
    with open(CSV_PATH, "r", encoding="utf-8-sig", newline="") as f:
        for row in csv.DictReader(f):
            data_rows.append([
                row.get("case_id", ""),
                row.get("title_id", ""),
                row.get("approach", ""),
                row.get("title_rank", ""),
                row.get("title", ""),
                row.get("lecturer_rank", ""),
                row.get("lecturer_id", ""),
                row.get("lecturer_name", ""),
                row.get("dept", ""),
                row.get("keywords", ""),
                "",
            ])

    panduan_rows = [
        ["Panduan Penilaian Ground Truth Judul-Dosen"],
        [""],
        ["Tujuan"],
        ["File ini digunakan untuk menilai kecocokan dosen rekomendasi terhadap judul hasil generate sistem."],
        ["Penilaian ini menggantikan title scoring, novelty, dan diversity scoring."],
        [""],
        ["Yang Diisi Validator"],
        ["Validator hanya mengisi kolom score_v1 pada sheet Groundtruth Data."],
        ["Satu baris mewakili satu pasangan judul hasil generate dengan satu kandidat dosen."],
        [""],
        ["Skala Penilaian"],
        ["0", "Tidak sesuai", "Bidang keahlian dosen tidak cocok dengan judul."],
        ["1", "Cukup sesuai", "Ada hubungan umum, tetapi tidak paling kuat/spesifik."],
        ["2", "Sesuai", "Bidang keahlian dosen cocok dan layak menjadi pembimbing judul tersebut."],
        [""],
        ["Cara Mengisi"],
        ["1", "Buka sheet Groundtruth Data."],
        ["2", "Baca kolom title sebagai judul yang dihasilkan sistem."],
        ["3", "Lihat lecturer_name, dept, dan keywords sebagai informasi keahlian dosen."],
        ["4", "Isi score_v1 dengan angka 0, 1, atau 2."],
        ["5", "Jangan mengubah case_id, title_id, approach, title, atau data dosen."],
        [""],
        ["Makna Kolom Penting"],
        ["case_id", "Kode kasus uji, misalnya UJI-01."],
        ["title_id", "Kode judul hasil generate."],
        ["approach", "Label pendekatan: Hybrid atau Gemini."],
        ["title", "Judul hasil generate sistem."],
        ["lecturer_rank", "Urutan rekomendasi dosen dari sistem."],
        ["keywords", "Ringkasan bidang keahlian dosen."],
        ["score_v1", "Kolom skor validator."],
        [""],
        ["Catatan Skripsi"],
        ["File titles_for_scoring.csv dan diversity_for_scoring.csv tidak digunakan lagi untuk revisi ini."],
        ["Metrik akhir yang dihitung dari file ini adalah Precision@3 dan nDCG@3."],
    ]

    with zipfile.ZipFile(XLSX_PATH, "w", zipfile.ZIP_DEFLATED) as zf:
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
        write_part(
            zf,
            "xl/worksheets/sheet2.xml",
            sheet_xml(data_rows, [12, 12, 12, 10, 60, 12, 12, 26, 20, 70, 10], True),
        )

    print(XLSX_PATH)


if __name__ == "__main__":
    build_workbook()
