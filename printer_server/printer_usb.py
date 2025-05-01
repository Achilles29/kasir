from escpos.printer import Usb
import six

# Ganti Vendor ID dan Product ID sesuai printer kamu
VENDOR_ID = 0x416  # contoh: EPSON
PRODUCT_ID = 0x0511  # contoh: EPSON TM-T20

# Buat koneksi ke printer
try:
    printer = Usb(VENDOR_ID, PRODUCT_ID)

    # Tes cetak
    printer.text("N A M U A   C O F F E E\n")
    printer.text("Jl. Senja No. 12\n")
    printer.text("Tanggal: 2025-04-13\n")
    printer.text("------------------------------\n")
    printer.text("Espresso       x1    20.000\n")
    printer.text("Cappuccino     x2    50.000\n")
    printer.text("------------------------------\n")
    printer.text("TOTAL:              Rp 70.000\n")
    printer.text("\nTerima kasih!\n\n\n")
    printer.cut()

    print("✅ Cetak berhasil via USB!")
except Exception as e:
    print(f"❌ Gagal cetak: {e}")
