from flask import Flask, request, jsonify
import serial
import mysql.connector
import logging
import sys
import time

app = Flask(__name__)
logging.basicConfig(level=logging.INFO)

# ======================
# KONFIG DATABASE MYSQL
# ======================
DB_CONFIG = {"host": "localhost", "user": "root", "password": "", "database": "namua"}

LOKASI_PRINTER = "KITCHEN"  # Lokasi printer sesuai yang ada di tabel pr_printer

# ======================
# FUNGSI BACA KONFIG PRINTER
# ======================
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
        logging.error(f"❌ Gagal koneksi database: {e}")
        sys.exit(1)

# ======================
# LOAD KONFIGURASI PRINTER
# ======================
printer_config = get_printer_config(LOKASI_PRINTER)
if not printer_config:
    logging.error(f"❌ Printer '{LOKASI_PRINTER}' tidak ditemukan di database.")
    sys.exit(1)

COM_PORT = "/dev/" + printer_config["port"]

HTTP_PORT = int(printer_config.get("python_port", 5000))

if not COM_PORT:
    logging.error("❌ Port printer tidak valid atau kosong!")
    sys.exit(1)

# ======================
# FUNGSI CETAK
# ======================
def kirim_ke_printer(text):
    try:
        printer = serial.Serial(COM_PORT, 9600, timeout=3)
        printer.write(b"\x1b\x40")  # Reset
        printer.write(text.encode('utf-8'))
        printer.write(b"\n\n\n\x1d\x56\x00")  # Potong kertas
        printer.close()
        logging.info(f"✅ Berhasil cetak ke {COM_PORT}")
        return True
    except serial.SerialException as e:
        logging.error(f"❌ Error koneksi serial: {e}")
        return False
    except Exception as e:
        logging.error(f"❌ Error saat kirim ke printer: {e}")
        return False

# ======================
# ROUTE CETAK
# ======================
@app.route("/cetak", methods=["POST"])
def cetak():
    data = request.get_json(force=True)
    struk_text = data.get("text", "")

    if not struk_text:
        return jsonify({"status": "error", "message": "Data struk kosong"}), 400

    success = kirim_ke_printer(struk_text)

    if success:
        return jsonify({"status": "success"}), 200
    else:
        return jsonify({"status": "error", "message": "Gagal cetak ke printer"}), 500

# ======================
# JALANKAN SERVER
# ======================
if __name__ == "__main__":
    print(f"🖨️ Menjalankan printer '{LOKASI_PRINTER}' di {COM_PORT} (HTTP {HTTP_PORT})")
    app.run(host="0.0.0.0", port=HTTP_PORT, debug=False)

