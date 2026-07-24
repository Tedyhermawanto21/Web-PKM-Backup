"""Validasi ringan untuk request eksperimen (tanpa dependensi berat)."""

VALID_MODES = ("retrieval_based", "full_llm")


def validate_request(idea: str, abstract: str, mode: str):
    """Return (ok: bool, error: str|None)."""
    idea = (idea or "").strip()
    abstract = (abstract or "").strip()
    if not idea and not abstract:
        return False, "Input kosong: 'idea' atau 'abstract' wajib diisi."
    if (mode or "").strip().lower() not in VALID_MODES:
        return False, f"mode tidak valid: {mode}"
    return True, None
