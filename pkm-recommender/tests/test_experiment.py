"""
Test eksperimen komparatif (Pendekatan A vs B).

Dijalankan dari folder pkm-recommender:
    .venv\\Scripts\\python.exe -m unittest tests.test_experiment -v

Test dirancang RINGAN: tidak memuat Sentence-BERT/FAISS. Jaminan pemisahan
(retrieval tanpa LLM, full_llm tanpa SBERT/FAISS) diverifikasi secara statis
dari sumber + secara fungsional dengan me-mock LLM client.
"""
import os
import sys
import importlib.util
import unittest
from unittest import mock

BASE = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
if BASE not in sys.path:
    sys.path.insert(0, BASE)

SERVICES = os.path.join(BASE, "services")

EXPECTED_TOP_KEYS = {
    "mode", "method_label", "lecturer_recommendations",
    "title_recommendations", "experiment_metadata",
}
EXPECTED_META_KEYS = {
    "provider", "model_name", "latency_ms", "input_tokens",
    "output_tokens", "total_tokens", "error",
}


def _read(fname):
    with open(os.path.join(SERVICES, fname), "r", encoding="utf-8") as f:
        return f.read()


def _has_module(module_name):
    try:
        return importlib.util.find_spec(module_name) is not None
    except ModuleNotFoundError:
        return False


def _import_lines(fname):
    """Hanya baris import (bukan komentar/docstring), di-lowercase & digabung."""
    lines = []
    for raw in _read(fname).splitlines():
        s = raw.strip().lower()
        if s.startswith("import ") or s.startswith("from "):
            lines.append(s)
    return "\n".join(lines)


class TestSeparation(unittest.TestCase):
    """J1 & J2: pemisahan pemanggilan (diperiksa dari baris IMPORT)."""

    def test_retrieval_never_imports_llm(self):
        imports = _import_lines("retrieval_recommender.py")
        for banned in ("llm_client", "groq", "gemini", "openai", "claude", "full_llm"):
            self.assertNotIn(banned, imports, f"retrieval tidak boleh meng-import {banned}")

    def test_full_llm_never_imports_retrieval(self):
        imports = _import_lines("full_llm_experiment.py")
        for banned in ("faiss", "embedding_service", "title_generator",
                       "dosen_matcher", "sentence_transformers", "retrieval_recommender"):
            self.assertNotIn(banned, imports, f"full_llm tidak boleh meng-import {banned}")


class TestInputValidation(unittest.TestCase):
    """J6: input kosong ditolak; J3: schema input sama (mode divalidasi)."""

    def test_empty_input_rejected(self):
        from services.experiment_validation import validate_request
        ok, err = validate_request("", "", "retrieval_based")
        self.assertFalse(ok)
        self.assertIsNotNone(err)

    def test_invalid_mode_rejected(self):
        from services.experiment_validation import validate_request
        ok, err = validate_request("ide bagus", "", "hybrid")
        self.assertFalse(ok)

    def test_valid_input_accepted(self):
        from services.experiment_validation import validate_request
        for mode in ("retrieval_based", "full_llm"):
            ok, err = validate_request("deteksi wajah iot", "", mode)
            self.assertTrue(ok)
            self.assertIsNone(err)


class TestFullLlmValidationAndSchema(unittest.TestCase):
    """J4, J5, J7, J8 untuk Pendekatan B (LLM di-mock, tanpa jaringan)."""

    def _require_runtime_deps(self):
        for module_name in ("pandas", "pydantic_settings"):
            if not _has_module(module_name):
                self.skipTest(f"{module_name} belum terpasang pada environment Python ini")

    def _dataset(self):
        from services import full_llm_experiment
        return full_llm_experiment._dosen_records()

    def test_full_llm_output_schema_and_validation(self):
        self._require_runtime_deps()
        from services import full_llm_experiment as fle
        dataset = self._dataset()
        if not dataset:
            self.skipTest("lecturers.xlsx tidak tersedia")

        real_id = dataset[0]["lecturer_id"]
        real_name = dataset[0]["name"]

        # Dosen: 1 valid, 1 duplikat, 1 ID palsu (harus dibuang), + nama asing
        dosen_json = (
            '{"lecturer_recommendations":['
            f'{{"lecturer_id":"{real_id}","lecturer_name":"{real_name}","rank":1,"reason":"cocok","score":0.9}},'
            f'{{"lecturer_id":"{real_id}","lecturer_name":"{real_name}","rank":2,"reason":"dup","score":0.8}},'
            '{"lecturer_id":"ID_PALSU_9999","lecturer_name":"Dosen Fiktif","rank":3,"reason":"x","score":0.7}'
            ']}'
        )
        # Judul: 4 judul -> harus dipangkas jadi 3
        title_json = (
            '{"title_recommendations":['
            '{"title":"Judul A","method":"m","object":"o","goal":"g","context":"k"},'
            '{"title":"Judul B","method":"m","object":"o","goal":"g","context":"k"},'
            '{"title":"Judul C","method":"m","object":"o","goal":"g","context":"k"},'
            '{"title":"Judul D","method":"m","object":"o","goal":"g","context":"k"}'
            ']}'
        )
        usage = {"input_tokens": 10, "output_tokens": 5, "total_tokens": 15}
        with mock.patch.object(fle.llm_client, "chat",
                               side_effect=[(dosen_json, usage, "mock-model"),
                                            (title_json, usage, "mock-model")]):
            result = fle.recommend("deteksi wajah berbasis iot", "", "PKM-KC",
                                   top_k_lecturers=5, total_titles=3)

        # J4: schema sama
        self.assertEqual(set(result.keys()), EXPECTED_TOP_KEYS)
        self.assertEqual(set(result["experiment_metadata"].keys()), EXPECTED_META_KEYS)
        self.assertEqual(result["mode"], "full_llm")
        self.assertEqual(result["method_label"], "Pendekatan B")

        # J5: ID palsu dibuang, duplikat dibersihkan -> tersisa 1 dosen valid
        ids = [l["lecturer_id"] for l in result["lecturer_recommendations"]]
        self.assertNotIn("ID_PALSU_9999", ids)
        self.assertEqual(len(ids), len(set(ids)), "tidak boleh ada ID ganda")
        self.assertEqual(ids, [real_id])

        # tepat 3 judul
        self.assertEqual(len(result["title_recommendations"]), 3)

        # J7: metadata terisi
        self.assertEqual(result["experiment_metadata"]["total_tokens"], 30)
        self.assertIsNone(result["experiment_metadata"]["error"])

        # J8: tidak ada API key bocor di hasil
        self.assertNotIn("api_key", str(result).lower())

    def test_full_llm_retries_once_on_bad_json(self):
        self._require_runtime_deps()
        from services import full_llm_experiment as fle
        dataset = self._dataset()
        if not dataset:
            self.skipTest("lecturers.xlsx tidak tersedia")
        usage = {"input_tokens": 1, "output_tokens": 1, "total_tokens": 2}
        good = '{"lecturer_recommendations":[]}'
        title = '{"title_recommendations":[]}'
        # dosen: gagal sekali lalu berhasil (retry), title: langsung berhasil
        with mock.patch.object(fle.llm_client, "chat",
                               side_effect=[("bukan json", usage, "m"),
                                            (good, usage, "m"),
                                            (title, usage, "m")]):
            result = fle.recommend("ide", "abstrak", "PKM-RE")
        self.assertIsNone(result["experiment_metadata"]["error"])


class TestLogger(unittest.TestCase):
    """J7: metadata eksperimen tercatat; J8: tak ada field API key."""

    def test_log_record_fields(self):
        from services import experiment_logger
        payload = {"proposal_id": None, "idea": "ide", "abstract": "abs",
                   "pkm_category": "PKM-KC", "mode": "retrieval_based",
                   "top_k_lecturers": 5, "total_titles": 3}
        result = {
            "mode": "retrieval_based", "method_label": "Pendekatan A",
            "lecturer_recommendations": [], "title_recommendations": [],
            "experiment_metadata": {"provider": "local", "model_name": "sbert",
                                    "latency_ms": 12, "input_tokens": 0,
                                    "output_tokens": 0, "total_tokens": 0, "error": None},
        }
        record = experiment_logger.log_experiment(payload, result)
        self.assertEqual(set(record.keys()), set(experiment_logger.FIELDS))
        self.assertTrue(record["success"])
        self.assertNotIn("api_key", str(record).lower())
        self.assertNotIn("gemini_api", str(record).lower())


if __name__ == "__main__":
    unittest.main(verbosity=2)
