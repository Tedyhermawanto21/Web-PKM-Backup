"""
Integration test untuk Tabel 4.9.

Dijalankan dari folder pkm-recommender:
    python -m unittest tests.test_api_integration -v

Test ini memanggil fungsi endpoint FastAPI secara langsung dengan service
retrieval, full LLM, dan logger yang di-mock. Dengan begitu alur request-response
endpoint diuji tanpa menjalankan server dan tanpa memanggil Gemini asli.
"""
import importlib.util
import os
import sys
import types
import unittest
from unittest import mock

BASE = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
if BASE not in sys.path:
    sys.path.insert(0, BASE)


def _sample_result(mode="retrieval_based", error=None):
    method_label = "Pendekatan A" if mode == "retrieval_based" else "Pendekatan B"
    model_name = "paraphrase-multilingual-MiniLM-L12-v2" if mode == "retrieval_based" else "gemini-3.5-flash"
    return {
        "mode": mode,
        "method_label": method_label,
        "lecturer_recommendations": [
            {"lecturer_id": "38", "lecturer_name": "Nunik Pratiwi", "rank": 1, "reason": "relevan", "score": 0.95},
            {"lecturer_id": "39", "lecturer_name": "Irwansyah", "rank": 2, "reason": "relevan", "score": 0.90},
            {"lecturer_id": "29", "lecturer_name": "Atiqah Meutia Hilda", "rank": 3, "reason": "relevan", "score": 0.88},
        ],
        "title_recommendations": [
            {"rank": 1, "title": "Prediksi Curah Hujan Lokal Menggunakan LSTM", "method": "LSTM", "object": "Curah hujan lokal", "goal": "Prediksi cuaca", "context": "Meteorologi lokal"},
            {"rank": 2, "title": "Analisis Curah Hujan Harian Menggunakan Random Forest Regression", "method": "Random Forest Regression", "object": "Data hujan harian", "goal": "Informasi cuaca", "context": "Wilayah lokal"},
            {"rank": 3, "title": "Pemodelan Curah Hujan Berbasis XGBoost Regression", "method": "XGBoost Regression", "object": "Data historis cuaca", "goal": "Estimasi hujan", "context": "Meteorologi lokal"},
        ],
        "experiment_metadata": {
            "provider": "local" if mode == "retrieval_based" else "gemini",
            "model_name": model_name,
            "latency_ms": 12,
            "input_tokens": 0,
            "output_tokens": 0,
            "total_tokens": 0,
            "error": error,
        },
    }


class _FakeRouter:
    def post(self, *_args, **_kwargs):
        def decorator(func):
            return func
        return decorator


class _FakeBaseModel:
    def __init__(self, **kwargs):
        for base in reversed(type(self).mro()):
            for key, value in getattr(base, "__dict__", {}).items():
                if key.startswith("_") or callable(value):
                    continue
                setattr(self, key, value)
        for key, value in kwargs.items():
            setattr(self, key, value)

    def model_dump(self):
        return dict(self.__dict__)


def _module(name, **attrs):
    module = types.ModuleType(name)
    for key, value in attrs.items():
        setattr(module, key, value)
    return module


def _load_routes(retrieval_result=None, llm_result=None, retrieval_error=None, llm_error=None):
    log_calls = []
    fake_services = types.ModuleType("services")
    fake_services.__path__ = []

    def retrieval_recommend(*_args, **_kwargs):
        if retrieval_error:
            raise retrieval_error
        return retrieval_result or _sample_result("retrieval_based")

    def llm_recommend(*_args, **_kwargs):
        if llm_error:
            raise llm_error
        return llm_result or _sample_result("full_llm")

    def log_experiment(payload, result):
        log_calls.append({"payload": payload, "result": result})
        return {"experiment_id": "LOG-001", "success": result["experiment_metadata"]["error"] is None}

    def validate_request(idea, abstract, mode):
        if not (idea or abstract):
            return False, "Idea atau abstract wajib diisi."
        if mode not in {"retrieval_based", "full_llm"}:
            return False, "Mode tidak valid."
        return True, None

    retrieval_module = _module("services.retrieval_recommender", recommend=retrieval_recommend)
    llm_module = _module("services.full_llm_experiment", recommend=llm_recommend)
    logger_module = _module("services.experiment_logger", log_experiment=log_experiment)
    validation_module = _module("services.experiment_validation", validate_request=validate_request)
    fake_services.retrieval_recommender = retrieval_module
    fake_services.full_llm_experiment = llm_module
    fake_services.experiment_logger = logger_module
    fake_services.experiment_validation = validation_module

    patched_modules = {
        "services": fake_services,
        "fastapi": _module("fastapi", APIRouter=lambda: _FakeRouter()),
        "pydantic": _module("pydantic", BaseModel=_FakeBaseModel),
        "services.dosen_matcher": _module("services.dosen_matcher", dosen_matcher=object()),
        "services.title_generator": _module("services.title_generator", title_generator=object()),
        "services.schema_predictor": _module("services.schema_predictor", schema_predictor=object()),
        "services.text_preprocessor": _module("services.text_preprocessor", preprocess_text=lambda text: text),
        "services.groq_enhancer": _module("services.groq_enhancer", groq_enhancer=object()),
        "services.full_llm_recommender": _module("services.full_llm_recommender", generate_full_llm=lambda *args, **kwargs: {}),
        "services.retrieval_recommender": retrieval_module,
        "services.full_llm_experiment": llm_module,
        "services.experiment_logger": logger_module,
        "services.experiment_validation": validation_module,
    }

    route_path = os.path.join(BASE, "api", "routes.py")
    spec = importlib.util.spec_from_file_location("api_routes_under_test", route_path)
    routes = importlib.util.module_from_spec(spec)
    with mock.patch.dict(sys.modules, patched_modules):
        spec.loader.exec_module(routes)
    routes._log_calls = log_calls
    routes._patched_modules = patched_modules
    return routes


class TestApiIntegrationTable49(unittest.TestCase):
    def _request(self, routes, mode):
        return routes.ExperimentRequest(
            idea="Prediksi curah hujan lokal menggunakan machine learning",
            abstract="Prediksi cuaca harian skala lokal menggunakan data historis cuaca.",
            pkm_category="PKM-RE",
            top_k_lecturers=3,
            total_titles=3,
            mode=mode,
        )

    def _post_experiment(self, routes, mode):
        with mock.patch.dict(sys.modules, routes._patched_modules):
            return routes.experiment_recommend(self._request(routes, mode))

    def test_01_input_topic_from_website_to_hybrid_api_returns_lecturers(self):
        routes = _load_routes()
        response = self._post_experiment(routes, "retrieval_based")

        self.assertEqual(response["status"], "success")
        self.assertEqual(response["data"]["mode"], "retrieval_based")
        self.assertEqual(len(response["data"]["lecturer_recommendations"]), 3)

    def test_02_input_topic_from_website_to_gemini_api_returns_lecturers(self):
        routes = _load_routes()
        response = self._post_experiment(routes, "full_llm")

        self.assertEqual(response["status"], "success")
        self.assertEqual(response["data"]["mode"], "full_llm")
        self.assertEqual(len(response["data"]["lecturer_recommendations"]), 3)

    def test_03_title_recommendation_request_to_both_apis_returns_three_titles(self):
        routes = _load_routes()

        hybrid = self._post_experiment(routes, "retrieval_based")
        gemini = self._post_experiment(routes, "full_llm")

        self.assertEqual(len(hybrid["data"]["title_recommendations"]), 3)
        self.assertEqual(len(gemini["data"]["title_recommendations"]), 3)

    def test_04_gemini_connection_failure_returns_error_message(self):
        routes = _load_routes(llm_error=RuntimeError("Koneksi Gemini gagal"))
        response = self._post_experiment(routes, "full_llm")

        self.assertEqual(response["status"], "error")
        self.assertIn("Koneksi Gemini gagal", response["message"])

    def test_05_evaluation_result_log_is_saved_after_successful_request(self):
        routes = _load_routes()
        response = self._post_experiment(routes, "retrieval_based")

        self.assertEqual(response["status"], "success")
        self.assertEqual(len(routes._log_calls), 1)
        self.assertEqual(routes._log_calls[0]["payload"]["mode"], "retrieval_based")
        self.assertEqual(routes._log_calls[0]["result"]["mode"], "retrieval_based")


if __name__ == "__main__":
    unittest.main(verbosity=2)
