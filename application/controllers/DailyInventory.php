<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DailyInventory extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('DailyInventory_model');
        $this->load->model('DbPurchase_model'); // Model bl_purchase
        $this->load->library('pagination'); // Pastikan library pagination dimuat
    }
public function index() {
    $data['title'] = 'Daily Inventory';

    $this->db->select('
        bl_daily_inventory.*,
        bl_purchase.tanggal_pembelian,
        bl_db_belanja.nama_barang,
        bl_db_belanja.nama_bahan_baku,
        bl_kategori.nama_kategori,
        bl_tipe_produksi.nama_tipe_produksi,
        bl_purchase.merk,
        bl_purchase.keterangan,
        bl_purchase.ukuran,
        bl_purchase.unit,
        bl_purchase.pack,
        bl_purchase.harga_satuan,
        bl_purchase.hpp
    ');
    $this->db->from('bl_daily_inventory');
    $this->db->join('bl_purchase', 'bl_purchase.id = bl_daily_inventory.bl_purchase_id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_belanja.id = bl_purchase.bl_db_belanja_id', 'left');
    $this->db->join('bl_kategori', 'bl_kategori.id = bl_db_belanja.id_kategori', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_tipe_produksi.id = bl_db_belanja.id_tipe_produksi', 'left');
    $data['inventory'] = $this->db->get()->result_array();

    $this->load->view('templates/header', $data);
    $this->load->view('dailyinventory/index', $data);
    $this->load->view('templates/footer');
}


    public function sync_storeroom() {
        // Ambil data dari `bl_purchase` dengan `jenis_pengeluaran = STOREROOM`
        $storeroom_purchases = $this->DbPurchase_model->get_by_jenis_pengeluaran('STOREROOM');

        foreach ($storeroom_purchases as $purchase) {
            $data = [
                'tanggal_pembelian' => $purchase['tanggal'],
                'bl_purchase_id' => $purchase['id']
            ];
            $this->DailyInventory_model->insert($data);
        }

        $this->session->set_flashdata('success', 'Data storeroom berhasil disinkronkan.');
        redirect('dailyinventory');
    }
}
