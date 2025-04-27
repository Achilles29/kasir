<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Antrian extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        $this->load->view('antrian/index');
    }

public function cek_transaksi_baru()
{
    $tanggal_hari_ini = date('Y-m-d');

    $this->db->select_max('id');
    $this->db->where('tanggal', $tanggal_hari_ini);
    $query = $this->db->get('pr_transaksi');
    $row = $query->row();

    echo json_encode(['last_id' => $row ? $row->id : 0]);
}

    public function get_transaksi()
    {
        $tanggal_hari_ini = date('Y-m-d');

        $query = $this->db->query("
            SELECT pt.id, pt.no_transaksi, pt.nomor_meja, pt.customer, pt.created_at,
            CASE 
                WHEN SUM(CASE WHEN pdt.is_checked = 0 THEN 1 ELSE 0 END) = 0 THEN 1
                ELSE 0
            END as semua_checked
            FROM pr_transaksi pt
            JOIN pr_detail_transaksi pdt ON pdt.pr_transaksi_id = pt.id
            WHERE pt.tanggal = ?
            GROUP BY pt.id
            ORDER BY semua_checked ASC, MIN(pt.waktu_order) ASC
        ", [$tanggal_hari_ini]);

        echo json_encode($query->result());
    }

public function get_detail($transaksi_id)
{
    // Ambil semua detail produk
    $detail = $this->db->select('dt.id, p.nama_produk, dt.jumlah, dt.harga, dt.catatan, dt.is_checked, dt.status')
        ->from('pr_detail_transaksi dt')
        ->join('pr_produk p', 'p.id = dt.pr_produk_id')
        ->where('dt.pr_transaksi_id', $transaksi_id)
        ->get()
        ->result_array(); // <<< HARUS pakai result_array() supaya lebih gampang

    foreach ($detail as &$item) {
        // Ambil extra per produk
        $extra = $this->db->select('pe.nama_produk_extra, de.jumlah, de.harga, de.status')
            ->from('pr_detail_extra de')
            ->join('pr_produk_extra pe', 'pe.id = de.pr_produk_extra_id')
            ->where('de.detail_transaksi_id', $item['id'])
            ->get()
            ->result_array();

        $item['extra'] = $extra; // <<<<<< tambahkan extra ke item
    }

    echo json_encode($detail);
}


    public function set_checked($detail_transaksi_id)
    {
        $this->db->where('id', $detail_transaksi_id);
        $this->db->update('pr_detail_transaksi', ['is_checked' => 1, 'updated_at' => date('Y-m-d H:i:s')]);
        echo json_encode(['status' => true]);
    }
}