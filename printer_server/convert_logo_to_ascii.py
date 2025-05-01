from PIL import Image


def convert_to_ascii(image_path, output_path, width=32):
    chars = [" ", ".", ":", "-", "=", "+", "*", "#", "%", "@"]
    image = Image.open(image_path).convert("L")
    aspect_ratio = image.height / image.width
    height = int(aspect_ratio * width * 0.55)
    image = image.resize((width, height))

    ascii_str = ""
    for y in range(image.height):
        for x in range(image.width):
            pixel = image.getpixel((x, y))
            index = min(pixel // 25, len(chars) - 1)  # 🔥 fix here
            ascii_str += chars[index]
        ascii_str += "\n"

    with open(output_path, "w") as f:
        f.write(ascii_str)
    print("✅ ASCII logo saved to:", output_path)


# Jalankan fungsi
convert_to_ascii("uploads/logo.jpg", "uploads/logo_ascii.txt")
