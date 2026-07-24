import logging
import pandas as pd
from typing import Dict, Any

logger = logging.getLogger(__name__)

class SchemaPredictor:
    def __init__(self):
        # A simple ruleset mapped from the PDF guide context
        self.schema_rules = {
            "PKM-RE": [
                "penelitian", "eksperimen", "laboratorium", "uji coba", "analisis",
                "pengujian", "riset", "data mining", "machine learning", "prediksi",
                "klasifikasi", "model",
            ],
            "PKM-RSH": [
                "sosial", "humaniora", "budaya", "masyarakat", "perilaku", "psikologi",
                "pengaruh", "dampak", "persepsi", "orang tua", "anak", "remaja",
                "hp", "gadget", "gawai", "kesehatan mental",
            ],
            "PKM-K": ["kewirausahaan", "produk", "jual", "bisnis", "komersial", "pemasaran", "profit"],
            "PKM-PM": [
                "pengabdian", "masyarakat", "non-profit", "pelatihan", "pemberdayaan",
                "sosialisasi", "desa", "edukasi", "literasi", "pendampingan",
                "sekolah", "orang tua",
            ],
            "PKM-PI": ["mitra profit", "industri", "ukm", "umkm", "penerapan iptek", "teknologi tepat guna", "solusi mitra"],
            "PKM-KC": [
                "karsa cipta", "prototype", "alat", "hardware", "iot",
                "rancang bangun", "purwarupa", "sistem prediksi",
                "aplikasi", "software", "perangkat lunak", "sistem", "platform",
            ],
            "PKM-KI": ["karya inovatif", "paten", "siap pakai", "inovasi", "hki", "komersialisasi"],
            "PKM-VGK": ["video", "gagasan", "konstruktif", "youtube", "animasi", "kampanye"],
            "PKM-GFT": ["gagasan", "futuristik", "masa depan", "tertulis", "konsep", "jangka panjang"],
            "PKM-AI": ["artikel", "ilmiah", "jurnal", "publikasi", "karya tulis"]
        }

    def predict(self, idea_text: str, additional_params: dict = None) -> Dict[str, Any]:
        """
        Predicts the PKM schema based on text and additional parameters (like profit partner).
        """
        ranked = self.predict_top_schemas(idea_text, additional_params=additional_params, top_k=3)
        best = ranked[0]

        return {
            "predicted_schema": best["predicted_schema"],
            "confidence_score": best["confidence_score"],
            "details": best["details"],
            "alternatives": ranked,
        }

    def predict_top_schemas(
        self,
        idea_text: str,
        additional_params: dict = None,
        top_k: int = 3,
    ) -> list[Dict[str, Any]]:
        idea_lower = idea_text.lower()
        scores = {schema: 0 for schema in self.schema_rules.keys()}

        for schema, keywords in self.schema_rules.items():
            for kw in keywords:
                if kw in idea_lower:
                    scores[schema] += 1

        if additional_params:
            has_partner = additional_params.get("has_partner", False)
            is_profit = additional_params.get("partner_is_profit", False)

            if has_partner:
                if is_profit:
                    scores["PKM-PI"] += 4
                else:
                    scores["PKM-PM"] += 4

        self._apply_domain_boosts(idea_lower, scores)

        if max(scores.values()) == 0:
            scores["PKM-RE"] = 2
            scores["PKM-KC"] = 1
            scores["PKM-PM"] = 1

        ranked_keys = sorted(scores, key=lambda schema: (-scores[schema], self._tie_breaker(schema, idea_lower)))
        selected = ranked_keys[:top_k]

        return [
            {
                "predicted_schema": schema,
                "confidence_score": scores[schema],
                "details": self._schema_reason(schema),
            }
            for schema in selected
        ]

    def _apply_domain_boosts(self, idea_lower: str, scores: dict[str, int]) -> None:
        social_terms = ["pengaruh", "dampak", "perilaku", "persepsi", "anak", "remaja", "orang tua", "hp", "gadget", "gawai"]
        tech_terms = ["sistem", "aplikasi", "iot", "sensor", "prediksi", "machine learning", "data mining", "dashboard"]
        community_terms = ["masyarakat", "sekolah", "orang tua", "edukasi", "literasi", "pelatihan", "pendampingan"]
        business_terms = ["produk", "usaha", "bisnis", "jual", "umkm", "mitra profit"]
        campaign_terms = ["kampanye", "video", "animasi", "konten"]

        if any(term in idea_lower for term in social_terms):
            scores["PKM-RSH"] += 3
            scores["PKM-RE"] += 1
            if any(term in idea_lower for term in ["anak", "remaja", "siswa"]) and any(term in idea_lower for term in ["hp", "gadget", "gawai"]):
                scores["PKM-PM"] += 1

        if any(term in idea_lower for term in tech_terms):
            scores["PKM-KC"] += 2
            scores["PKM-RE"] += 2

        if any(term in idea_lower for term in community_terms):
            scores["PKM-PM"] += 2

        if any(term in idea_lower for term in business_terms):
            scores["PKM-K"] += 2
            scores["PKM-PI"] += 1

        if any(term in idea_lower for term in campaign_terms):
            scores["PKM-VGK"] += 2

    def _tie_breaker(self, schema: str, idea_lower: str) -> int:
        preferred = ["PKM-RSH", "PKM-RE", "PKM-PM", "PKM-KC", "PKM-K", "PKM-PI", "PKM-VGK", "PKM-KI", "PKM-GFT", "PKM-AI"]
        if any(term in idea_lower for term in ["sistem", "aplikasi", "iot", "sensor", "prediksi"]):
            preferred = ["PKM-KC", "PKM-RE", "PKM-KI", "PKM-PM", "PKM-RSH", "PKM-PI", "PKM-K", "PKM-VGK", "PKM-GFT", "PKM-AI"]
        return preferred.index(schema) if schema in preferred else 99

    def _schema_reason(self, schema: str) -> str:
        reasons = {
            "PKM-RE": "Riset eksakta atau komputasional berbasis analisis, pemodelan, pengujian, atau eksperimen.",
            "PKM-RSH": "Riset sosial humaniora untuk mengkaji perilaku, persepsi, dampak sosial, atau fenomena masyarakat.",
            "PKM-PM": "Program pengabdian berbasis edukasi, pendampingan, atau pemberdayaan masyarakat non-profit.",
            "PKM-KC": "Karsa cipta untuk merancang sistem, alat, aplikasi, purwarupa, atau teknologi fungsional.",
            "PKM-K": "Kewirausahaan untuk mengembangkan produk atau jasa bernilai jual.",
            "PKM-PI": "Penerapan iptek untuk menyelesaikan masalah mitra profit atau industri.",
            "PKM-KI": "Karya inovatif siap pakai yang berpotensi dilindungi HKI atau dikomersialisasikan.",
            "PKM-VGK": "Video gagasan konstruktif untuk kampanye, edukasi visual, atau penyadaran publik.",
            "PKM-GFT": "Gagasan futuristik tertulis untuk solusi konseptual jangka panjang.",
            "PKM-AI": "Artikel ilmiah dari hasil kegiatan atau kajian yang sudah selesai.",
        }
        return reasons.get(schema, "Skema alternatif yang masih relevan dengan ide proposal.")

schema_predictor = SchemaPredictor()
