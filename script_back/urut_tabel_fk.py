import pymysql
import networkx as nx
import re
from collections import defaultdict

# Konfigurasi koneksi ke database VPS
conn = pymysql.connect(
    host="89.116.171.157", user="root", password="29011989", database="namua", port=3306
)
cursor = conn.cursor()

# Ambil relasi foreign key
cursor.execute(
    """
    SELECT TABLE_NAME, REFERENCED_TABLE_NAME
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = 'namua' AND REFERENCED_TABLE_NAME IS NOT NULL
"""
)
fk_relations = cursor.fetchall()

# Ambil semua tabel
cursor.execute("SHOW TABLES")
all_tables = [row[0] for row in cursor.fetchall()]

# Buat graph dependency → arah: referensi ke dependensi
graph = nx.DiGraph()
graph.add_nodes_from(all_tables)
for table, ref_table in fk_relations:
    graph.add_edge(ref_table, table)

# Topological sort
try:
    sorted_tables = list(nx.topological_sort(graph))
except nx.NetworkXUnfeasible:
    print("❌ Terdapat siklus dalam foreign key. Tidak bisa diurutkan.")
    exit()

# Kelompokkan per prefix
prefix_groups = defaultdict(list)
for table in sorted_tables:
    match = re.match(r"^([a-zA-Z]+)_", table)
    prefix = match.group(1) if match else "lainnya"
    prefix_groups[prefix].append(table)

# Tampilkan hasil & simpan ke file
with open("urutan_tabel_per_prefix.txt", "w") as f:
    f.write("[\n")
    for prefix in sorted(prefix_groups):
        f.write(f"// Prefix: {prefix}\n")
        for table in prefix_groups[prefix]:
            f.write(f"    '{table}',\n")
    f.write("];\n")

print("✅ File berhasil dibuat: urutan_tabel_per_prefix.txt")
