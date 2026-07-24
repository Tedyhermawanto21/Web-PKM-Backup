"""
LLM Client tunggal untuk eksperimen Pendekatan B (Full LLM).

Prinsip eksperimen:
- SATU provider & SATU model, dibaca dari environment:
  FULL_LLM_PROVIDER (groq|gemini), FULL_LLM_MODEL, FULL_LLM_API_KEY.
- TANPA auto-failover. Bila model utama gagal/limit, error dilempar apa adanya
  agar seluruh eksperimen memakai model yang sama (bisa dicatat ke log).
- Mengembalikan teks jawaban + penggunaan token (bila provider menyediakannya).
- API key TIDAK PERNAH dicetak ke log/terminal maupun dikembalikan ke pemanggil.
"""
import json
import socket
import urllib.error
import urllib.request

from core.config import settings


class LLMError(Exception):
    """Kesalahan umum pemanggilan LLM."""


class LLMConfigError(LLMError):
    """API key kosong / model tidak dikonfigurasi / provider tak dikenal."""


class LLMRateLimit(LLMError):
    """Provider mengembalikan status 429 / limit."""


class LLMTimeout(LLMError):
    """Permintaan melebihi batas waktu."""


class LLMResponseError(LLMError):
    """Koneksi gagal atau respons provider tidak dapat diproses."""


def _provider() -> str:
    return (settings.FULL_LLM_PROVIDER or "groq").strip().lower()


def _api_key() -> str:
    key = (settings.FULL_LLM_API_KEY or "").strip()
    if key:
        return key
    # Fallback ke kunci provider spesifik bila FULL_LLM_API_KEY belum diisi.
    prov = _provider()
    if prov == "groq":
        return (settings.GROQ_API_KEY or "").strip()
    if prov == "gemini":
        return (getattr(settings, "GEMINI_API_KEY", "") or "").strip()
    return ""


def _model() -> str:
    model = (settings.FULL_LLM_MODEL or "").strip()
    if model:
        return model
    prov = _provider()
    if prov == "groq":
        return (settings.GROQ_PRIMARY_MODEL or "").strip()
    if prov == "gemini":
        return (getattr(settings, "GEMINI_MODEL", "") or "").strip()
    return ""


def _http_post(url: str, payload: dict, headers: dict, timeout: int) -> dict:
    data = json.dumps(payload).encode("utf-8")
    request = urllib.request.Request(url, data=data, method="POST", headers=headers)
    try:
        with urllib.request.urlopen(request, timeout=timeout) as response:
            return json.loads(response.read().decode("utf-8"))
    except urllib.error.HTTPError as exc:
        # Jangan cetak body mentah yang mungkin memuat detail sensitif berlebihan
        if exc.code == 429:
            raise LLMRateLimit(f"Provider limit (HTTP 429).") from exc
        detail = exc.read().decode("utf-8", errors="ignore")[:200]
        raise LLMResponseError(f"HTTP {exc.code}: {detail}") from exc
    except socket.timeout as exc:
        raise LLMTimeout("Permintaan ke provider LLM melebihi batas waktu.") from exc
    except urllib.error.URLError as exc:
        raise LLMResponseError(f"Koneksi ke provider gagal: {exc.reason}") from exc


def _usage_zero() -> dict:
    return {"input_tokens": 0, "output_tokens": 0, "total_tokens": 0}


def chat(system_prompt: str, user_prompt: str, timeout: int = 60) -> tuple[str, dict, str]:
    """Kirim satu permintaan ke provider tunggal.

    Return: (content_text, usage_dict, model_name)
    Raise: LLMConfigError | LLMRateLimit | LLMTimeout | LLMResponseError
    """
    provider = _provider()
    key = _api_key()
    model = _model()

    if not key:
        raise LLMConfigError("FULL_LLM_API_KEY kosong. Set kunci provider di .env.")
    if not model:
        raise LLMConfigError("FULL_LLM_MODEL kosong. Set nama model di .env.")

    if provider == "groq":
        return _chat_groq(system_prompt, user_prompt, model, key, timeout)
    if provider == "gemini":
        return _chat_gemini(system_prompt, user_prompt, model, key, timeout)
    raise LLMConfigError(f"Provider LLM tidak dikenal: {provider}")


def _chat_groq(system_prompt, user_prompt, model, key, timeout):
    url = settings.GROQ_API_URL
    payload = {
        "model": model,
        "messages": [
            {"role": "system", "content": system_prompt},
            {"role": "user", "content": user_prompt},
        ],
        "temperature": 0.4,
        "max_tokens": 2500,
        "response_format": {"type": "json_object"},
    }
    headers = {
        "Authorization": f"Bearer {key}",
        "Content-Type": "application/json",
    }
    body = _http_post(url, payload, headers, timeout)
    try:
        content = body["choices"][0]["message"]["content"]
    except (KeyError, IndexError) as exc:
        raise LLMResponseError("Struktur respons Groq tidak sesuai.") from exc

    u = body.get("usage") or {}
    usage = {
        "input_tokens": int(u.get("prompt_tokens", 0) or 0),
        "output_tokens": int(u.get("completion_tokens", 0) or 0),
        "total_tokens": int(u.get("total_tokens", 0) or 0),
    }
    return content, usage, model


def _chat_gemini(system_prompt, user_prompt, model, key, timeout):
    base = getattr(settings, "GEMINI_API_URL", "https://generativelanguage.googleapis.com/v1beta/models")
    url = f"{base}/{model}:generateContent?key={key}"
    payload = {
        "contents": [{"parts": [{"text": system_prompt + "\n\n" + user_prompt}]}],
        "generationConfig": {"response_mime_type": "application/json", "temperature": 0.4},
    }
    headers = {"Content-Type": "application/json"}
    body = _http_post(url, payload, headers, timeout)
    try:
        content = body["candidates"][0]["content"]["parts"][0]["text"]
    except (KeyError, IndexError) as exc:
        raise LLMResponseError("Struktur respons Gemini tidak sesuai.") from exc

    u = body.get("usageMetadata") or {}
    usage = {
        "input_tokens": int(u.get("promptTokenCount", 0) or 0),
        "output_tokens": int(u.get("candidatesTokenCount", 0) or 0),
        "total_tokens": int(u.get("totalTokenCount", 0) or 0),
    }
    return content, usage, model
