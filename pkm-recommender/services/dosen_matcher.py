import os
import pandas as pd
import numpy as np
import faiss
import logging
from core.config import settings
from services.embedding_service import embedding_service
from services.text_preprocessor import preprocess_text

logger = logging.getLogger(__name__)

class DosenMatcher:
    def __init__(self):
        self.index = None
        self.df = None
        
        # Determine paths
        self.data_path = settings.LECTURERS_FILE
        self.index_path = settings.FAISS_INDEX_FILE

        self._load_data()

    def _load_data(self):
        if not os.path.exists(self.data_path):
            logger.warning(f"Lecturer data file not found at {self.data_path}")
            return

        try:
            self.df = pd.read_excel(self.data_path)
            # Combine all textual rows to form a "profile" string
            # Fill NaNs with empty string
            text_columns = self.df.select_dtypes(include=['object']).columns
            self.df['__profile_text'] = self.df[text_columns].fillna('').agg(' '.join, axis=1)

            # Build FAISS index if it doesn't exist
            if not os.path.exists(self.index_path):
                self._build_index()
            else:
                self.index = faiss.read_index(self.index_path)
                logger.info("FAISS index loaded from disk.")
                
        except Exception as e:
            logger.error(f"Error loading lecturer data: {e}")

    def _build_index(self):
        logger.info("Building FAISS index for lecturers...")
        profiles = self.df['__profile_text'].tolist()
        
        # Get embeddings
        embeddings = embedding_service.get_embeddings(profiles)
        embeddings = np.array(embeddings).astype('float32')
        
        # Normalize vectors for Cosine Similarity
        faiss.normalize_L2(embeddings)
        
        d = embeddings.shape[1]
        self.index = faiss.IndexFlatIP(d) # Inner Product for Cosine Similarity
        self.index.add(embeddings)
        
        # Save index
        os.makedirs(os.path.dirname(self.index_path), exist_ok=True)
        faiss.write_index(self.index, self.index_path)
        logger.info(f"FAISS index built and saved to {self.index_path}")

    def suggest_dosen(self, idea_text: str, top_k: int = 3):
        if self.index is None or self.df is None:
            return {"error": "Matcher not ready or data missing."}

        # Embed the cleaned idea text for more stable cosine matching.
        clean_idea = preprocess_text(idea_text) or idea_text
        idea_embedding = embedding_service.get_embedding(clean_idea)
        idea_embedding = np.array([idea_embedding]).astype('float32')
        faiss.normalize_L2(idea_embedding)

        # Search
        distances, indices = self.index.search(idea_embedding, top_k)
        
        results = []
        for i, idx in enumerate(indices[0]):
            dosen_info = self.df.iloc[idx].to_dict()
            # Remove internal column
            dosen_info.pop('__profile_text', None)
            
            # Convert NaN to None for JSON compliance
            cleaned_info = {k: (v if pd.notnull(v) else None) for k, v in dosen_info.items()}
            
            results.append({
                "similarity_score": float(distances[0][i]),
                "dosen": cleaned_info
            })
            
        return results

dosen_matcher = DosenMatcher()
