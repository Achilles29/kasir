from flask import Flask, request, jsonify
import serial

app = Flask(__name__)


@app.route("/print", methods=["POST"])
def print_text():
    data = request.get_json()
    printer_name = data.get(
        "printer_name"
    )  # Contoh: "COM5" (Windows) atau "/dev/rfcomm0" (Linux)
    text = data.get("text")

    if not printer_name or not text:
        return (
            jsonify(
                {"status": "error", "message": "printer_name dan text wajib diisi"}
            ),
            400,
        )

    try:
        with serial.Serial(printer_name, 9600, timeout=1) as printer:
            printer.write(text.encode("utf-8"))
        return jsonify({"status": "success", "message": "Berhasil dikirim ke printer"})
    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)  # Jalankan di semua IP lokal, port 5000
