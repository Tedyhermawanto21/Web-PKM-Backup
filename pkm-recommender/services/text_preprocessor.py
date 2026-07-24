import re
import unicodedata


ID_STOPWORDS = {
    "yang", "dan", "di", "ke", "dari", "untuk", "dengan", "pada", "dalam",
    "adalah", "ini", "itu", "atau", "juga", "serta", "karena", "oleh",
    "sebagai", "agar", "supaya", "sehingga", "maka", "jika", "kalau",
    "namun", "tetapi", "tapi", "sedangkan", "meskipun", "walaupun",
    "bagi", "terhadap", "tentang", "antara", "melalui", "secara",
    "sebuah", "suatu", "setiap", "semua", "berbagai", "beberapa",
    "saya", "kami", "kita", "mereka", "dia", "ia", "anda", "kamu",
    "sini", "sana", "situ", "tersebut", "merupakan", "dapat", "bisa",
    "akan", "telah", "sudah", "belum", "tidak", "tak", "bukan", "ada",
    "perlu", "harus", "mampu", "membuat", "melakukan", "menggunakan",
    "ingin", "mengembangkan", "mengembankan", "pengembangan",
    "sangat", "lebih", "paling", "hanya", "saja", "pun", "bahkan",
    "masih", "lagi", "kembali", "kemudian", "lalu", "setelah", "sebelum",
    "ketika", "saat", "selama", "hingga", "sampai", "dengan", "terhadap",
}


def preprocess_text(text: str) -> str:
    """Normalize Indonesian proposal text before embedding/retrieval."""
    normalized = unicodedata.normalize("NFKC", text or "").lower()
    normalized = re.sub(r"[^0-9a-zA-Z\u00C0-\u024F\s-]", " ", normalized)
    normalized = re.sub(r"[-_/]", " ", normalized)
    normalized = re.sub(r"\s+", " ", normalized).strip()

    tokens = [
        token
        for token in normalized.split()
        if token not in ID_STOPWORDS and len(token) > 1
    ]

    return " ".join(tokens)
