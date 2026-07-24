"""
Title Generator: RAG + MMR + M-O-T-K template transformation

Pipeline:
1. [RAG - Retrieval]    Semantic search pada 11k+ PKM winner titles via FAISS
2. [Trend Enrichment]   Deteksi topik â†’ inject teknologi trending yang relevan
3. [MMR - Re-ranking]   Maximal Marginal Relevance untuk diversitas
4. [M-O-T-K Transform]  Format baku PKM: Metode-Objek-Tujuan-Konteks
                        Semua bagian dibangun dari keyword IDE PENGGUNA (bukan referensi)
"""
import logging
import re
import os
import hashlib
import random
import numpy as np
import faiss
import pandas as pd
from sklearn.metrics.pairwise import cosine_similarity
from keybert import KeyBERT
from core.config import settings
from services.embedding_service import embedding_service
from services.text_preprocessor import preprocess_text

logger = logging.getLogger(__name__)

# â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
# Stopwords Bahasa Indonesia untuk KeyBERT
# Tanpa ini, kata-kata seperti "untuk", "dalam", "dengan" masuk jadi keyword
# â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
ID_STOPWORDS = [
    # Kata sambung & preposisi
    "yang", "dan", "di", "ke", "dari", "untuk", "dengan", "pada", "dalam",
    "adalah", "ini", "itu", "atau", "juga", "serta", "karena", "oleh",
    "sebagai", "agar", "supaya", "sehingga", "maka", "jika", "kalau",
    "namun", "tetapi", "tapi", "sedangkan", "meskipun", "walaupun",
    "bagi", "terhadap", "tentang", "antara", "melalui", "secara",
    "sebuah", "suatu", "setiap", "semua", "berbagai", "beberapa",
    # Kata ganti & artikel
    "saya", "kami", "kita", "mereka", "dia", "ia", "anda", "kamu",
    "ini", "itu", "sini", "sana", "situ", "tersebut",
    # Kata kerja umum
    "adalah", "merupakan", "dapat", "bisa", "akan", "telah", "sudah",
    "belum", "tidak", "tak", "bukan", "ada", "tidak ada", "perlu",
    "harus", "mampu", "membuat", "melakukan", "melakukan", "menggunakan",
    "ingin", "mengembangkan", "mengembankan", "pengembangan",
    # Kata keterangan
    "sangat", "lebih", "paling", "juga", "hanya", "saja", "pun",
    "bahkan", "masih", "lagi", "kembali", "kemudian", "lalu", "setelah",
    "sebelum", "ketika", "saat", "selama", "hingga", "sampai",
]

# â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
# Peta topik â†’ teknologi trending yang relevan
# Deteksi otomatis dari kata kunci idea pengguna
# â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
TOPIC_TECH_MAP = {
    # AI / ML Umum
    r"kecerdasan buatan|artificial intelligence|machine learning|ai\b|ml\b": [
        "Deep Learning", "Machine Learning", "Convolutional Neural Network (CNN)",
        "YOLOv8", "Random Forest", "XGBoost", "Transfer Learning",
    ],
    # NLP / Teks
    r"nlp|natural language|teks|sentiment|chatbot|bahasa": [
        "Natural Language Processing (NLP)", "BERT", "IndoBERT",
        "Large Language Model (LLM)", "Sentiment Analysis",
    ],
    # Computer Vision / Gambar
    r"gambar|citra|deteksi|pengenalan|klasifikasi|visual|kamera|foto": [
        "Computer Vision", "YOLOv8", "CNN", "ResNet-50",
        "Image Segmentation", "Object Detection",
    ],
    # IoT / Embedded
    r"iot|sensor|arduino|raspberry|monitoring|smart|otomasi|hardware|alat": [
        "Internet of Things (IoT)", "Mikrokontroler Arduino",
        "Raspberry Pi", "MQTT Protocol", "Real-time Monitoring",
    ],
    # Cuaca / Meteorologi
    r"cuaca|iklim|meteorologi|suhu|curah hujan|hujan|kelembaban|angin": [
        "Time Series Forecasting", "Long Short-Term Memory (LSTM)",
        "Random Forest", "Internet of Things (IoT)", "Sensor Meteorologi",
        "Machine Learning",
    ],
    # Pertanian / Lingkungan
    r"pertanian|tanaman|padi|sawah|kebun|ladang|agri|tani": [
        "Smart Farming", "Precision Agriculture", "Drone Pertanian",
        "Sensor Kelembaban Tanah", "Computer Vision",
    ],
    # Kesehatan / Medis
    r"kesehatan|medis|penyakit|diagnosa|rumah sakit|pasien|klinis|stunting": [
        "Diagnosa Berbasis AI", "Telemedicine", "Health Monitoring",
        "Convolutional Neural Network (CNN)", "Medical Image Analysis",
    ],
    # Pendidikan
    r"pendidikan|belajar|siswa|mahasiswa|sekolah|kampus|edukasi": [
        "E-Learning Adaptif", "Gamifikasi", "Augmented Reality (AR)",
        "Personalized Learning", "Learning Management System",
    ],
    # Lingkungan / Energi
    r"lingkungan|sampah|daur ulang|limbah|energi|surya|panel|angin": [
        "Green Technology", "Solar Panel Monitoring", "Waste Management System",
        "Energy Harvesting", "Carbon Footprint Tracker",
    ],
    # Ekonomi / UMKM
    r"umkm|ukm|bisnis|produk|usaha|ekonomi|kewirausahaan|startup": [
        "Digital Marketing AI", "Sistem Rekomendasi", "E-Commerce",
        "Business Intelligence", "Market Trend Analysis",
    ],
}

# â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
# Kosakata metode/teknologi + alias pencariannya.
# CATATAN METODOLOGIS: daftar ini BUKAN sumber "tren" yang di-hardcode. Ia hanya
# kamus untuk mengenali istilah; PRIORITAS pemilihan istilah ditentukan secara
# data-driven dari frekuensi kemunculannya pada judul PKM juara relevan hasil
# retrieval SBERT+FAISS (lihat _mine_methods_from_winners). Dengan begitu istilah
# mengikuti tren pada data juara, sementara TOPIC_TECH_MAP hanya jadi cadangan.
# â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
METHOD_VOCAB = {
    "Internet of Things (IoT)": ["internet of things", "iot"],
    "Machine Learning": ["machine learning", "pembelajaran mesin"],
    "Deep Learning": ["deep learning", "pembelajaran mendalam"],
    "Convolutional Neural Network (CNN)": ["convolutional neural network", "cnn"],
    "YOLO": ["yolov", "yolo"],
    "Long Short-Term Memory (LSTM)": ["long short-term memory", "lstm"],
    "Random Forest": ["random forest"],
    "Support Vector Machine (SVM)": ["support vector machine", "svm"],
    "Natural Language Processing (NLP)": ["natural language processing", "nlp"],
    "Computer Vision": ["computer vision", "visi komputer"],
    "Sensor": ["sensor"],
    "Mikrokontroler Arduino": ["arduino", "mikrokontroler"],
    "Raspberry Pi": ["raspberry"],
    "Augmented Reality (AR)": ["augmented reality"],
    "Sistem Pendukung Keputusan": ["sistem pendukung keputusan", "spk"],
    "Sistem Informasi": ["sistem informasi"],
    "Aplikasi Mobile": ["android", "aplikasi mobile", "mobile"],
    "Website": ["website", "berbasis web"],
    "Gamifikasi": ["gamifikasi"],
    "Data Mining": ["data mining"],
    "Extreme Gradient Boosting (XGBoost)": ["xgboost", "gradient boosting"],
    "Time Series Forecasting": ["time series", "peramalan", "forecasting"],
}

# Tujuan (T) per domain — agar frasa tujuan natural dan tidak garbled dari keyword
DOMAIN_GOALS = {
    r"cuaca|iklim|meteorologi|suhu|curah hujan|hujan|kelembaban|angin": [
        "Peningkatan Akurasi Informasi Cuaca",
        "Pemantauan Perubahan Cuaca secara Real-time",
        "Deteksi Dini Potensi Cuaca Ekstrem",
        "Dukungan Keputusan Berbasis Data Meteorologi",
    ],
    r"pertanian|tanaman|tani|agri|sawah|padi|kebun": [
        "Peningkatan Produktivitas Pertanian",
        "Optimasi Penggunaan Sumber Daya Lahan",
        "Deteksi Dini Hama dan Penyakit Tanaman",
        "Efisiensi Sistem Irigasi Cerdas",
    ],
    r"kesehatan|medis|penyakit|diagnosa|pasien|klinik|rumah sakit|stunting": [
        "Deteksi Dini Penyakit secara Akurat",
        "Peningkatan Layanan Kesehatan Masyarakat",
        "Efisiensi Proses Monitoring Pasien",
        "Optimasi Sistem Diagnosa Medis",
    ],
    r"pendidikan|belajar|siswa|mahasiswa|sekolah|edukasi|kampus": [
        "Peningkatan Kualitas Pembelajaran",
        "Personalisasi Pengalaman Belajar Siswa",
        "Efisiensi Penilaian Hasil Belajar",
        "Peningkatan Aksesibilitas Materi Pendidikan",
    ],
    r"lingkungan|sampah|limbah|energi|emisi|polusi|daur ulang": [
        "Pengurangan Dampak Lingkungan",
        "Efisiensi Pengelolaan Sampah dan Limbah",
        "Peningkatan Kesadaran Lingkungan Berkelanjutan",
        "Optimasi Konsumsi Energi Terbarukan",
    ],
    r"umkm|usaha kecil|bisnis|produk|kewirausahaan|startup|ekonomi": [
        "Peningkatan Daya Saing UMKM",
        "Optimasi Strategi Pemasaran Digital",
        "Efisiensi Manajemen Operasional Bisnis",
        "Perluasan Jangkauan Pasar",
    ],
    r"iot|sensor|monitoring|hardware|perangkat|otomasi": [
        "Otomatisasi Proses Pemantauan Real-time",
        "Peningkatan Efisiensi Sistem Monitoring",
        "Optimasi Pengambilan Keputusan Berbasis Data",
        "Integrasi Perangkat Pintar yang Terpadu",
    ],
    r"kecerdasan buatan|machine learning|ai\b|deep learning|nlp": [
        "Peningkatan Akurasi Prediksi Sistem",
        "Otomatisasi Proses Klasifikasi Data",
        "Optimasi Kinerja Algoritma Cerdas",
        "Pengembangan Sistem Rekomendasi Adaptif",
    ],
}

DEFAULT_GOALS = [
    "Peningkatan Efisiensi dan Efektivitas Sistem",
    "Optimasi Proses Berbasis Data",
    "Pengembangan Solusi Teknologi Inovatif",
    "Peningkatan Kualitas Layanan Masyarakat",
]

DOMAIN_CONTEXTS = {
    r"cuaca|iklim|meteorologi|suhu|curah hujan|hujan|kelembaban|angin": "Meteorologi Lokal",
    r"pertanian|tanaman|tani|agri|sawah|padi|kebun": "Pertanian",
    r"kesehatan|medis|penyakit|diagnosa|pasien|klinik|rumah sakit|stunting": "Kesehatan",
    r"pendidikan|belajar|siswa|mahasiswa|sekolah|edukasi|kampus": "Pendidikan",
    r"lingkungan|sampah|limbah|energi|emisi|polusi|daur ulang": "Lingkungan",
    r"umkm|usaha kecil|bisnis|produk|kewirausahaan|startup|ekonomi": "UMKM dan Kewirausahaan",
    r"iot|sensor|monitoring|hardware|perangkat|otomasi": "Sistem Monitoring Berbasis IoT",
}

# Struktur kalimat M-O-T-K. Pola dijaga ketat agar fallback lokal tidak
# menghasilkan frasa generik seperti "Sistem Cerdas Prediksi ...".
MOTK_PATTERNS = [
    "Rancang Bangun {O} Berbasis {M} untuk {T} pada {K}",
    "Pengembangan {O} Menggunakan {M} untuk {T} di Bidang {K}",
    "Implementasi {M} pada {O} untuk {T} di {K}",
    "Purwarupa {O} Berbasis {tech} untuk {T}",
    "Penerapan {tech} dalam {O} untuk {T} pada {K}",
    "Desain {O} dengan {M} sebagai Solusi {T}",
    "Optimalisasi {O} Menggunakan {M} untuk {T}",
    "Analisis dan Pengembangan {O} Berbasis {M} untuk {T} pada {K}",
    "Rancang Bangun {O} Menggunakan {M} untuk {T} pada {K}",
    "Desain dan Implementasi {O} Berbasis {tech} untuk {T}",
]

SCHEMA_TITLE_PATTERNS = {
    "PKM-RSH": [
        "Analisis Pengaruh {topic} terhadap {impact} pada {context}",
        "Studi Hubungan {topic} dengan {impact} pada {context}",
        "Eksplorasi Faktor {topic} dalam Pembentukan {impact} pada {context}",
        "Kajian Persepsi {context} terhadap {topic} dan Implikasinya pada {impact}",
    ],
    "PKM-RE": [
        "Penerapan {method} untuk Menganalisis {topic} pada {context}",
        "Analisis Pola {topic} Menggunakan {method} sebagai Dasar {impact}",
        "Model Prediksi {impact} Berbasis {method} pada Kajian {topic}",
        "Evaluasi {topic} terhadap {impact} Menggunakan Pendekatan {method}",
    ],
    "PKM-KC": [
        "Rancang Bangun {object} Berbasis {method} untuk {impact}",
        "Pengembangan {object} Menggunakan {method} sebagai Solusi {impact}",
        "Purwarupa {object} Berbasis {method} untuk Mendukung {context}",
        "Desain dan Implementasi {object} untuk {impact} pada {context}",
    ],
    "PKM-PM": [
        "Edukasi {topic} untuk Meningkatkan {impact} pada {context}",
        "Program Pendampingan {context} dalam Penguatan {impact} melalui {topic}",
        "Literasi Digital tentang {topic} sebagai Upaya Pencegahan Risiko pada {context}",
        "Pemberdayaan {context} melalui Pelatihan {topic} untuk {impact}",
    ],
    "PKM-K": [
        "Inovasi Produk {object} Berbasis {topic} sebagai Peluang Usaha Mahasiswa",
        "Pengembangan {object} Bernilai Ekonomi untuk Menjawab Kebutuhan {context}",
        "{object} sebagai Produk Kreatif Berbasis {topic} untuk Pasar Edukatif",
    ],
    "PKM-PI": [
        "Optimalisasi {object} pada Mitra Usaha melalui Penerapan {method}",
        "Penerapan {method} untuk Meningkatkan Efisiensi {object} pada Mitra Produktif",
        "Digitalisasi Proses {topic} sebagai Solusi Produktivitas Mitra Usaha",
    ],
    "PKM-KI": [
        "Inovasi {object} Berbasis {method} sebagai Karya Siap Pakai untuk {impact}",
        "Pengembangan {object} Inovatif untuk Mendukung {context}",
        "{object} Berbasis {method} sebagai Solusi Praktis {impact}",
    ],
    "PKM-VGK": [
        "Kampanye Visual {topic} sebagai Upaya Peningkatan Kesadaran {context}",
        "Video Gagasan Konstruktif tentang {topic} untuk Penguatan {impact}",
        "Edukasi Digital {topic} melalui Media Visual Interaktif bagi {context}",
    ],
    "PKM-GFT": [
        "Gagasan Futuristik {object} untuk Menghadapi Tantangan {impact}",
        "Konsep {object} Berbasis {method} sebagai Solusi Masa Depan {context}",
        "Strategi Inovatif {topic} untuk Mewujudkan {impact} Berkelanjutan",
    ],
    "PKM-AI": [
        "Artikel Ilmiah tentang {topic} dan Implikasinya terhadap {impact}",
        "Tinjauan Ilmiah {topic} pada {context} sebagai Dasar Pengembangan Solusi",
        "Analisis Literatur {topic} untuk Memahami {impact} pada {context}",
    ],
}

SCHEMA_METHODS = {
    "PKM-RSH": ["Analisis Korelasi", "Survei Kuantitatif", "Pendekatan Studi Kasus"],
    "PKM-RE": ["Data Mining", "Machine Learning", "Analisis Regresi"],
    "PKM-KC": ["Machine Learning", "Internet of Things (IoT)", "Sistem Pendukung Keputusan"],
    "PKM-PM": ["Participatory Action", "Literasi Digital", "Pendampingan Komunitas"],
    "PKM-K": ["Design Thinking", "Lean Startup", "Analisis Kelayakan Usaha"],
    "PKM-PI": ["Teknologi Tepat Guna", "Business Process Improvement", "Sistem Informasi"],
    "PKM-KI": ["Rekayasa Produk", "Rapid Prototyping", "Teknologi Cerdas"],
    "PKM-VGK": ["Storytelling Digital", "Animasi Edukatif", "Kampanye Multimedia"],
    "PKM-GFT": ["Scenario Planning", "Desain Konseptual", "Analisis Futuristik"],
    "PKM-AI": ["Systematic Literature Review", "Analisis Bibliometrik", "Kajian Literatur"],
}


class TitleGenerator:
    def __init__(self):
        self._keybert_model = None
        self.winners_df: pd.DataFrame | None = None
        self.winners_index: faiss.Index | None = None
        self._load_winners()

    # â”€â”€ Data & FAISS â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    def _load_winners(self):
        if not os.path.exists(settings.WINNERS_FILE):
            logger.warning(f"Winners file tidak ditemukan: {settings.WINNERS_FILE}")
            return
        self.winners_df = pd.read_csv(settings.WINNERS_FILE)
        logger.info(f"Loaded {len(self.winners_df)} PKM winner titles.")
        if os.path.exists(settings.WINNERS_INDEX_FILE):
            self.winners_index = faiss.read_index(settings.WINNERS_INDEX_FILE)
            logger.info("Winners FAISS index dimuat dari disk.")
        else:
            self._build_winners_index()

    def _build_winners_index(self):
        logger.info("Membangun FAISS winners index (proses sekali, ~beberapa menit)...")
        titles = self.winners_df['title'].fillna('').tolist()
        embeddings = np.array(embedding_service.get_embeddings(titles), dtype='float32')
        faiss.normalize_L2(embeddings)
        index = faiss.IndexFlatIP(embeddings.shape[1])
        index.add(embeddings)
        self.winners_index = index
        os.makedirs(os.path.dirname(settings.WINNERS_INDEX_FILE), exist_ok=True)
        faiss.write_index(index, settings.WINNERS_INDEX_FILE)
        logger.info(f"Winners FAISS index disimpan ke {settings.WINNERS_INDEX_FILE}")

    def retrieve_historical_titles(self, idea_text: str, top_k: int = 3, candidate_pool: int = 25) -> list[dict]:
        """
        Ambil judul PKM historis yang mirip lalu re-rank dengan MMR agar beragam.
        """
        if self.winners_df is None or self.winners_index is None:
            return []

        clean_idea = preprocess_text(idea_text) or idea_text
        idea_emb = np.array([embedding_service.get_embedding(clean_idea)], dtype='float32')
        faiss.normalize_L2(idea_emb)

        limit = min(candidate_pool, len(self.winners_df))
        distances, indices = self.winners_index.search(idea_emb, limit)
        valid_indices = [int(idx) for idx in indices[0] if idx >= 0]
        if not valid_indices:
            return []

        # Terapkan filter anti-pollution (domain filter) secara taktis
        idea_lower = idea_text.lower()
        non_medical_keywords = [
            "gas", "elpiji", "lpg", "kebocoran", "kebakaran", "cuaca", "iklim", "meteorologi",
            "sampah", "limbah", "energi", "panel surya", "bioplastik", "pertanian", "padi", 
            "irigasi", "pupuk", "tanaman", "hidroponik", "banjir", "gempa", "longsor"
        ]
        medical_keywords = [
            "jantung", "darah", "pasien", "medis", "rumah sakit", "klinik", "penyakit", 
            "stunting", "obat", "aritmia", "glukosa", "kanker", "kardiovaskular"
        ]
        
        has_non_medical = any(w in idea_lower for w in non_medical_keywords)
        has_medical = any(w in idea_lower for w in medical_keywords)
        
        filtered_indices = []
        for idx in valid_indices:
            row = self.winners_df.iloc[idx]
            title_lower = str(row.get('title') or '').lower()
            
            # Saring keluar judul medis jika input non-medis
            if has_non_medical and any(w in title_lower for w in medical_keywords):
                continue
                
            # Saring keluar judul non-medis jika input medis
            if has_medical and any(w in title_lower for w in non_medical_keywords) and not any(w in title_lower for w in medical_keywords):
                continue
                
            filtered_indices.append(idx)
            
        # Jika hasil filter kosong, gunakan valid_indices asli agar tidak kosong sama sekali
        if not filtered_indices:
            filtered_indices = valid_indices

        # Gunakan list filtered_indices untuk ekstraksi judul
        titles = self.winners_df.iloc[filtered_indices]['title'].fillna('').tolist()
        cand_embeddings = np.array(embedding_service.get_embeddings(titles), dtype='float32')
        selected = self._apply_mmr(idea_emb.squeeze(), cand_embeddings, top_k=min(top_k, len(titles)))

        results = []
        for selected_idx in selected:
            source_idx = filtered_indices[selected_idx]
            row = self.winners_df.iloc[source_idx].to_dict()
            title = row.get('title') or row.get('judul') or titles[selected_idx]
            results.append({
                "title": title,
                "similarity_score": float(distances[0][selected_idx]) if selected_idx < len(distances[0]) else 0.5,
                "metadata": {k: v for k, v in row.items() if k not in {"title", "judul"}},
            })
        return results

    @property
    def keybert_model(self) -> KeyBERT:
        if self._keybert_model is None:
            logger.info("Inisialisasi KeyBERT...")
            self._keybert_model = KeyBERT(model=embedding_service._model)
        return self._keybert_model

    # â”€â”€ Ekstraksi keyword dari teks ide â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    def _extract_keywords(self, text: str) -> list[str]:
        results = self.keybert_model.extract_keywords(
            text,
            keyphrase_ngram_range=(1, 2),
            stop_words=ID_STOPWORDS,   # <-- filter stopwords B.Indonesia
            use_mmr=True,
            diversity=0.5,
            top_n=8,
        )
        # Bersihkan: buang keyword yang hanya berisi stopword atau angka pendek
        clean = []
        for kw, score in results:
            tokens = kw.lower().split()
            # Buang jika semua token adalah stopword
            if all(t in ID_STOPWORDS for t in tokens):
                continue
            # Buang jika kata pertama adalah stopword (misal "untuk pertanian")
            if tokens[0] in ID_STOPWORDS:
                kw = " ".join(tokens[1:]).strip()
                if not kw:
                    continue
            clean.append(kw)
        return clean[:6]

    # â”€â”€ Deteksi teknologi trending relevan dari teks â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    def _detect_trending_tech(self, idea_text: str) -> list[str]:
        """
        Utamakan istilah metode/teknologi yang DIGALI dari judul PKM juara
        relevan (RAG, data-driven). Peta statis TOPIC_TECH_MAP hanya dipakai
        sebagai cadangan bila mining tidak menemukan istilah apa pun.
        """
        mined = self._mine_methods_from_winners(idea_text)

        idea_lower = idea_text.lower()
        matched: list[str] = list(mined)  # istilah dari data juara didahulukan
        for pattern, techs in TOPIC_TECH_MAP.items():
            if re.search(pattern, idea_lower):
                matched.extend(techs)
        if not matched:
            matched = ["Kecerdasan Buatan", "Machine Learning", "Sistem Cerdas"]

        unique = []
        for tech in matched:
            normalized = self._normalize_method(tech)
            if normalized not in unique:
                unique.append(normalized)
        return unique[:4]

    # â”€â”€ Mining istilah metode dari judul juara relevan (RAG, data-driven) â”€â”€â”€â”€â”€
    def _mine_methods_from_winners(self, idea_text: str, pool: int = 30, top_n: int = 6) -> list[str]:
        """
        Gali istilah metode/teknologi dari judul PKM juara yang relevan.

        Alur (selaras metodologi RAG proposal):
        1. SBERT+FAISS menarik judul juara termirip (retrieve_historical_titles).
        2. Hitung frekuensi kemunculan tiap istilah METHOD_VOCAB pada judul itu.
        3. Kembalikan istilah terurut berdasarkan frekuensi (tren pada data juara).

        Aman gagal: bila indeks winner belum siap, kembalikan [] agar pemanggil
        jatuh ke cadangan TOPIC_TECH_MAP.
        """
        try:
            historical = self.retrieve_historical_titles(
                idea_text, top_k=pool, candidate_pool=pool
            )
        except Exception as exc:  # pragma: no cover - defensif
            logger.warning("Mining metode dari winner gagal: %s", exc)
            return []

        if not historical:
            return []

        corpus = " ".join(str(h.get("title") or "") for h in historical).lower()
        counts: dict[str, int] = {}
        for canonical, aliases in METHOD_VOCAB.items():
            hit = 0
            for alias in aliases:
                hit += corpus.count(alias)
            if hit > 0:
                counts[canonical] = hit

        ranked = sorted(counts.items(), key=lambda kv: kv[1], reverse=True)
        mined = [self._normalize_method(name) for name, _ in ranked[:top_n]]
        if mined:
            logger.info(f"Metode hasil mining dari judul juara: {mined}")
        return mined

    # â”€â”€ Penanda skema teknis (boleh disuntik istilah metode/teknologi) â”€â”€â”€â”€â”€â”€â”€
    def _is_technical_schema(self, schema: str) -> bool:
        """
        Hanya skema rekayasa/eksakta yang wajar memakai istilah metode teknis.
        Skema sosial/pengabdian (RSH, PM, K, VGK, GFT, AI) tidak disuntik agar
        judulnya tidak tercemar istilah komputasi.
        """
        key = str(schema or "").strip().upper()
        return any(t in key for t in ("PKM-KC", "PKM-RE", "PKM-KI", "PKM-PI"))

    # â”€â”€ Parse komponen M-O-T-K dari keyword idea pengguna â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    def _detect_domain_goal(self, idea_text: str, index: int = 0) -> str:
        idea_lower = idea_text.lower()
        for pattern, goals in DOMAIN_GOALS.items():
            if re.search(pattern, idea_lower):
                return goals[index % len(goals)]
        return DEFAULT_GOALS[index % len(DEFAULT_GOALS)]

    def _detect_domain_context(self, idea_text: str, fallback: str) -> str:
        idea_lower = idea_text.lower()
        for pattern, context in DOMAIN_CONTEXTS.items():
            if re.search(pattern, idea_lower):
                return context
        return fallback

    def _detect_primary_object(self, idea_text: str, fallback: str) -> str:
        idea_lower = idea_text.lower()
        if re.search(r"cuaca|iklim|meteorologi|curah hujan|hujan", idea_lower):
            if re.search(r"alat|sensor|iot|stasiun|perangkat|hardware", idea_lower):
                return "Stasiun Cuaca Mini"
            return "Sistem Prediksi Cuaca Lokal"
        return fallback

    def _normalize_method(self, method: str) -> str:
        replacements = {
            "Teknologi Berbasis AI": "Kecerdasan Buatan",
            "Sistem Cerdas": "Kecerdasan Buatan",
        }
        return replacements.get(method, method)

    def _parse_motk_components(
        self, keywords: list[str], techs: list[str], idea_text: str = "", goal_index: int = 0
    ) -> dict:
        """
        Petakan keyword â†’ M (metode/teknologi), O (objek/sistem),
        T (tujuan), K (konteks domain).

        Aturan sederhana namun efektif:
        - Keyword ke-0 â†’ Objek utama (apa yang dibangun/dianalisis)
        - Keyword ke-1 â†’ Domain/Konteks (bidang penerapan)
        - Keyword ke-2+ â†’ Tujuan (apa yang ingin dicapai)
        - Teknologi trending â†’ Metode & tech spesifik
        """
        # Kata sambung yang tidak di-capitalize di tengah kalimat
        _CONJ = {"dan", "atau", "di", "ke", "dari", "untuk", "pada", "dengan", "dalam", "sebagai", "yang"}

        def titleize(s: str) -> str:
            words = s.split()
            result = []
            for i, w in enumerate(words):
                if i == 0 or w.lower() not in _CONJ:
                    result.append(w.capitalize())
                else:
                    result.append(w.lower())
            return " ".join(result)

        kws = [titleize(k) for k in keywords] if keywords else ["Sistem Cerdas"]
        source_text = " ".join([idea_text, " ".join(keywords)])

        objek = self._detect_primary_object(source_text, kws[0])
        fallback_context = kws[1] if len(kws) > 1 else "Kehidupan Sehari-hari"
        konteks = self._detect_domain_context(source_text, fallback_context)
        tujuan = self._detect_domain_goal(source_text, goal_index)

        metode = self._normalize_method(techs[0] if techs else "Machine Learning")
        tech_alt = self._normalize_method(techs[1] if len(techs) > 1 else metode)

        return {
            "M": metode,
            "O": objek,
            "T": tujuan,
            "K": konteks,
            "tech": tech_alt,
        }

    # â”€â”€ Buat judul dari pola M-O-T-K & komponen â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    def _build_title(self, pattern: str, components: dict) -> str:
        title = pattern.format(**components)
        # Pastikan tidak ada placeholder yang tersisa
        title = re.sub(r'\{[^}]+\}', '', title)
        title = re.sub(r'\bSistem Sistem\b', 'Sistem', title)
        title = re.sub(r'\bSistem Cerdas Prediksi Cuaca\b', 'Sistem Prediksi Cuaca Lokal', title)
        title = re.sub(r'\bSistem Prediksi Cuaca Lokal Cerdas\b', 'Sistem Prediksi Cuaca Lokal', title)
        title = re.sub(r'\bAlat Prediksi Cuaca Cerdas\b', 'Alat Prediksi Cuaca', title)
        title = re.sub(r'\bSistem Prediksi Cuaca Cerdas\b', 'Sistem Prediksi Cuaca', title)
        title = title.replace("Berbasis Teknologi Berbasis AI", "Berbasis Kecerdasan Buatan")
        title = title.replace("dengan Teknologi Kecerdasan Buatan", "Berbasis Kecerdasan Buatan")
        # Dedupe umum: buang kata/frasa berulang berurutan untuk domain apa pun
        title = self._dedupe_title(title)
        # Bersihkan spasi ganda
        title = re.sub(r'\s+', ' ', title).strip()
        return title

    def _dedupe_title(self, title: str) -> str:
        """
        Hilangkan pengulangan yang membuat judul template awut-awutan, secara
        umum (tanpa perlu tambal-sulam per-domain):
        - kata identik berurutan: "Sistem Sistem" -> "Sistem"
        - bigram identik berurutan: "Penggunaan HP Penggunaan HP" -> "Penggunaan HP"
        """
        # 1) kata identik berurutan
        words = title.split()
        collapsed: list[str] = []
        for w in words:
            if collapsed and collapsed[-1].lower() == w.lower():
                continue
            collapsed.append(w)

        # 2) bigram identik berurutan
        result: list[str] = []
        i = 0
        while i < len(collapsed):
            if (
                i + 3 < len(collapsed)
                and collapsed[i].lower() == collapsed[i + 2].lower()
                and collapsed[i + 1].lower() == collapsed[i + 3].lower()
            ):
                result.extend([collapsed[i], collapsed[i + 1]])
                i += 4
            else:
                result.append(collapsed[i])
                i += 1
        return " ".join(result)

    # â”€â”€ MMR re-ranking â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    def _apply_mmr(
        self,
        idea_embedding: np.ndarray,
        candidate_embeddings: np.ndarray,
        top_k: int = 3,
        lambda_param: float = 0.55,
    ) -> list[int]:
        doc_sims = cosine_similarity(
            candidate_embeddings, idea_embedding.reshape(1, -1)
        ).flatten()
        cand_sims = cosine_similarity(candidate_embeddings, candidate_embeddings)
        selected: list[int] = [int(np.argmax(doc_sims))]
        for _ in range(top_k - 1):
            if len(selected) >= len(candidate_embeddings):
                break
            scores = []
            for i in range(len(candidate_embeddings)):
                if i in selected:
                    scores.append(-np.inf)
                    continue
                rel = doc_sims[i]
                red = float(np.max([cand_sims[i][j] for j in selected]))
                scores.append(lambda_param * rel - (1 - lambda_param) * red)
            selected.append(int(np.argmax(scores)))
        return selected

    def generate_schema_title_groups(
        self,
        idea_text: str,
        schemas: list[dict] | list[str],
        titles_per_schema: int = 3,
    ) -> list[dict]:
        clean_idea = preprocess_text(idea_text) or idea_text
        keywords = self._extract_keywords(clean_idea)
        # Gali istilah metode dari judul juara sekali saja untuk seluruh grup skema
        mined_methods = self._mine_methods_from_winners(idea_text)
        groups = []

        for schema_index, schema_item in enumerate(schemas):
            if isinstance(schema_item, dict):
                schema = schema_item.get("predicted_schema") or schema_item.get("schema")
                confidence = schema_item.get("confidence_score", 0)
                reason = schema_item.get("details", "")
            else:
                schema = str(schema_item)
                confidence = 0
                reason = ""

            if not schema:
                continue

            titles = self.generate_titles_for_schema(
                idea_text,
                schema,
                top_k=titles_per_schema,
                keywords=keywords,
                schema_index=schema_index,
                mined_methods=mined_methods,
            )
            groups.append({
                "schema": schema,
                "confidence_score": confidence,
                "reason": reason,
                "titles": titles,
            })

        return groups

    def generate_titles_for_schema(
        self,
        idea_text: str,
        schema: str,
        top_k: int = 3,
        keywords: list[str] | None = None,
        schema_index: int = 0,
        mined_methods: list[str] | None = None,
    ) -> list[str]:
        clean_idea = preprocess_text(idea_text) or idea_text
        keywords = keywords if keywords is not None else self._extract_keywords(clean_idea)
        context = self._title_context(idea_text, keywords)
        topic = self._title_topic(idea_text, keywords, schema)
        impact = self._title_impact(idea_text, schema)
        object_name = self._title_object(idea_text, keywords)

        methods = SCHEMA_METHODS.get(schema, ["Analisis Data", "Pendekatan Inovatif", "Metode Terapan"])
        # Skema teknis: prioritaskan metode hasil mining dari judul juara (RAG)
        # supaya istilah mengikuti tren pada data, bukan daftar statis.
        if mined_methods and self._is_technical_schema(schema):
            merged: list[str] = []
            for m in list(mined_methods) + list(methods):
                if m and m not in merged:
                    merged.append(m)
            methods = merged
        patterns = SCHEMA_TITLE_PATTERNS.get(schema, MOTK_PATTERNS)
        selected_patterns = patterns[:min(len(patterns), max(top_k, 3))]

        titles = []
        for index, pattern in enumerate(selected_patterns):
            method = methods[(index + schema_index) % len(methods)]
            title = pattern.format(
                method=method,
                topic=topic,
                impact=impact,
                context=context,
                object=object_name,
                M=method,
                O=object_name,
                T=impact,
                K=context,
                tech=method,
            )
            title = self._clean_title(title)
            if title and title not in titles:
                titles.append(title)
            if len(titles) >= top_k:
                break

        while len(titles) < top_k:
            fallback = self.generate_titles(idea_text, top_k=top_k)[len(titles)]
            if fallback not in titles:
                titles.append(fallback)

        return titles[:top_k]

    def _title_topic(self, idea_text: str, keywords: list[str], schema: str = "") -> str:
        lower = idea_text.lower()
        if re.search(r"\b(hp|handphone|gadget|gawai|smartphone)\b", lower) and re.search(r"anak|remaja|siswa", lower):
            if schema == "PKM-RE":
                return "Pola Penggunaan HP pada Anak"
            if schema == "PKM-PM":
                return "Penggunaan HP Sehat"
            return "Penggunaan HP"
        if re.search(r"prediksi cuaca|cuaca|iklim|meteorologi", lower):
            return "Prediksi Cuaca Lokal"
        if re.search(r"tanaman|pertanian|padi|cabai|sawah", lower):
            return "Monitoring Pertanian Cerdas"
        if re.search(r"sampah|limbah|daur ulang", lower):
            return "Pengelolaan Sampah dan Limbah"
        if re.search(r"umkm|usaha|produk|bisnis", lower):
            return "Pengembangan Usaha Berbasis Data"

        if keywords:
            return self._humanize_phrase(keywords[0])
        return "Permasalahan Mahasiswa"

    def _title_context(self, idea_text: str, keywords: list[str]) -> str:
        lower = idea_text.lower()
        if re.search(r"\b(hp|handphone|gadget|gawai|smartphone)\b", lower) and re.search(r"anak|remaja|siswa", lower):
            return "Anak Usia Sekolah"
        if re.search(r"cuaca|iklim|meteorologi", lower):
            return "Meteorologi Lokal"
        if re.search(r"pertanian|tanaman|padi|cabai|sawah", lower):
            return "Petani Lokal"
        if re.search(r"sekolah|siswa|guru", lower):
            return "Lingkungan Sekolah"
        if re.search(r"desa|masyarakat|komunitas", lower):
            return "Masyarakat"

        return "Masyarakat"

    def _title_impact(self, idea_text: str, schema: str) -> str:
        lower = idea_text.lower()
        if re.search(r"\b(hp|handphone|gadget|gawai|smartphone)\b", lower) and re.search(r"anak|remaja|siswa", lower):
            if schema == "PKM-RE":
                return "Deteksi Risiko Kesehatan Mental Anak"
            if schema == "PKM-PM":
                return "Kesadaran Literasi Digital Keluarga"
            return "Perkembangan Anak dan Kesehatan Mental"
        if re.search(r"cuaca|iklim|meteorologi", lower):
            return "Dukungan Keputusan Berbasis Data Meteorologi"
        if re.search(r"pertanian|tanaman|padi|cabai|sawah", lower):
            return "Peningkatan Produktivitas Pertanian"
        if re.search(r"sampah|limbah|daur ulang", lower):
            return "Kesadaran Lingkungan Berkelanjutan"

        return "Penyelesaian Masalah Berbasis Data"

    def _title_object(self, idea_text: str, keywords: list[str]) -> str:
        lower = idea_text.lower()
        if re.search(r"\b(hp|handphone|gadget|gawai|smartphone)\b", lower) and re.search(r"anak|remaja|siswa", lower):
            return "Sistem Pendukung Keputusan Pemantauan Penggunaan HP pada Anak"
        if re.search(r"cuaca|iklim|meteorologi", lower):
            return self._detect_primary_object(idea_text, "Sistem Prediksi Cuaca Lokal")
        if re.search(r"tanaman|pertanian|padi|cabai|sawah", lower):
            return "Sistem Monitoring Pertanian Cerdas"
        if re.search(r"sampah|limbah|daur ulang", lower):
            return "Media Edukasi Pengelolaan Sampah"

        if keywords:
            phrase = self._humanize_phrase(keywords[0])
            return phrase if phrase.lower().startswith(("sistem", "alat", "aplikasi", "media")) else f"Sistem {phrase}"
        return "Sistem Inovatif"

    def _humanize_phrase(self, phrase: str) -> str:
        _CONJ = {"dan", "atau", "di", "ke", "dari", "untuk", "pada", "dengan", "dalam", "sebagai", "yang", "terhadap"}
        words = str(phrase).replace("_", " ").split()
        result = []
        for index, word in enumerate(words):
            lower = word.lower()
            if lower in {"hp", "iot", "ai", "ml"}:
                result.append(lower.upper())
            elif index > 0 and lower in _CONJ:
                result.append(lower)
            else:
                result.append(lower.capitalize())
        return " ".join(result)

    def _clean_title(self, title: str) -> str:
        title = re.sub(r"\s+", " ", title).strip()
        title = title.replace("Berbasis Berbasis", "Berbasis")
        title = title.replace("untuk untuk", "untuk")
        title = title.replace("pada pada", "pada")
        title = title.replace("Pola Pola", "Pola")
        title = title.replace("pada Anak pada Anak Usia Sekolah", "pada Anak Usia Sekolah")
        title = title.replace("Penggunaan HP terhadap Penggunaan HP", "Penggunaan HP")
        return title

    # â”€â”€ Entry point utama â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    def generate_titles(self, idea_text: str, top_k: int = 3) -> list[str]:
        """
        Hasilkan top_k judul PKM berkualitas via:
        RAG (FAISS semantic search) â†’ Trend Detection â†’ MMR â†’ M-O-T-K
        """
        clean_idea = preprocess_text(idea_text) or idea_text
        keywords = self._extract_keywords(clean_idea)
        logger.info(f"Keywords: {keywords}")

        trending_techs = self._detect_trending_tech(idea_text)
        logger.info(f"Trending techs terdeteksi: {trending_techs}")

        # Pilih pola secara deterministik agar input yang sama tidak berubah-ubah.
        seed = int(hashlib.sha256(clean_idea.encode("utf-8")).hexdigest()[:8], 16)
        rng = random.Random(seed)
        shuffled_patterns = rng.sample(MOTK_PATTERNS, min(top_k * 3, len(MOTK_PATTERNS)))

        # â”€â”€ Bangun pool kandidat judul â”€â”€
        # Setiap kombinasi pola Ã— variasi tech menghasilkan kandidat unik
        candidates: list[str] = []
        for i, pattern in enumerate(shuffled_patterns):
            # Rotasi tech untuk setiap pola agar tiap judul pakai teknologi berbeda
            rotated_techs = trending_techs[i % len(trending_techs):] + trending_techs[:i % len(trending_techs)]
            comps = self._parse_motk_components(keywords, rotated_techs, idea_text, i)
            title = self._build_title(pattern, comps)
            if title not in candidates:
                candidates.append(title)

        # â”€â”€ Embed ide â”€â”€
        idea_emb = np.array([embedding_service.get_embedding(clean_idea)], dtype='float32')
        faiss.normalize_L2(idea_emb)

        # â”€â”€ Embed kandidat â”€â”€
        cand_embeddings = np.array(
            embedding_service.get_embeddings(candidates), dtype='float32'
        )

        # â”€â”€ MMR: pilih top_k yang paling relevan sekaligus beragam â”€â”€
        selected_indices = self._apply_mmr(
            idea_emb.squeeze(), cand_embeddings, top_k=top_k
        )

        final_titles = [candidates[i] for i in selected_indices]
        for i, t in enumerate(final_titles):
            logger.info(f"Judul [{i+1}]: {t}")
        return final_titles

    def generate_titles_structured(self, idea_text: str, top_k: int = 3) -> list[dict]:
        """Sama seperti generate_titles namun mengembalikan komponen M-O-T-K per
        judul (untuk keluaran eksperimen Pendekatan A / Retrieval-Based). Murni
        retrieval + template mining + MMR, TANPA LLM."""
        clean_idea = preprocess_text(idea_text) or idea_text
        keywords = self._extract_keywords(clean_idea)
        trending_techs = self._detect_trending_tech(idea_text) or ["Kecerdasan Buatan"]

        seed = int(hashlib.sha256(clean_idea.encode("utf-8")).hexdigest()[:8], 16)
        rng = random.Random(seed)
        shuffled_patterns = rng.sample(MOTK_PATTERNS, min(top_k * 3, len(MOTK_PATTERNS)))

        candidates: list[str] = []
        comps_list: list[dict] = []
        for i, pattern in enumerate(shuffled_patterns):
            rotated = trending_techs[i % len(trending_techs):] + trending_techs[:i % len(trending_techs)]
            comps = self._parse_motk_components(keywords, rotated, idea_text, i)
            title = self._build_title(pattern, comps)
            if title not in candidates:
                candidates.append(title)
                comps_list.append(comps)

        idea_emb = np.array([embedding_service.get_embedding(clean_idea)], dtype='float32')
        faiss.normalize_L2(idea_emb)
        cand_embeddings = np.array(embedding_service.get_embeddings(candidates), dtype='float32')
        selected_indices = self._apply_mmr(idea_emb.squeeze(), cand_embeddings, top_k=top_k)

        results = []
        for rank, idx in enumerate(selected_indices, start=1):
            comps = comps_list[idx]
            results.append({
                "rank": rank,
                "title": candidates[idx],
                "method": comps.get("M", ""),
                "object": comps.get("O", ""),
                "goal": comps.get("T", ""),
                "context": comps.get("K", ""),
            })
        return results

    # â”€â”€ Fallback jika embedding service tidak tersedia â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    def _fallback_generate(self, keywords: list[str], top_k: int) -> list[str]:
        kw = keywords[0].title() if keywords else "Sistem Cerdas"
        ctx = keywords[1].title() if len(keywords) > 1 else "Masyarakat"
        return [
            f"Implementasi Machine Learning untuk {kw} di Bidang {ctx}",
            f"Pengembangan Sistem {kw} Berbasis Deep Learning",
            f"Rancang Bangun Aplikasi {kw} sebagai Solusi Inovatif",
        ][:top_k]


title_generator = TitleGenerator()

