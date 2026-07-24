from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from core.config import settings
from api.routes import router as api_router
import uvicorn

app = FastAPI(
    title=settings.APP_NAME,
    version=settings.APP_VERSION,
    description="Microservice AI untuk rekomendasi judul, dosen pendamping, dan skema PKM"
)

# CORS middleware to allow Laravel to communicate with this API
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"], # In production, restrict this to your Laravel URL
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

app.include_router(api_router, prefix="/api")

@app.get("/")
def root():
    return {"message": "PKM Recommender API is running!", "version": settings.APP_VERSION}

if __name__ == "__main__":
    uvicorn.run("main:app", host="127.0.0.1", port=8002, reload=False)
