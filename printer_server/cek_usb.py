import usb.core
import usb.util

devices = usb.core.find(find_all=True)

for device in devices:
    print(f"Vendor ID : {hex(device.idVendor)}, Product ID : {hex(device.idProduct)}")
