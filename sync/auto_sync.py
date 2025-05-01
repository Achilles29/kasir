import time
from datetime import datetime
import traceback

# Import fungsi dari file lain jika kamu pisah, atau paste isinya langsung di sini
from pull_from_vps import run_sync as pull_sync
from push_to_vps import run_sync as push_sync

while True:
    try:
        print(f"[{datetime.now()}] Mulai PULL dari VPS...")
        pull_sync()
        print(f"[{datetime.now()}] PULL selesai.\n")

        print(f"[{datetime.now()}] Mulai PUSH ke VPS...")
        push_sync()
        print(f"[{datetime.now()}] PUSH selesai.\n")

    except Exception:
        print(f"[{datetime.now()}] ❌ ERROR:")
        traceback.print_exc()

    # Tunggu 5 menit sebelum sync berikutnya
    print(f"[{datetime.now()}] Menunggu 3 menit...\n")
    time.sleep(180)
