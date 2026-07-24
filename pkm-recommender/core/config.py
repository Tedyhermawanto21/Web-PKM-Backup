import os
from pydantic_settings import BaseSettings


def _load_env_file():
    """Load .env file, giving PRIORITY to .env values over OS environment variables.

    This uses os.environ[key] = value (not setdefault) so that keys defined in
    .env always win — even if a stale key exists in the Windows/OS environment.
    Only Groq-related and app-specific keys are force-overridden to avoid
    accidentally clobbering unrelated OS vars like PATH.
    """
    FORCE_OVERRIDE_PREFIXES = (
        "GROQ_",
        "GEMINI_",
        "FULL_LLM",
        "EXPERIMENT_MODE",
        "PKM_",
        "APP_NAME",
        "APP_VERSION",
        "SENTENCE_BERT",
    )

    root_dir = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', '..'))
    for path in (
        os.path.join(root_dir, '.env'),
        os.path.join(os.path.dirname(__file__), '..', '.env'),
    ):
        if not os.path.exists(path):
            continue

        with open(path, 'r', encoding='utf-8') as env_file:
            for line in env_file:
                line = line.strip()
                if not line or line.startswith('#') or '=' not in line:
                    continue
                key, value = line.split('=', 1)
                key = key.strip()
                value = value.strip().strip('"').strip("'")
                # Force-override known app keys; use setdefault for the rest
                if any(key.startswith(p) for p in FORCE_OVERRIDE_PREFIXES):
                    os.environ[key] = value
                else:
                    os.environ.setdefault(key, value)
        # Stop after the first .env file found (root .env takes precedence)
        break


_load_env_file()


class Settings(BaseSettings):
    APP_NAME: str = "PKM AI Recommender API"
    APP_VERSION: str = "1.0.0"
    
    # Model Configuration
    SENTENCE_BERT_MODEL: str = "paraphrase-multilingual-MiniLM-L12-v2"
    
    # Data Paths
    DATA_DIR: str = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', '..', 'data'))
    LECTURERS_FILE: str = os.path.join(DATA_DIR, "lecturers.xlsx")
    WINNERS_FILE: str = os.path.join(DATA_DIR, "pkm_winners_2022_2025_titles.csv")
    FAISS_INDEX_FILE: str = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', 'data', 'dosen_index.faiss'))
    WINNERS_INDEX_FILE: str = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', 'data', 'winners_index.faiss'))

    # Groq Cloud
    GROQ_API_KEY: str = ""
    GROQ_API_URL: str = "https://api.groq.com/openai/v1/chat/completions"
    GROQ_PRIMARY_MODEL: str = "llama-3.3-70b-versatile"
    GROQ_FALLBACK_MODEL: str = "compound-beta"
    GROQ_TIMEOUT_SECONDS: int = 45

    # Google Gemini (provider cadangan untuk Pendekatan B / Full LLM)
    GEMINI_API_KEY: str = ""
    GEMINI_MODEL: str = "gemini-2.0-flash"
    GEMINI_API_URL: str = "https://generativelanguage.googleapis.com/v1beta/models"

    # Pendekatan B (Full LLM) — parameter skalabilitas
    FULL_LLM_MAX_CONCURRENCY: int = 4
    FULL_LLM_QUEUE_TIMEOUT: int = 60
    FULL_LLM_CACHE_ENABLED: bool = True

    # ── Eksperimen komparatif (Pendekatan A vs B) ──────────────────────────
    # Provider & model TUNGGAL yang dipakai selama eksperimen. Dibaca dari .env.
    # Tidak ada auto-failover: bila model utama gagal, error dilaporkan apa adanya.
    FULL_LLM_PROVIDER: str = "groq"          # "groq" | "gemini"
    FULL_LLM_MODEL: str = ""                  # mis. llama-3.3-70b-versatile / gemini-2.0-flash
    FULL_LLM_API_KEY: str = ""                # kunci provider eksperimen (rahasia)
    # Bila true: cache dinonaktifkan agar latency asli terukur.
    EXPERIMENT_MODE: bool = False

    class Config:
        case_sensitive = True

settings = Settings()
