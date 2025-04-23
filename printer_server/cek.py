import serial.tools.list_ports
import time


ports = serial.tools.list_ports.comports()
print("📦 Daftar perangkat terdeteksi:")
for port in ports:
    print(f"{port.device} - {port.description}")


def find_port_by_name(device_name_keyword):
    ports = serial.tools.list_ports.comports()
    for port in ports:
        if device_name_keyword.lower() in port.description.lower():
            return port.device  # misal: 'COM5'
    return None


# Coba cari port berdasarkan nama
printer_name = "RPP210A "
port_found = find_port_by_name(printer_name)

if port_found:
    print(f"✅ Ditemukan port: {port_found}")
else:
    print("❌ Port tidak ditemukan berdasarkan nama")

for port in serial.tools.list_ports.comports():
    try:
        s = serial.Serial(port.device, 9600, timeout=1)
        s.write(b"Test\n")
        s.close()
        print(f"✅ Bisa menulis ke {port.device}")
    except Exception as e:
        print(f"❌ Tidak bisa akses {port.device} - {e}")


time.sleep(10)
