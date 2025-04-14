<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StokTerbuang extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('StokTerbuang_model');
        $this->load->model('Gudang_model');
    }

public function index() {
    $this->load->model('Gudang_model');
    $this->load->model('StokTerbuang_model');
    // Filter Tanggal dan Pagination
    $data['title'] = 'Stok Terbuang';
    $tanggal_awal = $this->input->get('tanggal_awal') ?? date('Y-m-01');
    $tanggal_akhir = $this->input->get('tanggal_akhir') ?? date('Y-m-d');
    $limit = $this->input->get('limit') ?? 10; // Default 10 baris
    $page = $this->input->get('page') ?? 1;
    $start = ($page - 1) * $limit;
    $data['bulan'] = $this->input->get('bulan') ?? date('m');
    $data['tahun'] = $this->input->get('tahun') ?? date('Y');

    // Data Stok Terbuang
    $data['stok_terbuang'] = $this->StokTerbuang_model->get_filtered($tanggal_awal, $tanggal_akhir, $limit, $start);
    $data['total_rows'] = $this->StokTerbuang_model->count_filtered($tanggal_awal, $tanggal_akhir);
    $data['stok_terbuang'] = $this->StokTerbuang_model->get_all($limit, $start);
    // Pagination
    $this->load->library('pagination');
    $config['base_url'] = base_url('stokterbuang');
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
    $this->load->view('stokterbuang/index', $data);
    $this->load->view('templates/footer');
}

    // AJAX: Ambil data stok terbuang berdasarkan bulan & tahun
    public function get_data_by_month() {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');
        $data = $this->StokTerbuang_model->get_by_month($bulan, $tahun);
        echo json_encode($data);
    }

    // Pencarian barang berdasarkan bulan & tahun
    public function search_barang() {
        $keyword = $this->input->get('keyword');
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $results = $this->Gudang_model->search_barang_filtered($keyword, $bulan, $tahun);
        echo json_encode($results);
    }

    public function add() {
        $tanggal = $this->input->post('tanggal');
        $data = [
            'tanggal' => $tanggal,
            'bl_db_purchase_id' => $this->input->post('bl_db_purchase_id'),
            'kuantitas' => $this->input->post('kuantitas'),
            'alasan' => $this->input->post('alasan')
        ];

        if ($this->StokTerbuang_model->insert($data)) {
            // Kurangi stok terbuang di gudang
            $this->Gudang_model->update_stok_terbuang($data['bl_db_purchase_id'], $data['kuantitas']);
            echo json_encode(['success' => true, 'message' => 'Stok terbuang berhasil ditambahkan.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan stok terbuang.']);
        }
    }

    public function update() {
        $id = $this->input->post('id');
        $new_qty = $this->input->post('kuantitas');
        $oldData = $this->StokTerbuang_model->get_by_id($id);

        if (!$oldData) {
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan.']);
            return;
        }

        $selisih = $new_qty - $oldData['kuantitas'];
        $this->StokTerbuang_model->update($id, ['kuantitas' => $new_qty, 'alasan' => $this->input->post('alasan')]);
        $this->Gudang_model->update_stok_terbuang($oldData['bl_db_purchase_id'], $selisih);

        echo json_encode(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    }

    public function delete() {
        $id = $this->input->post('id');
        $data = $this->StokTerbuang_model->get_by_id($id);

        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan.']);
            return;
        }

        $this->StokTerbuang_model->delete($id);
        $this->Gudang_model->update_stok_terbuang($data['bl_db_purchase_id'], -$data['kuantitas']);

        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }

// public function search_barang() {
//     $keyword = $this->input->get('keyword');
//     $results = $this->Gudang_model->search_barang_terbuang($keyword);

//     foreach ($results as &$result) {
//         $result['preview'] = "{$result['nama_barang']} - {$result['merk']} - {$result['keterangan']} - {$result['ukuran']} {$result['unit']} - Rp " . number_format($result['harga_satuan'], 0, ',', '.') . " - SISA STOK: {$result['stok_akhir']}";
//     }

//     echo json_encode($results);
// }


// public function add() {
//     $tanggal = $this->input->post('tanggal') ?: date('Y-m-d'); // Default ke hari ini jika tidak ada input
//     $data = [
//         'tanggal' => $tanggal,
//         'bl_db_purchase_id' => $this->input->post('bl_db_purchase_id'),
//         'kuantitas' => $this->input->post('kuantitas'),
//         'alasan' => $this->input->post('alasan')
//     ];

//     $this->load->model('StokTerbuang_model');
//     $result = $this->StokTerbuang_model->insert($data);

//     if ($result) {
//         $this->load->model('Gudang_model');
//         $this->Gudang_model->update_stok_terbuang($data['bl_db_purchase_id'], $data['kuantitas']);
//         $this->session->set_flashdata('success', 'Data berhasil disimpan.');
//     } else {
//         $this->session->set_flashdata('error', 'Gagal menyimpan data.');
//     }

//     redirect('stokterbuang');
// }
// public function update() {
//     $id = $this->input->post('id');
//     $data = [
//         'tanggal' => $this->input->post('tanggal'),
//         'kuantitas' => $this->input->post('kuantitas'),
//         'alasan' => $this->input->post('alasan')
//     ];

//     // Ambil data stok terbuang sebelum diupdate
//     $oldData = $this->StokTerbuang_model->get_by_id($id);
//     if (!$oldData) {
//         $this->session->set_flashdata('error', 'Data tidak ditemukan.');
//         redirect('stokterbuang');
//     }

//     // Hitung selisih kuantitas
//     $selisih = $data['kuantitas'] - $oldData['kuantitas'];

//     // Update data di tabel stok terbuang
//     $this->StokTerbuang_model->update($id, $data);

//     // Update stok_terbuang di tabel gudang
//     $this->Gudang_model->update_stok_terbuang($oldData['bl_db_purchase_id'], $selisih);

//     $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
//     redirect('stokterbuang');
// }

// public function delete() {
//     $id = $this->input->post('id');

//     // Ambil data stok terbuang sebelum dihapus
//     $data = $this->StokTerbuang_model->get_by_id($id);
//     if (!$data) {
//         $this->session->set_flashdata('error', 'Data tidak ditemukan.');
//         redirect('stokterbuang');
//     }

//     // Kurangi stok_terbuang di tabel gudang
//     $this->Gudang_model->update_stok_terbuang($data['bl_db_purchase_id'], -$data['kuantitas']);

//     // Hapus data stok terbuang
//     $this->StokTerbuang_model->delete($id);

//     $this->session->set_flashdata('success', 'Data berhasil dihapus.');
//     redirect('stokterbuang');
// }

}
