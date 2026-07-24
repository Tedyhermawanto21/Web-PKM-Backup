import requests

BASE_URL = "http://127.0.0.1:8001/api"

def test_recommend_dosen():
    print("--- Testing /recommend-dosen ---")
    payload = {
        "ide_proposal": "Saya ingin membuat aplikasi mobile untuk deteksi penyakit daun menggunakan kecerdasan buatan",
        "top_k": 3
    }
    response = requests.post(f"{BASE_URL}/recommend-dosen", json=payload)
    print(response.json())
    print("\n")

def test_generate_titles():
    print("--- Testing /generate-titles ---")
    payload = {
        "ide_proposal": "Membuat tempat sampah pintar menggunakan arduino yang bisa memisahkan organik dan anorganik dengan sensor",
        "top_k": 3
    }
    response = requests.post(f"{BASE_URL}/generate-titles", json=payload)
    print(response.json())
    print("\n")

def test_predict_schema():
    print("--- Testing /predict-schema ---")
    payload = {
        "ide_proposal": "Pemberdayaan ibu rumah tangga di desa sukamaju melalui pelatihan merajut berbahan limbah plastik",
        "has_partner": True,
        "partner_is_profit": False
    }
    response = requests.post(f"{BASE_URL}/predict-schema", json=payload)
    print(response.json())
    print("\n")

if __name__ == "__main__":
    print("Make sure the FastAPI server is running on port 8001\n")
    test_predict_schema()
    test_recommend_dosen()
    test_generate_titles()
