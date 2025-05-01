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

# Tabel dengan kolom unik khusus
special_tables = {
    "bl_penjualan_produk": ["tanggal", "sku"],
    "bl_penjualan_majoo": ["tanggal", "no_nota"],
}

# Tabel yang akan dilewati (tidak di-pull)
excluded_tables = [
    "pr_customer_poin",
    "pr_detail_extra",
    "pr_detail_transaksi",
    "pr_kasir_shift",
    "pr_log_voucher",
    "pr_lokasi_printer",
    "pr_pembayaran",
    "pr_pengaturan",
    "pr_printer",
    "pr_printer_setting",
    "pr_refund",
    "pr_transaksi",
    "pr_void",
]


def connect_db(config):
    return mysql.connector.connect(**config)


def get_all_tables(cursor):
    try:
        cursor.execute("SHOW TABLES")
        rows = cursor.fetchall()
        if not rows:
            print("❌ Tidak ada tabel ditemukan.")
            return []
        return [
            list(row.values())[0] if isinstance(row, dict) else row[0] for row in rows
        ]
    except Exception as e:
        print("❌ Gagal mengambil daftar tabel:", e)
        return []


def fetch_data(cursor, table, unique_cols):
    cursor.execute(f"SELECT * FROM {table}")
    rows = cursor.fetchall()
    return {tuple(row[col] for col in unique_cols): row for row in rows}


def sync_table(table, unique_cols, cur_local, cur_vps, conn_local):
    vps_data = fetch_data(cur_vps, table, unique_cols)
    local_data = fetch_data(cur_local, table, unique_cols)

    for key, vps_row in vps_data.items():
        local_row = local_data.get(key)
        if local_row:
            if vps_row.get("updated_at") and vps_row["updated_at"] > local_row.get(
                "updated_at"
            ):
                updates = ", ".join(
                    [f"{col} = %s" for col in vps_row if col not in unique_cols]
                )
                condition = " AND ".join([f"{col} = %s" for col in unique_cols])
                sql = f"UPDATE {table} SET {updates} WHERE {condition}"
                values = [
                    vps_row[col] for col in vps_row if col not in unique_cols
                ] + list(key)
                cur_local.execute(sql, values)
        else:
            cols = ", ".join(vps_row.keys())
            placeholders = ", ".join(["%s"] * len(vps_row))
            sql = f"INSERT INTO {table} ({cols}) VALUES ({placeholders})"
            values = [vps_row[col] for col in vps_row]
            cur_local.execute(sql, values)

    conn_local.commit()
    print(f"[✓] Pull tabel {table} selesai.")


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

    tables = get_all_tables(cur_vps)
    if not tables:
        print("❌ Tidak ada tabel ditemukan.")
        return

    for table in tables:
        if table in excluded_tables:
            print(f"🚫 Lewati tabel '{table}' (excluded)")
            continue

        try:
            # Skip VIEW
            cur_vps.execute(
                f"""
                SELECT TABLE_NAME FROM INFORMATION_SCHEMA.VIEWS 
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = %s
                """,
                (table,),
            )
            if cur_vps.fetchone():
                print(f"ℹ️  Lewati VIEW '{table}'")
                continue

            cur_local.execute(f"SHOW COLUMNS FROM {table}")
            kolom = [row["Field"] for row in cur_local.fetchall()]

            if table in special_tables:
                unique_cols = special_tables[table]
            elif "id" in kolom:
                unique_cols = ["id"]
            else:
                print(f"⚠️  Lewati tabel '{table}' (tidak ada 'id' atau pengecualian)")
                continue

            sync_table(table, unique_cols, cur_local, cur_vps, conn_local)
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
    except Exception:
        print("Terjadi error:")
        traceback.print_exc()
    input("\n>> Tekan ENTER untuk keluar...")
