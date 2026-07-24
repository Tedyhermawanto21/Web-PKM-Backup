import pandas as pd
from core.config import settings

def check_lecturers():
    try:
        df = pd.read_excel(settings.LECTURERS_FILE)
        print("Lecturers data columns:", df.columns.tolist())
        print("Lecturers shape:", df.shape)
        print("First 2 rows:")
        print(df.head(2).to_dict('records'))
    except Exception as e:
        print("Error reading lecturers:", e)

def check_winners():
    try:
        df = pd.read_csv(settings.WINNERS_FILE)
        print("Winners data columns:", df.columns.tolist())
        print("Winners shape:", df.shape)
    except Exception as e:
        print("Error reading winners:", e)

if __name__ == "__main__":
    check_lecturers()
    print("\n----------------\n")
    check_winners()
