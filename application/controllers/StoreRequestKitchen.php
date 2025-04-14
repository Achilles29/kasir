<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StoreRequestKitchen extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('StoreRequestKitchen_model');
        $this->load->model('Gudang_model');
        $this->load->library('pagination');
    }

    public function index() {
        $data['title'] = 'Store Request Kitchen';

        // Ambil tanggal default (hari ini)
        $tanggal_awal = $this->input->get('tanggal_awal') ?: date('Y-m-d');
        $tanggal_akhir = $this->input->get('tanggal_akhir') ?: date('Y-m-d');
        $per_page = $this->input->get('per_page') ?: 10;

        // Konfigurasi Pagination
        $config['base_url'] = site_url('storerequestkitchen/index');
        $config['total_rows'] = $this->StoreRequestKitchen_model->count_filtered($tanggal_awal, $tanggal_akhir);
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = true;
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // Ambil data
        $data['store_requests'] = $this->StoreRequestKitchen_model->get_filtered($tanggal_awal, $tanggal_akhir, $per_page, $page);
        $data['pagination'] = $this->pagination->create_links();
        $data['tanggal_awal'] = $tanggal_awal;
        $data['tanggal_akhir'] = $tanggal_akhir;
        $data['per_page'] = $per_page;

        $this->load->view('templates/header', $data);
        $this->load->view('storerequestkitchen/index', $data);
        $this->load->view('templates/footer');
    }
public function filter_table() {
    $query = $this->input->get('query');

    // Pastikan model dimuat
    $this->load->model('StoreRequestKitchen_model');

    // Ambil data berdasarkan pencarian
    $data = $this->StoreRequestKitchen_model->search_table($query);

    // Kembalikan data dalam format JSON
    echo json_encode($data);
}



public function search_barang() {
    $keyword = $this->input->get('keyword');
    $results = $this->Gudang_model->search_barang_ajax($keyword);

    // Format data untuk ditampilkan di pencarian AJAX
    foreach ($results as &$result) {
        $result['preview'] = "{$result['nama_barang']} - {$result['merk']} - {$result['keterangan']} - {$result['ukuran']} {$result['unit']} - Rp " . number_format($result['harga_satuan'], 0, ',', '.') . " - SISA STOK: " . ($result['stok_akhir'] ?? 0);
    }
    echo json_encode($results);
}

    public function add() {
        $data = [
            'tanggal' => $this->input->post('tanggal'),
            'jenis_pengeluaran' => 'KITCHEN',
            'bl_db_purchase_id' => $this->input->post('bl_db_purchase_id'),
            'kuantitas' => $this->input->post('kuantitas'),
            'catatan' => $this->input->post('catatan'),
            'status' => 'pending',
        ];

        $this->StoreRequestKitchen_model->insert($data);

        // Update stok keluar di tabel bl_gudang
        // $this->Gudang_model->update_stok_keluar($data['bl_db_purchase_id'], $data['kuantitas']);

        $this->session->set_flashdata('success', 'Store Request berhasil ditambahkan.');
        redirect('storerequestkitchen');
    }
public function edit($id) {
    $this->load->model('StoreRequestKitchen_model');
    $this->load->model('Gudang_model');

    $data['title'] = 'Edit Store Request Kitchen';
    $data['request'] = $this->StoreRequestKitchen_model->get_by_id($id);

    if (!$data['request']) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        redirect('storerequestkitchen');
    }

    // Ambil sisa stok dari Gudang
    $data['sisa_stok'] = $this->Gudang_model->get_stok_akhir($data['request']['bl_db_purchase_id']);

    $this->load->view('templates/header', $data);
    $this->load->view('storerequestkitchen/edit', $data);
    $this->load->view('templates/footer');
}

public function update() {
    $id = $this->input->post('id');
    $data = [
        'tanggal' => $this->input->post('tanggal'),
        'kuantitas' => $this->input->post('kuantitas'),
        'catatan' => $this->input->post('catatan'),
        'status' => $this->input->post('status')
    ];

    if ($this->StoreRequestKitchen_model->update($id, $data)) {
        $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
    } else {
        $this->session->set_flashdata('error', 'Gagal memperbarui data.');
    }

    redirect('storerequestkitchen');
}

public function verify($id) {
    $this->load->model('StoreRequestKitchen_model');
    $this->load->model('Gudang_model');
    $this->load->model('StoreRequest_model');

    // Ambil data dari StoreRequestKitchen
    $requestKitchen = $this->StoreRequestKitchen_model->get_by_id($id);

    if (!$requestKitchen) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        redirect('storerequestkitchen');
    }

    // Ambil sisa stok dari Gudang
    $stok_akhir = $this->Gudang_model->get_stok_akhir($requestKitchen['bl_db_purchase_id']);

    // Validasi jika kuantitas melebihi stok
    if ($requestKitchen['kuantitas'] > $stok_akhir) {
        $this->session->set_flashdata('error', 'Kuantitas melebihi sisa stok.');
        redirect('storerequestkitchen');
    }

    // Simpan ke tabel bl_store_request
    $storeRequestData = [
        'tanggal' => $requestKitchen['tanggal'],
        'jenis_pengeluaran' => 3, // ID untuk Kitchen
        'bl_db_purchase_id' => $requestKitchen['bl_db_purchase_id'],
        'kuantitas' => $requestKitchen['kuantitas'],
    ];

    // Masukkan data ke tabel bl_store_request
    $this->StoreRequest_model->insert($storeRequestData);

    // Kurangi stok keluar dan perbarui stok akhir
    $this->Gudang_model->update_stok_keluar($requestKitchen['bl_db_purchase_id'], $requestKitchen['kuantitas']);

    // Update status di tabel bl_store_request_kitchen
    $this->StoreRequestKitchen_model->update_status($id, 'verified');

    $this->session->set_flashdata('success', 'Data berhasil diverifikasi.');
    redirect('storerequestkitchen');
}



public function delete($id) {
    if ($this->StoreRequestKitchen_model->delete($id)) {
        $this->session->set_flashdata('success', 'Data berhasil dihapus.');
    } else {
        $this->session->set_flashdata('error', 'Gagal menghapus data.');
    }

    redirect('storerequestkitchen');
}

}
