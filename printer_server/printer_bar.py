from flask import Flask, request
import serial
import mysql.connector
import logging
import sys

app = Flask(__name__)
logging.basicConfig(level=logging.INFO)

# ======================
# KONFIG DATABASE MYSQL
# ======================
DB_CONFIG = {"host": "localhost", "user": "root", "password": "", "database": "namua"}

LOKASI_PRINTER = "BAR"  # Ini ditentukan nama printer-nya di DB


# BACA KONFIGURASI PRINTER DARI DATABASE
def get_printer_config(lokasi_printer):
    try:
        conn = mysql.connector.connect(**DB_CONFIG)
        cursor = conn.cursor(dictionary=True)
        cursor.execute(
            "SELECT port, python_port FROM pr_printer WHERE lokasi_printer = %s",
            (lokasi_printer,),
        )
        result = cursor.fetchone()
        cursor.close()
        conn.close()
        return result
    except Exception as e:
        logging.error(f"❌ Gagal konek ke DB: {e}")
        sys.exit(1)


# ======================
# AMBIL KONFIGURASI
# ======================
printer_config = get_printer_config(LOKASI_PRINTER)
if not printer_config:
    logging.error(f"❌ Printer '{LOKASI_PRINTER}' tidak ditemukan di database.")
    sys.exit(1)

COM_PORT = printer_config["port"]
HTTP_PORT = int(printer_config["python_port"])


# ======================
# ROUTE CETAK
# ======================
@app.route("/cetak", methods=["POST"])
def cetak():
    try:
        data = request.get_json(force=True)
        struk_text = data.get("text", "")

        if not struk_text:
            return {"status": "error", "message": "Data struk kosong"}

        printer = serial.Serial(COM_PORT, 9600, timeout=1)
        printer.write(b"\x1b\x40")  # reset
        printer.write(struk_text.encode("utf-8"))
        printer.write(b"\n\n\n\x1d\x56\x00")  # potong kertas
        printer.close()

        logging.info(f"✅ Struk dikirim ke {COM_PORT}")
        return {"status": "success"}
    except Exception as e:
        logging.error(f"❌ Gagal cetak: {e}")
        return {"status": "error", "message": str(e)}


# ======================
# JALANKAN FLASK
# ======================
if __name__ == "__main__":
    print(f"🖨️ Menjalankan printer '{LOKASI_PRINTER}' di {COM_PORT} (HTTP {HTTP_PORT})")
    app.run(host="0.0.0.0", port=HTTP_PORT, debug=True)
