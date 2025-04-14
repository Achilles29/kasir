<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Saldo_model extends CI_Model {
    // Ambil saldo awal berdasarkan rekening dan bulan-tahun
    public function get_saldo_awal($rekening_id, $start_date) {
        $this->db->select_sum('jumlah');
        $this->db->where('bl_rekening_id', $rekening_id);
        $this->db->where('tanggal <', $start_date); // Saldo sebelum bulan-tahun yang difilter
        $query = $this->db->get('bl_kas');
        return $query->row()->jumlah ?? 0;
    }

    // Ambil transaksi harian
public function get_transaksi_harian($rekening_id, $start_date, $end_date) {
    $query = $this->db->query("
        SELECT tanggal, 
               SUM(penjualan) AS penjualan, 
               SUM(mutasi_masuk) AS mutasi_masuk, 
               SUM(mutasi_keluar) AS mutasi_keluar, 
               SUM(pembelian) AS pembelian 
        FROM (
            SELECT tanggal, penjualan, 0 AS mutasi_masuk, 0 AS mutasi_keluar, 0 AS pembelian 
            FROM bl_penjualan_majoo 
            WHERE rekening_id = ? 

            UNION ALL 

            SELECT tanggal, 0, jumlah AS mutasi_masuk, 0, 0 
            FROM bl_mutasi_kas 
            WHERE bl_rekening_id = ? AND jenis_mutasi = 'masuk'

            UNION ALL 

            SELECT tanggal, 0, 0, jumlah AS mutasi_keluar, 0 
            FROM bl_mutasi_kas 
            WHERE bl_rekening_id = ? AND jenis_mutasi = 'keluar'

            UNION ALL 

            SELECT tanggal, 0, 0, 0, total_harga AS pembelian 
            FROM bl_purchase 
            WHERE metode_pembayaran = ?
        ) AS transaksi 
        WHERE tanggal BETWEEN ? AND ? 
        GROUP BY tanggal 
        ORDER BY tanggal ASC
    ", [$rekening_id, $rekening_id, $rekening_id, $rekening_id, $start_date, $end_date]);

    return $query->result_array();
}

    // Dapatkan daftar rekening
    public function get_rekening_list() {
        return $this->db->get('bl_rekening')->result_array();
    }
}
