import mysql.connector

# Konfigurasi database
conn = mysql.connector.connect(
    host="127.0.0.1", user="root", password="", database="namua"
)
cursor = conn.cursor()

# Ambil semua tabel
cursor.execute("SHOW TABLES")
tables = [row[0] for row in cursor.fetchall()]

# Bangun isi file
lines = []
for table in tables:
    cursor.execute(f"SHOW COLUMNS FROM `{table}`")
    kolom = [row[0] for row in cursor.fetchall()]
    lines.append(f"{table} : {', '.join(kolom)}")

# Simpan ke file
with open("struktur_tabel_namua.txt", "w") as f:
    f.write("\n".join(lines))

cursor.close()
conn.close()
print("✅ File struktur_tabel_namua.txt berhasil dibuat.")
