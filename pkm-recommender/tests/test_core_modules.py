"""
Unit test untuk modul inti pada Tabel 4.8.

Dijalankan dari folder pkm-recommender:
    python -m unittest tests.test_core_modules -v

Test dibuat ringan:
- SBERT dan Gemini di-mock agar tidak download model dan tidak memanggil API asli.
- FAISS diuji dengan vektor kecil di memori.
- Template Mining dan MMR diuji dari kode TitleGenerator tanpa menjalankan singleton
  title_generator yang membaca seluruh data historis.
"""
import os
import sys
import types
import importlib.util
import unittest
from unittest import mock

import numpy as np

BASE = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
if BASE not in sys.path:
    sys.path.insert(0, BASE)


def _fake_settings_module():
    fake_config = types.ModuleType("core.config")
    fake_config.settings = types.SimpleNamespace(
        SENTENCE_BERT_MODEL="mock-sbert",
        LECTURERS_FILE="",
        WINNERS_FILE="",
        WINNERS_INDEX_FILE="",
        FULL_LLM_PROVIDER="gemini",
        FULL_LLM_MODEL="gemini-3.5-flash",
        FULL_LLM_API_KEY="test-key",
        GROQ_API_KEY="",
        GEMINI_API_KEY="test-key",
        GROQ_PRIMARY_MODEL="llama-3.3-70b-versatile",
        GEMINI_MODEL="gemini-3.5-flash",
        GROQ_API_URL="https://example.test/groq",
        GEMINI_API_URL="https://example.test/gemini",
    )
    return fake_config


def _fake_pandas_module():
    fake_pandas = types.ModuleType("pandas")

    class DataFrame:
        def __init__(self, *args, **kwargs):
            self._rows = []

        def iterrows(self):
            return iter(self._rows)

    fake_pandas.DataFrame = DataFrame
    fake_pandas.read_excel = lambda *args, **kwargs: DataFrame()
    fake_pandas.read_csv = lambda *args, **kwargs: DataFrame()
    fake_pandas.notnull = lambda value: value is not None
    return fake_pandas


def _fake_sklearn_modules():
    sklearn = types.ModuleType("sklearn")
    metrics = types.ModuleType("sklearn.metrics")
    pairwise = types.ModuleType("sklearn.metrics.pairwise")

    def cosine_similarity(a, b):
        a = np.asarray(a, dtype="float32")
        b = np.asarray(b, dtype="float32")
        a_norm = np.linalg.norm(a, axis=1, keepdims=True)
        b_norm = np.linalg.norm(b, axis=1, keepdims=True)
        a_norm[a_norm == 0] = 1
        b_norm[b_norm == 0] = 1
        return (a / a_norm) @ (b / b_norm).T

    pairwise.cosine_similarity = cosine_similarity
    return {
        "sklearn": sklearn,
        "sklearn.metrics": metrics,
        "sklearn.metrics.pairwise": pairwise,
    }


def _has_module(module_name):
    try:
        return importlib.util.find_spec(module_name) is not None
    except ModuleNotFoundError:
        return False


def _optional_dependency_fakes():
    modules = {}
    if not _has_module("pydantic_settings"):
        modules["core.config"] = _fake_settings_module()
    if not _has_module("pandas"):
        modules["pandas"] = _fake_pandas_module()
    if not _has_module("sklearn.metrics.pairwise"):
        modules.update(_fake_sklearn_modules())
    return modules


class _FakeSentenceTransformer:
    def __init__(self, *args, **kwargs):
        pass

    def encode(self, texts):
        if isinstance(texts, str):
            return np.ones(384, dtype="float32")
        return np.ones((len(texts), 384), dtype="float32")


class _FakeEmbeddingService:
    _model = object()

    def get_embedding(self, text):
        return np.ones(384, dtype="float32")

    def get_embeddings(self, texts):
        return np.ones((len(texts), 384), dtype="float32")


def _load_title_generator_without_singleton():
    """Load kode TitleGenerator tanpa menjalankan `title_generator = TitleGenerator()`."""
    source_path = os.path.join(BASE, "services", "title_generator.py")
    with open(source_path, "r", encoding="utf-8") as file:
        source = file.read()

    source = source.replace(
        "\ntitle_generator = TitleGenerator()\n",
        "\n# Singleton dinonaktifkan khusus unit test.\n",
    )

    fake_embedding_module = types.ModuleType("services.embedding_service")
    fake_embedding_module.embedding_service = _FakeEmbeddingService()

    fake_keybert_module = types.ModuleType("keybert")
    fake_keybert_module.KeyBERT = type(
        "KeyBERT",
        (),
        {"__init__": lambda self, *args, **kwargs: None},
    )

    fake_faiss_module = types.ModuleType("faiss")
    fake_faiss_module.Index = type("Index", (), {})
    fake_faiss_module.IndexFlatIP = lambda *args, **kwargs: object()
    fake_faiss_module.read_index = lambda *args, **kwargs: object()
    fake_faiss_module.write_index = lambda *args, **kwargs: None
    fake_faiss_module.normalize_L2 = lambda vectors: None

    module = types.ModuleType("title_generator_under_test")
    module.__file__ = source_path

    patched_modules = _optional_dependency_fakes()
    patched_modules.update(
        {
            "services.embedding_service": fake_embedding_module,
            "keybert": fake_keybert_module,
            "faiss": fake_faiss_module,
        }
    )

    with mock.patch.dict(
        sys.modules,
        patched_modules,
    ):
        exec(compile(source, source_path, "exec"), module.__dict__)
    return module


class TestCoreModulesTable48(unittest.TestCase):
    def test_01_embedding_sbert_returns_384_dimension_vector(self):
        fake_sentence_module = types.ModuleType("sentence_transformers")
        fake_sentence_module.SentenceTransformer = _FakeSentenceTransformer

        sys.modules.pop("services.embedding_service", None)
        patched_modules = _optional_dependency_fakes()
        patched_modules["sentence_transformers"] = fake_sentence_module
        with mock.patch.dict(sys.modules, patched_modules):
            from services import embedding_service as embedding_module

        vector = embedding_module.embedding_service.get_embedding(
            "Prediksi curah hujan lokal menggunakan machine learning"
        )

        self.assertEqual(np.asarray(vector).shape, (384,))

    def test_02_faiss_index_stores_and_retrieves_nearest_vector(self):
        try:
            import faiss
        except ImportError:
            self.skipTest("faiss belum terpasang pada environment Python ini")

        vectors = np.array(
            [
                [1.0, 0.0, 0.0],
                [0.0, 1.0, 0.0],
                [0.0, 0.0, 1.0],
            ],
            dtype="float32",
        )
        faiss.normalize_L2(vectors)

        index = faiss.IndexFlatIP(3)
        index.add(vectors)

        query = np.array([[0.0, 0.9, 0.1]], dtype="float32")
        faiss.normalize_L2(query)
        scores, indices = index.search(query, 1)

        self.assertEqual(index.ntotal, 3)
        self.assertEqual(int(indices[0][0]), 1)
        self.assertGreater(float(scores[0][0]), 0.9)

    def test_03_template_mining_builds_title_with_motk_structure(self):
        tg_module = _load_title_generator_without_singleton()
        generator = object.__new__(tg_module.TitleGenerator)

        components = {
            "M": "Long Short-Term Memory (LSTM)",
            "O": "Sistem Prediksi Curah Hujan Lokal",
            "T": "Peningkatan Akurasi Informasi Cuaca",
            "K": "Meteorologi Lokal",
            "tech": "Sensor Meteorologi",
        }
        pattern = "Pengembangan {O} Menggunakan {M} untuk {T} di Bidang {K}"

        title = generator._build_title(pattern, components)

        self.assertIn(components["M"], title)
        self.assertIn(components["O"], title)
        self.assertIn(components["T"], title)
        self.assertIn(components["K"], title)
        self.assertNotIn("{", title)
        self.assertNotIn("}", title)

    def test_04_mmr_selects_three_titles_with_lower_redundancy(self):
        tg_module = _load_title_generator_without_singleton()
        generator = object.__new__(tg_module.TitleGenerator)

        idea_embedding = np.array([1.0, 0.0, 0.0], dtype="float32")
        candidate_embeddings = np.array(
            [
                [0.80, 0.60, 0.00],   # paling relevan
                [0.79, 0.61, 0.00],   # sangat mirip dengan kandidat pertama
                [0.75, -0.66, 0.00],  # masih relevan, tetapi lebih beragam
                [0.10, 0.00, 0.99],
            ],
            dtype="float32",
        )

        selected = generator._apply_mmr(
            idea_embedding,
            candidate_embeddings,
            top_k=3,
            lambda_param=0.55,
        )

        self.assertEqual(len(selected), 3)
        self.assertEqual(selected[0], 0)
        self.assertEqual(selected[1], 2)
        self.assertNotEqual(selected[1], 1)

    def test_05_gemini_api_call_returns_structured_recommendation(self):
        with mock.patch.dict(sys.modules, _optional_dependency_fakes()):
            from services import full_llm_experiment as fle

        dataset = [
            {"lecturer_id": "38", "name": "Nunik Pratiwi", "dept": "Teknik Informatika", "keywords": "machine learning, data mining"},
            {"lecturer_id": "39", "name": "Irwansyah", "dept": "Teknik Informatika", "keywords": "data mining, prediksi"},
            {"lecturer_id": "29", "name": "Atiqah Meutia Hilda", "dept": "Teknik Informatika", "keywords": "GIS, machine learning"},
        ]
        dosen_json = (
            '{"lecturer_recommendations":['
            '{"lecturer_id":"38","lecturer_name":"Nunik Pratiwi","rank":1,"reason":"sesuai dengan prediksi data","score":0.95},'
            '{"lecturer_id":"39","lecturer_name":"Irwansyah","rank":2,"reason":"sesuai dengan data mining","score":0.90},'
            '{"lecturer_id":"29","lecturer_name":"Atiqah Meutia Hilda","rank":3,"reason":"sesuai dengan GIS dan prediksi","score":0.88}'
            ']}'
        )
        title_json = (
            '{"title_recommendations":['
            '{"rank":1,"title":"Prediksi Curah Hujan Lokal Menggunakan LSTM","method":"LSTM","object":"Curah hujan lokal","goal":"Prediksi cuaca","context":"Meteorologi lokal"},'
            '{"rank":2,"title":"Analisis Curah Hujan Harian Menggunakan Random Forest Regression","method":"Random Forest Regression","object":"Data hujan harian","goal":"Informasi cuaca","context":"Wilayah lokal"},'
            '{"rank":3,"title":"Pemodelan Curah Hujan Berbasis XGBoost Regression","method":"XGBoost Regression","object":"Data historis cuaca","goal":"Estimasi hujan","context":"Meteorologi lokal"}'
            ']}'
        )
        usage = {"input_tokens": 10, "output_tokens": 5, "total_tokens": 15}

        with mock.patch.object(fle, "_dosen_records", return_value=dataset), \
             mock.patch.object(
                 fle.llm_client,
                 "chat",
                 side_effect=[
                     (dosen_json, usage, "gemini-3.5-flash"),
                     (title_json, usage, "gemini-3.5-flash"),
                 ],
             ):
            result = fle.recommend(
                "Prediksi curah hujan lokal menggunakan machine learning",
                "Prediksi cuaca harian skala lokal menggunakan data historis cuaca.",
                "PKM-RE",
                top_k_lecturers=3,
                total_titles=3,
            )

        self.assertEqual(result["mode"], "full_llm")
        self.assertEqual(result["method_label"], "Pendekatan B")
        self.assertEqual(result["experiment_metadata"]["model_name"], "gemini-3.5-flash")
        self.assertEqual(len(result["lecturer_recommendations"]), 3)
        self.assertEqual(len(result["title_recommendations"]), 3)
        self.assertIsNone(result["experiment_metadata"]["error"])

    def test_06_gemini_output_validation_rejects_unknown_lecturer(self):
        with mock.patch.dict(sys.modules, _optional_dependency_fakes()):
            from services import full_llm_experiment as fle

        dataset = [
            {"lecturer_id": "38", "name": "Nunik Pratiwi", "dept": "Teknik Informatika", "keywords": "machine learning"},
            {"lecturer_id": "39", "name": "Irwansyah", "dept": "Teknik Informatika", "keywords": "data mining"},
        ]
        parsed = {
            "lecturer_recommendations": [
                {"lecturer_id": "38", "lecturer_name": "Nunik Pratiwi", "reason": "valid", "score": 0.95},
                {"lecturer_id": "999", "lecturer_name": "Dosen Tidak Terdaftar", "reason": "palsu", "score": 0.90},
                {"lecturer_id": "39", "lecturer_name": "Irwansyah", "reason": "valid", "score": 0.85},
            ]
        }

        lecturers = fle._validate_lecturers(parsed, dataset, top_k=3)
        lecturer_ids = [item["lecturer_id"] for item in lecturers]

        self.assertEqual(lecturer_ids, ["38", "39"])
        self.assertNotIn("999", lecturer_ids)
        self.assertEqual([item["rank"] for item in lecturers], [1, 2])


if __name__ == "__main__":
    unittest.main(verbosity=2)
