


TABEL POS:
pr_customer_poin : id, customer_id, transaksi_id, jumlah_poin, jenis, sumber, tanggal_kedaluwarsa, status, created_at, updated_at
pr_detail_extra : id, detail_transaksi_id, pr_produk_extra_id, jumlah, harga, subtotal, sku, satuan, hpp, created_at, updated_at, status
pr_detail_transaksi : id, pr_transaksi_id, pr_produk_id, detail_unit_id, jumlah, harga, catatan, is_printed, status, created_at, updated_at, is_checked
pr_kasir_shift : id, kasir_id, modal_awal, waktu_mulai, total_penjualan, total_pending, modal_akhir, selisih, waktu_tutup, total_pendapatan, keterangan, status, created_at, updated_at, transaksi_selesai, transaksi_pending
pr_log_voucher : id, voucher_id, transaksi_id, detail_transaksi_id, customer_id, kode_voucher, jumlah_diskon, sisa_voucher, created_at, updated_at
pr_voucher : id, kode_voucher, jenis, nilai, min_pembelian, produk_id, jumlah_gratis, max_diskon, maksimal_voucher, sisa_voucher, status, tanggal_mulai, tanggal_berakhir, created_at, updated_at, pr_transaksi_id, customer_idpr_pembayaran : id, transaksi_id, metode_id, jumlah, waktu_bayar, keterangan, kasir_id, created_at, updated_at
pr_refund : id, kode_refund, pr_transaksi_id, no_transaksi, pr_detail_transaksi_id, pr_produk_id, nama_produk, detail_extra_id, produk_extra_id, nama_extra, jumlah, harga, catatan, alasan, refund_by, metode_pembayaran_id, waktu_refund, created_at, updated_at
pr_transaksi : id, customer_id, tanggal, no_transaksi, waktu_order, waktu_bayar, jenis_order_id, customer, nomor_meja, total_penjualan, kasir_order, kasir_bayar, kode_voucher, diskon, total_pembayaran, sisa_pembayaran, status_pembayaran, created_at, updated_at
pr_void : id, kode_void, pr_transaksi_id, no_transaksi, detail_transaksi_id, pr_produk_id, nama_produk, detail_extra_id, produk_extra_id, nama_extra, jumlah, harga, catatan, alasan, void_by, waktu, created_at, updated_at, is_printed




pr_promo_stamp: id, nama_promo, deskripsi, minimal_pembelian, berlaku_kelipatan, produk_berlaku, total_stamp_target, hadiah, masa_berlaku_hari, aktif created_at, updated_at 

pr_customer_stamp : id, pr_transaksi_id, customer_id , promo_stamp_id, jumlah_stamp, last_stamp_at, masa_berlaku, status ENUM('aktif','kadaluarsa','ditukar') DEFAULT 'aktif', created_at ,updated_at 

pr_stamp_log: id, customer_id , promo_stamp_id, transaksi_id, jenis ENUM('tambah','tukar') DEFAULT 'tambah', jumlah, keterangan,created_at, updated_at 



Buat versi untuk update data?

Tambahkan sinkronisasi terjadwal?




sync_log berkala:
http://localhost/kasir/retry_sync

http://localhost/kasir/sync_data/ambil_semua

buat di cron
*/5 * * * * /usr/bin/php /path/to/index.php retry_sync


cron status poin di local dan vps
0 0 * * * /usr/bin/curl https://yourdomain.com/customer/update_poin_status

http://localhost/kasir/customer/update_poin_status

cron status stamp di local dan vps

http://localhost/kasir/stamp/kadaluarsa_stamp => belum berfungsi


https://dashboard.namuacoffee.com/stamp/kadaluarsa_stamp => online

https://dashboard.namuacoffee.com/voucher/voucher_nonaktif

https://dashboard.namuacoffee.com/customer/update_poin_status
