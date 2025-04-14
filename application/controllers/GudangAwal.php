<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GudangAwal extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('GudangAwal_model');
        $this->load->model('DbPurchase_model');
        $this->load->library('pagination');
    }

public function index() {
    $data['title'] = 'Gudang Awal';

    // Ambil jumlah baris per halaman dari query string atau gunakan default (10)
    $per_page = $this->input->get('per_page') ?: 10;

    // Ambil halaman saat ini dari query string
    $page = $this->input->get('page') ?: 1; // Default halaman 1 jika tidak ada parameter `page`

    // Hitung offset berdasarkan halaman
    $offset = ($page - 1) * $per_page;

    // Konfigurasi Pagination
    $config['base_url'] = base_url('gudangawal/index');
    $config['total_rows'] = $this->GudangAwal_model->count_all();
    $config['per_page'] = $per_page;
    $config['page_query_string'] = true; // Gunakan query string untuk pagination
    $config['reuse_query_string'] = true; // Pertahankan query string lainnya
    $config['query_string_segment'] = 'page'; // Gunakan `page` sebagai parameter query

    // Bootstrap Styling for Pagination
    $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul>';
    $config['attributes'] = ['class' => 'page-link'];
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';

    $this->pagination->initialize($config);

    // Data untuk View
    $data['gudang_awal'] = $this->GudangAwal_model->get_all($per_page, $offset);
    $data['pagination'] = $this->pagination->create_links();
    $data['per_page'] = $per_page;

    $this->load->view('templates/header', $data);
    $this->load->view('gudangawal/index', $data);
    $this->load->view('templates/footer');
}


public function add() {
    $post = $this->input->post();

    // Validasi apakah `bl_db_purchase_id` valid
    $purchase = $this->DbPurchase_model->get_by_id($post['bl_db_purchase_id']);
    if (!$purchase || $purchase['bl_db_belanja_id'] != $post['bl_db_belanja_id']) {
        $this->session->set_flashdata('error', 'Data barang tidak valid.');
        redirect('gudangawal');
    }

    // Hitung total_unit, total_harga, dan hpp
    $kuantitas = $post['kuantitas'];
    $total_unit = $purchase['ukuran'] * $kuantitas;
    $total_harga = $purchase['harga_satuan'] * $kuantitas;
    $hpp = $total_harga / $total_unit;

    // Data untuk disimpan ke database
    $data = [
        'tanggal' => $post['tanggal'],
        'bl_db_belanja_id' => $post['bl_db_belanja_id'],
        'bl_db_purchase_id' => $post['bl_db_purchase_id'],
        'kuantitas' => $kuantitas,
        'total_unit' => $total_unit,
        'total_harga' => $total_harga,
        'hpp' => $hpp,
    ];

    $this->GudangAwal_model->insert($data);
    $this->session->set_flashdata('success', 'Data gudang awal berhasil ditambahkan.');
    redirect('gudangawal');
}


public function search()
{
    $keyword = $this->input->get('keyword'); // Mengambil keyword dari query string

    if (empty($keyword)) {
        echo json_encode([]); // Return data kosong jika keyword kosong
        return;
    }

    // Query pencarian barang
    $this->db->select('
        bl_db_purchase.id AS purchase_id,
        bl_db_purchase.bl_db_belanja_id,
        bl_db_belanja.nama_barang,
        bl_db_belanja.nama_bahan_baku,
        bl_db_purchase.merk,
        bl_db_purchase.ukuran,
        bl_db_purchase.harga_satuan
    ');
    $this->db->from('bl_db_purchase');
    $this->db->join('bl_db_belanja', 'bl_db_belanja.id = bl_db_purchase.bl_db_belanja_id', 'left');
    $this->db->like('bl_db_belanja.nama_barang', $keyword, 'both');
    $this->db->order_by('bl_db_belanja.nama_barang', 'ASC');
    $result = $this->db->get()->result_array();

    // Return hasil pencarian dalam bentuk JSON
    echo json_encode($result);
}

public function edit($id) {
    $this->load->model('GudangAwal_model');
    $this->load->model('DbPurchase_model');
    $this->load->model('Belanja_model');

    $data['title'] = 'Edit Gudang Awal';
    $data['item'] = $this->GudangAwal_model->get_by_id($id);

    if (!$data['item']) {
        show_404(); // Tampilkan error jika data tidak ditemukan
    }

    $data['belanja'] = $this->Belanja_model->get_by_id($data['item']['bl_db_belanja_id']);
    $data['purchase'] = $this->DbPurchase_model->get_by_id($data['item']['bl_db_purchase_id']);

    $this->load->view('templates/header', $data);
    $this->load->view('gudangawal/edit', $data);
    $this->load->view('templates/footer');
}


public function update() {
    $id = $this->input->post('id');
    $tanggal = $this->input->post('tanggal');
    $bl_db_belanja_id = $this->input->post('bl_db_belanja_id');
    $bl_db_purchase_id = $this->input->post('bl_db_purchase_id');
    $kuantitas = $this->input->post('kuantitas');

    // Hitung total unit, total harga, dan HPP
    $purchase = $this->DbPurchase_model->get_by_id($bl_db_purchase_id);
    $total_unit = $kuantitas * $purchase['ukuran'];
    $total_harga = $kuantitas * $purchase['harga_satuan'];
    $hpp = $total_unit > 0 ? ($total_harga / $total_unit) : 0;

    $data = [
        'tanggal' => $tanggal,
        'bl_db_belanja_id' => $bl_db_belanja_id,
        'bl_db_purchase_id' => $bl_db_purchase_id,
        'kuantitas' => $kuantitas,
        'total_unit' => $total_unit,
        'total_harga' => $total_harga,
        'hpp' => $hpp,
    ];

    $this->GudangAwal_model->update($id, $data);

    $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
    redirect('gudangawal');
}

    public function delete($id) {
        $this->GudangAwal_model->delete($id);
        $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        redirect('gudangawal');
    }
}
