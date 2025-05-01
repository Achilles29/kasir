import traceback
import mysql.connector
from datetime import datetime

# KONFIGURASI
config_local = {
    "host": "127.0.0.1",
    "user": "root",
    "password": "",
    "database": "namua",
}

config_vps = {
    "host": "89.116.171.157",
    "user": "root",
    "password": "29011989",
    "database": "namua",
}

special_tables = {
    "bl_penjualan_produk": ["tanggal", "sku"],
    "bl_penjualan_majoo": ["tanggal", "no_nota"],
}


def connect_db(config):
    return mysql.connector.connect(**config)


def get_all_tables(cursor):
    try:
        cursor.execute("SHOW TABLES LIKE 'pr_%'")
        rows = cursor.fetchall()
        if not rows:
            print("❌ Tidak ada tabel 'pr_' yang ditemukan.")
            return []
        return [
            list(row.values())[0] if isinstance(row, dict) else row[0] for row in rows
        ]
    except Exception as e:
        print("❌ Gagal mengambil daftar tabel:", e)
        return []


def fetch_data(cursor, table, unique_cols):
    cursor.execute(f"SELECT * FROM `{table}`")
    rows = cursor.fetchall()
    return {tuple(row[col] for col in unique_cols): row for row in rows}


def sync_table(table, unique_cols, cur_source, cur_target, conn_target):
    source_data = fetch_data(cur_source, table, unique_cols)
    target_data = fetch_data(cur_target, table, unique_cols)

    for key, src_row in source_data.items():
        tgt_row = target_data.get(key)
        if tgt_row:
            if src_row["updated_at"] > tgt_row["updated_at"]:
                updates = ", ".join(
                    [f"{col} = %s" for col in src_row if col not in unique_cols]
                )
                condition = " AND ".join([f"{col} = %s" for col in unique_cols])
                sql = f"UPDATE `{table}` SET {updates} WHERE {condition}"
                values = [
                    src_row[col] for col in src_row if col not in unique_cols
                ] + list(key)
                cur_target.execute(sql, values)
        else:
            cols = ", ".join(src_row.keys())
            placeholders = ", ".join(["%s"] * len(src_row))
            sql = f"INSERT INTO `{table}` ({cols}) VALUES ({placeholders})"
            values = [src_row[col] for col in src_row]
            cur_target.execute(sql, values)
    conn_target.commit()
    print(f"[✓] Sinkronisasi ke VPS untuk tabel `{table}` selesai.")


def run_sync():
    try:
        conn_local = connect_db(config_local)
        conn_vps = connect_db(config_vps)
        cur_local = conn_local.cursor(dictionary=True)
        cur_vps = conn_vps.cursor(dictionary=True)
    except Exception as e:
        print("❌ Gagal koneksi ke database:", e)
        input(">> Tekan ENTER untuk keluar...")
        return

    tables = get_all_tables(cur_local)
    if not tables:
        print("❌ Tidak ada tabel ditemukan.")
        return

    for table in tables:
        cur_local.execute(f"SHOW COLUMNS FROM `{table}`")
        kolom = [row["Field"] for row in cur_local.fetchall()]

        if table in special_tables:
            unique_cols = special_tables[table]
        elif "id" in kolom:
            unique_cols = ["id"]
        else:
            print(f"⚠️  Lewati tabel '{table}' (tidak ada 'id' atau pengecualian)")
            continue

        try:
            sync_table(table, unique_cols, cur_local, cur_vps, conn_vps)
        except Exception as e:
            print(f"❌ Error saat sinkronisasi tabel '{table}': {e}")
            continue

    cur_local.close()
    cur_vps.close()
    conn_local.close()
    conn_vps.close()


if __name__ == "__main__":
    try:
        run_sync()
    except Exception as e:
        print("Terjadi error:")
        traceback.print_exc()
    input("\n>> Tekan ENTER untuk keluar...")
