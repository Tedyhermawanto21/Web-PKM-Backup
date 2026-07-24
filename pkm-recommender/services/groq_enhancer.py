import json
import logging
import re
import socket
import urllib.error
import urllib.request
from typing import Any

from core.config import settings

logger = logging.getLogger(__name__)


class GroqEnhancer:
    def __init__(self):
        self.api_key = settings.GROQ_API_KEY
        self.api_url = settings.GROQ_API_URL
        self.primary_model = settings.GROQ_PRIMARY_MODEL
        self.fallback_model = settings.GROQ_FALLBACK_MODEL
        self.timeout = settings.GROQ_TIMEOUT_SECONDS
        logger.info(f"== GROQ ENHANCER == API Key present: {bool(self.api_key)} | URL: {self.api_url}")

    def enhance(
        self,
        original_text: str,
        cleaned_text: str,
        dosen_recommendations: list[dict[str, Any]],
        historical_titles: list[dict[str, Any]],
        local_titles: list[str],
        predicted_schema: dict[str, Any],
        schema_title_groups: list[dict[str, Any]] | None = None,
    ) -> dict[str, Any]:
        if not self.api_key:
            return self._local_response(
                dosen_recommendations,
                historical_titles,
                local_titles,
                "GROQ_API_KEY belum dikonfigurasi, memakai fallback lokal.",
                schema_title_groups=schema_title_groups,
            )

        # Peran LLM = MEMOLES DIKSI saja (bukan generator judul). Model dengan
        # live-search/compound tidak dipakai karena berisiko mengubah substansi
        # dan menyalahi metodologi (Template Mining M-O-T-K + MMR tetap penghasil
        # judul). Cukup model teks standar dengan failover.
        models = [self.primary_model, "llama-3.1-8b-instant", "gemma2-9b-it", "llama3-8b-8192"]

        last_error = None
        for model in models:
            try:
                payload = self._build_payload(
                    model,
                    original_text,
                    cleaned_text,
                    dosen_recommendations,
                    historical_titles,
                    local_titles,
                    predicted_schema,
                    schema_title_groups or [],
                    use_live_search=(model == self.fallback_model),
                )
                content = self._post_chat(payload)
                parsed = self._parse_json(content)
                return self._normalize_response(
                    parsed,
                    model,
                    local_titles,
                    schema_title_groups or [],
                )
            except Exception as exc:
                last_error = exc
                logger.warning("Groq model %s failed: %s", model, exc)
                continue

        return self._local_response(
            dosen_recommendations,
            historical_titles,
            local_titles,
            "Groq tidak tersedia, memakai fallback lokal. Hasil tetap dihasilkan dari pencocokan data lokal.",
            technical_detail=str(last_error) if last_error else None,
            schema_title_groups=schema_title_groups,
        )

    def _build_payload(
        self,
        model: str,
        original_text: str,
        cleaned_text: str,
        dosen_recommendations: list[dict[str, Any]],
        historical_titles: list[dict[str, Any]],
        local_titles: list[str],
        predicted_schema: dict[str, Any],
        schema_title_groups: list[dict[str, Any]],
        use_live_search: bool,
    ) -> dict[str, Any]:
        system_prompt = (
            "Anda adalah editor bahasa ilmiah untuk judul Program Kreativitas Mahasiswa (PKM) Indonesia.\n"
            "PENTING: Judul-judul final SUDAH dihasilkan oleh modul Template Mining pola M-O-T-K + re-ranking MMR. "
            "Tugas Anda HANYA memoles diksi dan tata bahasanya agar lebih ilmiah dan enak dibaca, "
            "TANPA mengubah substansi. Anda BUKAN pembuat judul.\n\n"
            "Aturan Ketat (Polishing Diksi):\n"
            "1. DILARANG membuat judul baru, menambah, menghapus, atau menukar urutan judul. Jumlah judul keluaran HARUS sama persis dengan judul masukan.\n"
            "2. DILARANG mengganti atau menambah metode/teknologi, objek, tujuan, maupun domain yang sudah ada pada judul template. Semua istilah kunci wajib dipertahankan.\n"
            "3. Yang BOLEH: merapikan tata bahasa, menghaluskan pilihan kata, memperbaiki kapitalisasi/ejaan, dan menghilangkan pengulangan kata yang janggal.\n"
            "4. Pertahankan istilah asli mahasiswa dan istilah teknis apa adanya (mis. 'IoT', 'gas elpiji', 'lato-lato'); jangan diterjemahkan atau disingkat sepihak.\n"
            "5. Jangan menambah panjang judul secara berlebihan; poles seperlunya saja.\n"
            "6. Kembalikan JSON valid sesuai format yang ditentukan tanpa teks basa-basi pembuka atau penutup."
        )

        # Extract MMR historical titles for Few-Shot In-Context Learning
        mmr_examples = []
        for item in (historical_titles or []):
            t = item.get("title") or item.get("judul")
            if t:
                mmr_examples.append(t)
        
        examples_str = ""
        if mmr_examples:
            examples_str = "\n".join(f"{i+1}. {t}" for i, t in enumerate(mmr_examples[:2]))
        else:
            examples_str = "1. (Gunakan pola riset nasional secara adaptif)"

        # Detect the official PKM 2026 theme from original text
        detected_theme = self._detect_theme(original_text)

        # Dynamic System Prompt: Build schema-specific hints with if-else conditionals
        schema_guidance = []
        for g in (schema_title_groups or []):
            schema = g.get("schema", "").strip().upper()
            match = re.search(r'PKM-[A-Z]+', schema)
            schema_key = match.group(0) if match else schema
            
            if schema_key == "PKM-KC":
                hint = (
                    "PKM-KC (Karsa Cipta): Wajib fokus pada rancang bangun alat, sistem fungsional, software, hardware, "
                    "atau purwarupa inovatif nyata. DILARANG KERAS menggunakan kata klise 'Rancang Bangun' atau 'Sistem Deteksi' di awal judul. "
                    "Gunakan kata aksi tren modern seperti: 'Optimalisasi...', 'Akselerasi...', 'Implementasi...', 'Fabrikasi...', 'Rekayasa...'."
                )
            elif schema_key == "PKM-RSH":
                hint = (
                    "PKM-RSH (Riset Sosial Humaniora): Wajib fokus pada metode kualitatif/kuantitatif sosial, survei, analisis perilaku, "
                    "psikologi, sosiologi, atau humaniora. DILARANG KERAS menggunakan kata atau metode komputasi keras seperti "
                    "'Machine Learning', 'Deep Learning', 'Convolutional Neural Network (CNN)', 'YOLOv8', 'Arduino', 'Sensor', atau 'IoT' "
                    "ke dalam judul PKM-RSH. Gunakan kata aksi soshum modern seperti: 'Eksplorasi...', 'Mitigasi...', 'Analisis Komparatif...', 'Studi Kasus...'."
                )
            elif schema_key == "PKM-RE":
                hint = (
                    "PKM-RE (Riset Eksakta): Wajib fokus pada eksperimen laboratorium, pemodelan matematis, pengujian sains teoretis/eksakta, "
                    "atau algoritma komputasi eksakta. Boleh menyisipkan metode Machine Learning/Deep Learning jika relevan untuk analisis data eksakta."
                )
            elif schema_key == "PKM-PM":
                hint = (
                    "PKM-PM (Pengabdian Masyarakat): Wajib fokus pada program pemberdayaan masyarakat, pelatihan kelompok masyarakat, "
                    "pendampingan komunitas, literasi, atau edukasi non-profit. JANGAN gunakan metode komputasi rumit atau machine learning "
                    "dalam judul pengabdian masyarakat ini."
                )
            elif schema_key == "PKM-PI":
                hint = (
                    "PKM-PI (Penerapan Iptek): Wajib fokus pada transfer teknologi tepat guna sederhana untuk memecahkan masalah praktis "
                    "mitra profit (UMKM/toko/pabrik). Mulai dengan kata aksi peningkatan efisiensi atau produktivitas bisnis mitra."
                )
            elif schema_key == "PKM-K":
                hint = (
                    "PKM-K (Kewirausahaan): Wajib fokus pada produk inovatif komersial, layanan kreatif bernilai jual, model bisnis baru, "
                    "atau komersialisasi produk kreatif mahasiswa."
                )
            else:
                hint = f"{schema}: Sesuaikan secara ketat dengan pedoman resmi PKM 2026."
            
            schema_guidance.append(hint)

        user_prompt = {
            "instruksi_utama": [
                "1. DOSEN: Tulis alasan kecocokan 2-3 kalimat unik per dosen pendamping berdasarkan expertise mereka (bagian ini boleh Anda susun).",
                "2. JUDUL recommended_titles: POLES DIKSI setiap judul pada 'judul_template_final'. Keluarkan hasil polesan dengan JUMLAH dan URUTAN yang sama persis. Jangan mengubah substansi/metode/objek/topik dan jangan membuat judul baru.",
                "3. JUDUL schema_title_groups: POLES DIKSI setiap judul pada 'grup_judul_skema_final' per skema. Pertahankan skema, jumlah, dan urutan judul apa adanya; hanya perhalus bahasanya.",
                "4. DILARANG menambah metode/istilah teknik (misal: Machine Learning, IoT) yang belum ada pada judul template, khususnya pada skema sosial/pengabdian (PKM-RSH/PKM-PM).",
                "5. 'panduan_diksi_per_skema' dan 'contoh_struktur_judul_sukses_mmr' hanya acuan GAYA BAHASA, bukan izin mengganti isi judul.",
            ],
            "aturan_anti_halusinasi": {
                "aturan_lock_subject": "Pertahankan subjek atau istilah asli dari mahasiswa secara utuh tanpa dipotong, diubah, disingkat, atau diterjemahkan secara sepihak oleh AI (Contoh: jika mahasiswa memasukkan kata 'lato-lato', jangan diganti menjadi 'lato anak' atau 'mainan tradisional'; jika mahasiswa memasukkan kata 'gas elpiji', pertahankan frasa tersebut).",
                "aturan_substitusi_diksi": "Ganti kata kerja pasif/klise dengan kata aksi tren jurnal bereputasi (seperti: Optimalisasi, Akselerasi, Mitigasi, Implementasi, Rekayasa, atau Studi Komparatif)."
            },
            "contoh_struktur_judul_sukses_mmr": (
                "PENTING: Contoh di bawah HANYA digunakan sebagai acuan STYLISTIC (gaya penulisan ilmiah/tata bahasa), "
                f"BUKAN acuan topik substansi materi. Jangan meniru subjek medis/lainnya di bawah jika tidak relevan:\n{examples_str}"
            ),
            "tema_pkm_2026_terdeteksi": detected_theme,
            "panduan_diksi_per_skema": schema_guidance,
            "format_json_wajib": {
                "dosen_recommendations": [
                    {
                        "name": "nama dosen dari input",
                        "program_studi": "prodi dari input",
                        "match_score": 0,
                        "expertise": "keahlian utama dosen",
                        "reason": "alasan kecocokan ilmiah yang meyakinkan"
                    }
                ],
                "recommended_titles": [
                    "Judul kompetitif 1 (Aksi awal modern, no klise)",
                    "Judul kompetitif 2 (Aksi awal modern, no klise)",
                    "Judul kompetitif 3 (Aksi awal modern, no klise)"
                ],
                "schema_title_groups": [
                    {
                        "schema": "PKM-KC / PKM-RSH / PKM-RE",
                        "confidence_score": 0,
                        "reason": "mengapa skema ini sangat sesuai untuk dikembangkan",
                        "titles": [
                            "Judul skema pilihan A",
                            "Judul skema pilihan B",
                            "Judul skema pilihan C"
                        ]
                    }
                ],
                "notes": "dasar utama penentuan rekomendasi"
            },
            "data_input": {
                "abstrak_asli": original_text,
                "teks_bersih": cleaned_text,
                "prediksi_skema": predicted_schema
            },
            "dosen_untuk_dipoles": dosen_recommendations[:3],
            "judul_historis_referensi": historical_titles[:3],
            "judul_template_final": local_titles[:3],
            "grup_judul_skema_final": schema_title_groups[:3]
        }
        return {
            "model": model,
            "messages": [
                {"role": "system", "content": system_prompt},
                {"role": "user", "content": json.dumps(user_prompt, ensure_ascii=False)}
            ],
            "temperature": 0.35,
            "max_tokens": 3500,
            "response_format": {"type": "json_object"}
        }

    def _post_chat(self, payload: dict[str, Any]) -> str:
        try:
            body = self._send_payload(payload)
        except urllib.error.HTTPError as exc:
            detail = exc.read().decode("utf-8", errors="ignore")
            if exc.code == 400 and "response_format" in payload:
                retry_payload = dict(payload)
                retry_payload.pop("response_format", None)
                body = self._send_payload(retry_payload)
                return body["choices"][0]["message"]["content"]
            raise RuntimeError(f"HTTP {exc.code}: {detail[:300]}") from exc
        except (urllib.error.URLError, socket.timeout) as exc:
            raise RuntimeError(f"Network error: {exc}") from exc

        return body["choices"][0]["message"]["content"]

    def _send_payload(self, payload: dict[str, Any]) -> dict[str, Any]:
        data = json.dumps(payload).encode("utf-8")
        logger.info(f"== GROQ REQUEST == Model: {payload.get('model')} | Size: {len(data)} bytes | Content: {data[:400]}")
        request = urllib.request.Request(
            self.api_url,
            data=data,
            method="POST",
            headers={
                "Authorization": f"Bearer {self.api_key}",
                "Content-Type": "application/json",
                "Accept": "application/json",
                "User-Agent": "pkm-center-uhamka/1.0",
            },
        )
        with urllib.request.urlopen(request, timeout=self.timeout) as response:
            return json.loads(response.read().decode("utf-8"))

    def _parse_json(self, content: str) -> dict[str, Any]:
        try:
            return json.loads(content)
        except json.JSONDecodeError:
            match = re.search(r"\{.*\}", content, flags=re.DOTALL)
            if not match:
                raise
            return json.loads(match.group(0))

    def _normalize_response(
        self,
        parsed: dict[str, Any],
        model: str,
        local_titles: list[str],
        local_schema_title_groups: list[dict[str, Any]],
    ) -> dict[str, Any]:
        dosens = parsed.get("dosen_recommendations") or []
        titles = parsed.get("recommended_titles") or []

        normalized_dosens = []
        for item in dosens[:3]:
            if not isinstance(item, dict):
                continue

            normalized = dict(item)
            score = normalized.get("match_score", 0)
            if isinstance(score, (int, float)):
                normalized["match_score"] = round(score * 100, 1) if score <= 1 else round(score, 1)
            normalized_dosens.append(normalized)

        normalized_titles = self._normalize_titles(titles, local_titles)
        normalized_groups = self._normalize_schema_title_groups(
            parsed.get("schema_title_groups") or [],
            local_schema_title_groups,
            normalized_titles,
        )

        return {
            "provider": "groq",
            "model": model,
            "used_live_search": model == self.fallback_model,
            "dosen_recommendations": normalized_dosens,
            "recommended_titles": normalized_titles,
            "schema_title_groups": normalized_groups,
            "notes": parsed.get("notes") or "Rekomendasi disusun dari pencocokan lokal dan penyempurnaan LLM.",
        }

    def _normalize_schema_title_groups(
        self,
        llm_groups: list[Any],
        local_groups: list[dict[str, Any]],
        normalized_titles: list[str],
    ) -> list[dict[str, Any]]:
        if not local_groups:
            return []

        # Build clean mapping from LLM groups
        llm_by_schema = {}
        for group in llm_groups:
            if not isinstance(group, dict):
                continue
            schema = str(group.get("schema") or "").strip().upper()
            # Extract basic schema code (e.g. PKM-KC, PKM-RE)
            match = re.search(r'PKM-[A-Z]+', schema)
            schema_key = match.group(0) if match else schema
            if schema_key:
                llm_by_schema[schema_key] = group

        normalized_groups = []
        for index, local_group in enumerate(local_groups[:3]):
            group = dict(local_group)
            schema = str(group.get("schema") or "").strip().upper()
            
            # Match schema code
            match = re.search(r'PKM-[A-Z]+', schema)
            schema_key = match.group(0) if match else schema
            
            # Lookup LLM group using clean key
            llm_group = llm_by_schema.get(schema_key)

            local_titles = group.get("titles") or []
            llm_titles = llm_group.get("titles") if isinstance(llm_group, dict) else []
            
            # Normalize titles using matching LLM group
            group["titles"] = self._normalize_titles(llm_titles or [], local_titles)

            # Fallback for index 0 if both are empty
            if index == 0 and not llm_titles and normalized_titles:
                group["titles"] = self._normalize_titles(normalized_titles, local_titles)

            # Update reasons and scores if LLM group matched
            if isinstance(llm_group, dict):
                group["reason"] = llm_group.get("reason") or group.get("reason")
                group["confidence_score"] = llm_group.get("confidence_score", group.get("confidence_score"))

            normalized_groups.append(group)

        return normalized_groups

    def _normalize_titles(self, titles: list[Any], local_titles: list[str]) -> list[str]:
        """
        Judul template (hasil Template Mining M-O-T-K + MMR) adalah SUMBER
        KEBENARAN. Polesan diksi LLM hanya diterima bila mempertahankan
        substansi judul template (lihat _preserves_substance); jika tidak,
        judul template dipakai apa adanya. Jumlah & urutan mengikuti template.
        """
        normalized: list[str] = []
        seen: set[str] = set()

        base = [str(t).strip() for t in (local_titles or []) if str(t).strip()]
        for index, template_title in enumerate(base[:3]):
            llm_raw = titles[index] if index < len(titles) else ""
            polished = str(llm_raw or "").strip()
            # Bersihkan format markdown/penomoran dari keluaran LLM
            polished = re.sub(r'^[1-3\-\*\.\s\)\(]+', '', polished).strip()
            polished = polished.replace('"', '').replace("'", "")

            if polished and len(polished) >= 15 and self._preserves_substance(template_title, polished):
                final = polished
            else:
                final = template_title

            if final and final.lower() not in seen:
                seen.add(final.lower())
                normalized.append(final)

        # Lengkapi bila masih kurang dari 3, pakai judul template yang tersisa
        if len(normalized) < 3:
            for alt in base:
                if alt.lower() not in seen:
                    seen.add(alt.lower())
                    normalized.append(alt)
                if len(normalized) >= 3:
                    break

        return normalized[:3]

    # Stopword ringan untuk mengukur kesamaan substansi judul (bukan diksi).
    _ID_STOP = {
        "yang", "dan", "di", "ke", "dari", "untuk", "dengan", "pada", "dalam",
        "sebagai", "berbasis", "menggunakan", "melalui", "secara", "atau",
        "serta", "sebuah", "suatu", "sistem", "pengembangan", "rancang", "bangun",
    }

    def _content_tokens(self, text: str) -> list[str]:
        toks = re.findall(r"[a-z0-9()\-]+", str(text).lower())
        return [t for t in toks if len(t) > 2 and t not in self._ID_STOP]

    def _preserves_substance(self, template: str, polished: str) -> bool:
        """
        True bila polesan LLM masih mempertahankan substansi judul template:
        - >=50% kata kunci (content token) template tetap muncul, dan
        - panjang tidak membengkak (indikasi menulis ulang, bukan memoles).
        Ini menegakkan aturan proposal: 'LLM polishing tanpa mengubah substansi'.
        """
        t_tokens = set(self._content_tokens(template))
        if not t_tokens:
            return True
        p_tokens = set(self._content_tokens(polished))
        kept = 0
        for tok in t_tokens:
            if tok in p_tokens or any(tok in pt or pt in tok for pt in p_tokens):
                kept += 1
        overlap = kept / len(t_tokens)
        len_ratio = len(polished.split()) / max(1, len(template.split()))
        return overlap >= 0.5 and len_ratio <= 1.8

    def _looks_like_component_list(self, title: str) -> bool:
        comma_count = title.count(",")
        if comma_count < 2:
            return False

        lower = title.lower()
        has_sentence_connector = any(
            connector in lower
            for connector in (" untuk ", " pada ", " dalam ", " sebagai ", " berbasis ", " menggunakan ")
        )
        component_starters = (
            "metode ", "penerapan ", "teknologi ", "objek ", "tujuan ", "konteks ",
            "prediksi ", "deteksi ", "purwarupa ",
        )

        parts = [part.strip().lower() for part in title.split(",") if part.strip()]
        starts_like_components = sum(part.startswith(component_starters) for part in parts)

        return not has_sentence_connector or starts_like_components >= 2

    def _needs_live_trend_search(self, text: str) -> bool:
        text_lower = text.lower()
        emerging_terms = [
            "agentic", "rag", "retrieval augmented", "quantum", "federated learning",
            "edge ai", "diffusion", "blockchain", "web3", "digital twin",
            "llm", "large language model", "generative ai", "multimodal",
        ]
        return any(term in text_lower for term in emerging_terms)

    def _detect_theme(self, text: str) -> str:
        text_lower = text.lower()
        
        # Mapping 10 Tema Resmi PKM 2026 dengan keyword pendeteksi
        theme_keywords = {
            1: ["pangan", "energi", "air", "biodiesel", "bioavtur", "bioetanol", "biomassa", "energi hijau", "air bersih", "padi", "pupuk", "hidroponik", "tani", "sawah", "irigasi"],
            2: ["stunting", "gizi", "kosmetik", "herbal", "jamu", "sehat", "kesehatan", "medis", "jantung", "klinik", "rumah sakit", "aritmia", "penyakit", "obat", "darah", "stroke", "diabetes"],
            3: ["korupsi", "anggaran", "suap", "transparansi", "apbd", "apbn", "kpk", "anggaran publik", "audit", "birokrasi"],
            4: ["miskin", "kemiskinan", "dhuafa", "bansos", "sosial bantuan", "masyarakat miskin", "relatif"],
            5: ["narkoba", "narkotika", "napza", "sabu", "ganja", "psikotropika", "rehabilitasi", "kesadaran keluarga", "antidadah"],
            6: ["pendidikan", "sains", "teknologi", "sekolah", "belajar", "e-learning", "pembelajaran", "kurikulum", "guru", "siswa", "kuliah", "mahasiswa", "kampus"],
            7: ["gender", "perempuan", "anak", "disabilitas", "inklusif", "difabel", "tumbuh kembang", "hak anak", "kekerasan", "pelecehan", "difabel", "tuli", "netra"],
            8: ["lingkungan", "bencana", "zero emission", "carbon", "bioplastik", "banjir", "cuaca", "iklim", "gempa", "sampah", "polusi", "hutan", "longsor", "emisi", "daur ulang"],
            9: ["umkm", "ukm", "ikn", "ekonomi", "investasi", "usaha kecil", "koperasi", "pemerataan", "ekonomi mandiri", "ibu kota negara", "penajam paser"],
            10: ["seni", "budaya", "ekonomi kreatif", "kearifan lokal", "kriya", "tekstil", "adat", "tari", "musik tradisional", "pariwisata", "wayang", "batik", "tenun"]
        }
        
        scores = {t_id: 0 for t_id in theme_keywords.keys()}
        for t_id, keywords in theme_keywords.items():
            for kw in keywords:
                if kw in text_lower:
                    scores[t_id] += 1
                    
        best_theme_id = max(scores, key=scores.get)
        
        theme_details = {
            1: "Kemandirian Pangan, Energi, Dan Air (biodiesel, bioavtur, bioetanol, limbah biomassa, energi hijau terbarukan, air bersih)",
            2: "Kesehatan dan Gizi Masyarakat (stunting, gizi awal kehidupan, kualitas hidup, kosmetik & kesehatan herbal/jamu)",
            3: "Pencegahan dan Pemberantasan Korupsi (efisiensi anggaran, repitasi reputasi tatanan sosial, transparansi pembangunan)",
            4: "Pemberantasan Kemiskinan (menekan angka kemiskinan relatif agar di bawah 6% pada akhir tahun 2029)",
            5: "Pencegahan dan Pemberantasan Narkoba (aksi program terarah, kesadaran keluarga, pendekatan sosial)",
            6: "Penguatan Pendidikan, Sains, dan Teknologi (investasi mutu pendidikan, peningkatan daya saing bangsa)",
            7: "Penguatan Kesetaraan Gender Dan Perlindungan Hak-Hak Perempuan, Anak, dan Penyandang Disabilitas (inklusif, gizi tumbuh kembang anak)",
            8: "Pelestarian Lingkungan dan Mitigasi Bencana (zero emission, carbon footprint, water footprint, teknologi bioplastik)",
            9: "Pemerataan Ekonomi, Penguatan UMKM, dan Pembangunan Ibu Kota Negara (IKN) (ramah lingkungan, terkini, mandiri energi)",
            10: "Pelestarian Seni Budaya dan Peningkatan Ekonomi Kreatif (pemanfaatan kearifan lokal, motor penggerak ekonomi, kriya tekstil)"
        }
        
        if scores[best_theme_id] > 0:
            return f"Tema {best_theme_id}: {theme_details[best_theme_id]}"
            
        return "Umum (Dapat menyesuaikan secara fleksibel dengan 10 Tema Resmi PKM 2026)"

    def _local_response(
        self,
        dosen_recommendations: list[dict[str, Any]],
        historical_titles: list[dict[str, Any]],
        local_titles: list[str],
        note: str,
        technical_detail: str | None = None,
        schema_title_groups: list[dict[str, Any]] | None = None,
    ) -> dict[str, Any]:
        dosens = []
        for item in dosen_recommendations[:3]:
            dosen = item.get("dosen", item)
            score = item.get("similarity_score", item.get("match_score", 0))
            if isinstance(score, (int, float)) and score <= 1:
                score = round(score * 100, 1)

            name = dosen.get("name") or dosen.get("nama") or dosen.get("Nama") or "-"
            prodi = dosen.get("program_studi") or dosen.get("dept") or dosen.get("Prodi")
            expertise = dosen.get("keywords") or dosen.get("expertise") or dosen.get("Keahlian") or "bidang terkait"
            dosens.append({
                "name": name,
                "program_studi": prodi,
                "match_score": score,
                "expertise": str(expertise)[:160],
                "reason": (
                    f"{name} direkomendasikan karena profil keahliannya paling dekat "
                    f"dengan kata kunci abstrak mahasiswa. Skor kecocokan lokalnya "
                    f"{score}% sehingga layak menjadi kandidat pembimbing awal."
                ),
            })

        titles = local_titles[:3]
        if len(titles) < 3:
            titles.extend([item.get("title", "") for item in historical_titles if item.get("title")])

        return {
            "provider": "local",
            "model": None,
            "used_live_search": False,
            "dosen_recommendations": dosens,
            "recommended_titles": titles[:3],
            "schema_title_groups": schema_title_groups or [],
            "notes": note,
            "error_detail": technical_detail,
        }


groq_enhancer = GroqEnhancer()
