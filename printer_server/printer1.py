# printer_server.py
import socket
import threading


def handle_client(conn):
    with conn:
        data = conn.recv(4096).decode("utf-8")
        print("Menerima data cetak:\n", data)
        # TODO: kirim ke printer bluetooth di sini
        # misalnya via serial ke COM5 atau print command
        with open("log_print.txt", "a") as f:
            f.write(data + "\n---\n")


def start_server():
    server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    server.bind(("localhost", 8989))  # port bebas asal konsisten
    server.listen(5)
    print("🖨  Printer Server berjalan di port 8989...")

    while True:
        conn, addr = server.accept()
        threading.Thread(target=handle_client, args=(conn,)).start()


if __name__ == "__main__":
    start_server()
