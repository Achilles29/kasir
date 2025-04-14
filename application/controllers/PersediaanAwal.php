<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PersediaanAwal extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('PersediaanAwal_model');
        $this->load->model('Belanja_model');
        $this->load->model('DbPurchase_model');
        $this->load->library('pagination');
        $this->load->library('form_validation');

    }

    public function index() {
        $data['title'] = 'Persediaan Awal';

        $config['base_url'] = base_url('persediaanawal/index');
        $config['total_rows'] = $this->PersediaanAwal_model->count_all();
        $config['per_page'] = 10;
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['persediaan_awal'] = $this->PersediaanAwal_model->get_all($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('templates/header', $data);
        $this->load->view('persediaanawal/index', $data);
        $this->load->view('templates/footer');
    }

public function add() {
    // Validasi input
    $this->form_validation->set_rules('bl_db_belanja_id', 'Belanja ID', 'required|numeric');
    $this->form_validation->set_rules('bl_db_purchase_id', 'Purchase ID', 'required|numeric');
    $this->form_validation->set_rules('kuantitas', 'Kuantitas', 'required|numeric|greater_than[0]');

    if ($this->form_validation->run() === FALSE) {
        log_message('error', 'Validation Errors: ' . validation_errors());
        $this->session->set_flashdata('error', validation_errors());
        redirect('persediaanawal');
        return;
    }

    // Ambil data barang untuk perhitungan
    $purchase = $this->db->select('ukuran, harga_satuan')
                         ->where('id', $this->input->post('bl_db_purchase_id'))
                         ->get('bl_db_purchase')
                         ->row();

    if (!$purchase) {
        $this->session->set_flashdata('error', 'Data barang tidak ditemukan.');
        redirect('persediaanawal');
        return;
    }

    // Hitung total_unit, total_harga, dan hpp
    $kuantitas = $this->input->post('kuantitas');
    $total_unit = $kuantitas * $purchase->ukuran;
    $total_harga = $kuantitas * $purchase->harga_satuan;
    $hpp = $total_unit > 0 ? ($total_harga / $total_unit) : 0;

    // Data untuk disimpan
    $data = [
        'tanggal' => date('Y-m-d'),
        'bl_db_belanja_id' => $this->input->post('bl_db_belanja_id'),
        'bl_db_purchase_id' => $this->input->post('bl_db_purchase_id'),
        'kuantitas' => $kuantitas,
        'total_unit' => $total_unit,
        'total_harga' => $total_harga,
        'hpp' => $hpp,
    ];

    // Simpan data ke database
    if ($this->PersediaanAwal_model->insert($data)) {
        $this->session->set_flashdata('success', 'Data berhasil disimpan.');
    } else {
        log_message('error', 'Insert Error: ' . json_encode($this->db->error()));
        $this->session->set_flashdata('error', 'Gagal menyimpan data.');
    }

    redirect('persediaanawal');
}



public function search_barang() {
    $keyword = $this->input->get('keyword');
    
    if (empty($keyword)) {
        echo json_encode([]);
        return;
    }

    $this->db->select('
        bl_db_purchase.id AS purchase_id,
        bl_db_belanja.nama_barang,
        bl_db_belanja.nama_bahan_baku,
        bl_db_belanja.id_kategori,
        bl_db_belanja.id_tipe_produksi,
        bl_kategori.nama_kategori AS kategori,
        bl_tipe_produksi.nama_tipe_produksi AS tipe_produksi,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.pack,
        bl_db_purchase.harga_satuan
    ');
    $this->db->from('bl_db_purchase');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->like('bl_db_belanja.nama_barang', $keyword, 'both');
    $result = $this->db->get()->result_array();

    echo json_encode($result);
}



}
