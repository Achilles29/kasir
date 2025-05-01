from flask import Flask, request, jsonify
import subprocess
import bluetooth

app = Flask(__name__)


# Endpoint untuk scan perangkat Bluetooth
@app.route("/scan", methods=["GET"])
def scan():
    try:
        devices = bluetooth.discover_devices(duration=8, lookup_names=True)
        results = [{"name": name, "address": addr} for addr, name in devices]
        return jsonify(results)
    except Exception as e:
        return jsonify({"error": str(e)}), 500


# Endpoint untuk mencetak
@app.route("/print", methods=["POST"])
def print_text():
    data = request.json
    address = data.get("address")
    text = data.get("text")

    if not address or not text:
        return (
            jsonify({"status": "error", "message": "Alamat atau isi cetakan kosong"}),
            400,
        )

    try:
        # Kirim data ke perangkat via rfcomm
        process = subprocess.Popen(
            ["rfcomm", "connect", "hci0", address, "1"], stdin=subprocess.PIPE
        )
        process.communicate(input=text.encode())
        return jsonify({"status": "success", "message": "Berhasil dikirim ke printer"})
    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)
