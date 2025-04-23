from flask import Flask, request
import serial

app = Flask(__name__)


@app.route("/cetak", methods=["POST"])
def cetak():
    data = request.json
    try:
        s = serial.Serial("COM5", 9600, timeout=1)
        s.write(b"\x1b\x40")  # Reset
        s.write((data["title"] + "\n").encode())
        s.write(f"No: {data['no_transaksi']}\n".encode())
        s.write(f"Tanggal: {data['tanggal']}\n".encode())
        s.write(b"-------------------------\n")
        for item in data["items"]:
            s.write(f"{item['nama']} x{item['qty']} @ {item['harga']:,}\n".encode())
        s.write(b"-------------------------\n")
        s.write(f"TOTAL: Rp {data['total']:,}\n".encode())
        s.write(b"\nTerima kasih!\n")
        s.write(b"\x1d\x56\x00")  # Cut paper
        s.close()
        return {"status": "sukses"}
    except Exception as e:
        return {"status": "gagal", "error": str(e)}


if __name__ == "__main__":
    app.run(port=3000)
