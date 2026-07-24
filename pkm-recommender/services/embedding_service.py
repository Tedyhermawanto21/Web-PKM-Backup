import logging
import os
from pathlib import Path
from sentence_transformers import SentenceTransformer
from core.config import settings

logger = logging.getLogger(__name__)

class EmbeddingService:
    _instance = None
    _model = None

    def __new__(cls):
        if cls._instance is None:
            cls._instance = super(EmbeddingService, cls).__new__(cls)
            cls._instance._initialize_model()
        return cls._instance

    def _initialize_model(self):
        model_name_or_path = self._resolve_local_model(settings.SENTENCE_BERT_MODEL)
        logger.info(f"Loading Sentence-BERT model: {model_name_or_path}...")
        try:
            # Load the model. First time it will download, subsequently it will use cache.
            self._model = SentenceTransformer(
                model_name_or_path,
                local_files_only=os.path.exists(model_name_or_path),
            )
            logger.info("Model loaded successfully.")
        except Exception as e:
            logger.error(f"Failed to load model: {e}")
            raise e

    def _resolve_local_model(self, model_name: str) -> str:
        if os.path.exists(model_name):
            return model_name

        cache_root = Path(os.environ.get("HF_HOME", Path.home() / ".cache" / "huggingface"))
        cache_names = [model_name]
        if "/" not in model_name:
            cache_names.append(f"sentence-transformers/{model_name}")

        for cache_name in cache_names:
            snapshots_dir = cache_root / "hub" / f"models--{cache_name.replace('/', '--')}" / "snapshots"
            if not snapshots_dir.exists():
                continue

            snapshots = sorted(snapshots_dir.iterdir(), key=lambda path: path.stat().st_mtime, reverse=True)
            for snapshot in snapshots:
                has_sentence_transformer_config = (snapshot / "modules.json").exists()
                has_model_weights = (snapshot / "model.safetensors").exists() or (snapshot / "pytorch_model.bin").exists()
                if has_sentence_transformer_config and has_model_weights:
                    return str(snapshot)

        return model_name

    def get_embedding(self, text: str):
        """
        Encode a single text string into a vector.
        """
        if not self._model:
            raise RuntimeError("Model is not initialized.")
        return self._model.encode(text)

    def get_embeddings(self, texts: list[str]):
        """
        Encode a list of text strings into a list of vectors.
        """
        if not self._model:
            raise RuntimeError("Model is not initialized.")
        return self._model.encode(texts)

# Create a singleton instance to be imported across the app
embedding_service = EmbeddingService()
