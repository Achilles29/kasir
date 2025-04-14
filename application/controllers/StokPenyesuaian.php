<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StokPenyesuaian extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('StokPenyesuaian_model');
        $this->load->model('Gudang_model');
    }

public function index() {
    $this->load->model('Gudang_model');
    $this->load->model('StokPenyesuaian_model');
    // Filter Tanggal dan Pagination
    $data['title'] = 'Stok Penyesuaian';
    $tanggal_awal = $this->input->get('tanggal_awal') ?? date('Y-m-01');
    $tanggal_akhir = $this->input->get('tanggal_akhir') ?? date('Y-m-d');
    $limit = $this->input->get('limit') ?? 10; // Default 10 baris
    $page = $this->input->get('page') ?? 1;
    $start = ($page - 1) * $limit;

    // Data Stok Penyesuaian
    $data['stok_penyesuaian'] = $this->StokPenyesuaian_model->get_filtered($tanggal_awal, $tanggal_akhir, $limit, $start);
    $data['total_rows'] = $this->StokPenyesuaian_model->count_filtered($tanggal_awal, $tanggal_akhir);
    $data['stok_penyesuaian'] = $this->StokPenyesuaian_model->get_all($limit, $start);
    // Pagination
    $this->load->library('pagination');
    $config['base_url'] = base_url('stokpenyesuaian');
    $config['total_rows'] = $data['total_rows'];
    $config['per_page'] = $limit;
    $config['use_page_numbers'] = true;
    $config['query_string_segment'] = 'page';
    $config['page_query_string'] = true;
    $this->pagination->initialize($config);
    $data['pagination'] = $this->pagination->create_links();

    // Tanggal untuk filter
    $data['tanggal_awal'] = $tanggal_awal;
    $data['tanggal_akhir'] = $tanggal_akhir;
    $data['limit'] = $limit;

    $this->load->view('templates/header', $data);
    $this->load->view('stokpenyesuaian/index', $data);
    $this->load->view('templates/footer');
}

public function search_barang() {
    $keyword = $this->input->get('keyword');
    $results = $this->Gudang_model->search_barang_terbuang($keyword);

    foreach ($results as &$result) {
        $result['preview'] = "{$result['nama_barang']} - {$result['merk']} - {$result['keterangan']} - {$result['ukuran']} {$result['unit']} - Rp " . number_format($result['harga_satuan'], 0, ',', '.') . " - SISA STOK: {$result['stok_akhir']}";
    }

    echo json_encode($results);
}


public function add() {
    $tanggal = $this->input->post('tanggal') ?: date('Y-m-d'); // Default ke hari ini jika tidak ada input
    $data = [
        'tanggal' => $tanggal,
        'bl_db_purchase_id' => $this->input->post('bl_db_purchase_id'),
        'kuantitas' => $this->input->post('kuantitas'),
        'alasan' => $this->input->post('alasan')
    ];

    $this->load->model('StokPenyesuaian_model');
    $result = $this->StokPenyesuaian_model->insert($data);

    if ($result) {
        $this->load->model('Gudang_model');
        $this->Gudang_model->update_stok_penyesuaian($data['bl_db_purchase_id'], $data['kuantitas']);
        $this->session->set_flashdata('success', 'Data berhasil disimpan.');
    } else {
        $this->session->set_flashdata('error', 'Gagal menyimpan data.');
    }

    redirect('stokpenyesuaian');
}
public function update() {
    $id = $this->input->post('id');
    $data = [
        'tanggal' => $this->input->post('tanggal'),
        'kuantitas' => $this->input->post('kuantitas'),
        'alasan' => $this->input->post('alasan')
    ];

    // Ambil data stok penyesuaian sebelum diupdate
    $oldData = $this->StokPenyesuaian_model->get_by_id($id);
    if (!$oldData) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        redirect('stokpenyesuaian');
    }

    // Hitung selisih kuantitas
    $selisih = $data['kuantitas'] - $oldData['kuantitas'];

    // Update data di tabel stok penyesuaian
    $this->StokPenyesuaian_model->update($id, $data);

    // Update stok_penyesuaian di tabel gudang
    $this->Gudang_model->update_stok_penyesuaian($oldData['bl_db_purchase_id'], $selisih);

    $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
    redirect('stokpenyesuaian');
}

public function delete() {
    $id = $this->input->post('id');

    // Ambil data stok penyesuaian sebelum dihapus
    $data = $this->StokPenyesuaian_model->get_by_id($id);
    if (!$data) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        redirect('stokpenyesuaian');
    }

    // Kurangi stok_penyesuaian di tabel gudang
    $this->Gudang_model->update_stok_penyesuaian($data['bl_db_purchase_id'], -$data['kuantitas']);

    // Hapus data stok penyesuaian
    $this->StokPenyesuaian_model->delete($id);

    $this->session->set_flashdata('success', 'Data berhasil dihapus.');
    redirect('stokpenyesuaian');
}

}
