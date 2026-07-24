from fastapi import APIRouter
from pydantic import BaseModel
from services.dosen_matcher import dosen_matcher
import logging

router = APIRouter()
logger = logging.getLogger(__name__)

from services.title_generator import title_generator
from services.schema_predictor import schema_predictor
from services.text_preprocessor import preprocess_text
from services.groq_enhancer import groq_enhancer
from services.full_llm_recommender import generate_full_llm

class IdeaRequest(BaseModel):
    ide_proposal: str
    top_k: int = 3
    has_partner: bool = False
    partner_is_profit: bool = False


class ExperimentRequest(BaseModel):
    proposal_id: str | None = None
    idea: str = ""
    abstract: str = ""
    pkm_category: str = ""
    top_k_lecturers: int = 3
    total_titles: int = 3
    mode: str = "retrieval_based"


@router.post("/experiment/recommend")
def experiment_recommend(request: ExperimentRequest):
    """Endpoint eksperimen komparatif: mode=retrieval_based (Pendekatan A) atau
    mode=full_llm (Pendekatan B). Skema input/output sama untuk kedua mode."""
    from services.experiment_validation import validate_request
    idea = (request.idea or "").strip()
    abstract = (request.abstract or "").strip()
    mode = (request.mode or "retrieval_based").strip().lower()
    ok, err = validate_request(idea, abstract, mode)
    if not ok:
        return {"status": "error", "message": err}

    try:
        # Import lazy agar Pendekatan A tidak memuat modul LLM, dan sebaliknya.
        if mode == "retrieval_based":
            from services import retrieval_recommender
            result = retrieval_recommender.recommend(
                idea, abstract, request.pkm_category,
                request.top_k_lecturers, request.total_titles,
            )
        else:
            from services import full_llm_experiment
            result = full_llm_experiment.recommend(
                idea, abstract, request.pkm_category,
                request.top_k_lecturers, request.total_titles,
            )

        try:
            from services import experiment_logger
            experiment_logger.log_experiment(request.model_dump(), result)
        except Exception as log_exc:
            logger.error(f"Gagal menulis log eksperimen: {log_exc}")

        return {"status": "success", "data": result}
    except Exception as e:
        logger.error(f"Error in experiment_recommend: {str(e)}")
        return {"status": "error", "message": str(e)}

@router.post("/recommend-dosen")
def recommend_dosen(request: IdeaRequest):
    try:
        results = dosen_matcher.suggest_dosen(request.ide_proposal, top_k=request.top_k)
        return {"status": "success", "data": results}
    except Exception as e:
        logger.error(f"Error in recommend_dosen: {str(e)}")
        return {"status": "error", "message": str(e)}

@router.post("/recommendation")
def recommendation(request: IdeaRequest):
    try:
        cleaned_text = preprocess_text(request.ide_proposal)
        text_for_local = cleaned_text or request.ide_proposal

        params = {
            "has_partner": request.has_partner,
            "partner_is_profit": request.partner_is_profit
        }
        schema_recommendations = schema_predictor.predict_top_schemas(
            request.ide_proposal,
            additional_params=params,
            top_k=3,
        )
        schema_result = dict(schema_recommendations[0])
        schema_result["alternatives"] = schema_recommendations

        dosen_results = dosen_matcher.suggest_dosen(text_for_local, top_k=request.top_k)
        if isinstance(dosen_results, dict) and dosen_results.get("error"):
            dosen_results = []

        historical_titles = title_generator.retrieve_historical_titles(
            text_for_local,
            top_k=request.top_k,
        )
        schema_title_groups = title_generator.generate_schema_title_groups(
            request.ide_proposal,
            schema_recommendations,
            titles_per_schema=3,
        )
        local_titles = schema_title_groups[0]["titles"] if schema_title_groups else title_generator.generate_titles(
            request.ide_proposal,
            top_k=request.top_k,
        )

        llm_result = groq_enhancer.enhance(
            original_text=request.ide_proposal,
            cleaned_text=text_for_local,
            dosen_recommendations=dosen_results,
            historical_titles=historical_titles,
            local_titles=local_titles,
            predicted_schema=schema_result,
            schema_title_groups=schema_title_groups,
        )
        enhanced_schema_title_groups = llm_result.get("schema_title_groups") or schema_title_groups

        return {
            "status": "success",
            "data": {
                "input": {
                    "original_text": request.ide_proposal,
                    "cleaned_text": text_for_local,
                },
                "schema": schema_result,
                "schema_recommendations": schema_recommendations,
                "schema_title_groups": enhanced_schema_title_groups,
                "raw_dosen_recommendations": dosen_results,
                "historical_titles": historical_titles,
                "local_titles": local_titles,
                "dosen_recommendations": llm_result["dosen_recommendations"],
                "recommended_titles": llm_result["recommended_titles"],
                "llm": {
                    "provider": llm_result["provider"],
                    "model": llm_result["model"],
                    "used_live_search": llm_result["used_live_search"],
                    "notes": llm_result["notes"],
                    "error_detail": llm_result.get("error_detail"),
                },
            },
        }
    except Exception as e:
        logger.error(f"Error in recommendation: {str(e)}")
        return {"status": "error", "message": str(e)}

@router.post("/recommendation-llm")
def recommendation_llm(request: IdeaRequest):
    """Pendekatan B (Full LLM). Bila LLM tidak tersedia/limit, fallback anggun
    ke Pendekatan A (Hybrid) dengan penanda penurunan layanan."""
    try:
        result = generate_full_llm(
            request.ide_proposal,
            has_partner=request.has_partner,
            partner_is_profit=request.partner_is_profit,
        )
        if result:
            result["input"] = {
                "original_text": request.ide_proposal,
                "cleaned_text": request.ide_proposal,
            }
            return {"status": "success", "data": result}

        # Fallback anggun -> Pendekatan A
        fallback = recommendation(request)
        if isinstance(fallback, dict) and fallback.get("status") == "success":
            llm_meta = fallback["data"].setdefault("llm", {})
            llm_meta["approach"] = "B"
            llm_meta["degraded"] = True
            llm_meta["notes"] = (
                "Full LLM tidak tersedia atau kena limit. Sementara menampilkan "
                "hasil Pendekatan A (Hybrid). Silakan coba lagi nanti."
            )
        return fallback
    except Exception as e:
        logger.error(f"Error in recommendation_llm: {str(e)}")
        return {"status": "error", "message": str(e)}


@router.post("/generate-titles")
def generate_titles(request: IdeaRequest):
    try:
        results = title_generator.generate_titles(request.ide_proposal, top_k=request.top_k)
        return {"status": "success", "data": results}
    except Exception as e:
        logger.error(f"Error in generate_titles: {str(e)}")
        return {"status": "error", "message": str(e)}

@router.post("/predict-schema")
def predict_schema(request: IdeaRequest):
    try:
        params = {
            "has_partner": request.has_partner,
            "partner_is_profit": request.partner_is_profit
        }
        result = schema_predictor.predict(request.ide_proposal, additional_params=params)
        return {"status": "success", "data": result}
    except Exception as e:
        logger.error(f"Error in predict_schema: {str(e)}")
        return {"status": "error", "message": str(e)}
