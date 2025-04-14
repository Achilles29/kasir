<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StoreRequest extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('StoreRequest_model');
        $this->load->model('Gudang_model');
        $this->load->model('Stok_model');

    }
public function index() {
    // Default title for the page
    $data['title'] = 'Store Request';

    // Ambil filter bulan & tahun dari input (jika ada), jika tidak default ke bulan & tahun ini
    $bulan = $this->input->get('bulan') ?: date('m');
    $tahun = $this->input->get('tahun') ?: date('Y');
    $data['bulan'] = $bulan;
    $data['tahun'] = $tahun;

    // **Ambil filter tanggal awal dan akhir**
    $tanggal_awal = $this->input->get('tanggal_awal');
    $tanggal_akhir = $this->input->get('tanggal_akhir');

    // **Jika filter kosong, gunakan hari ini**
    if (!$tanggal_awal || !$tanggal_akhir) {
        $tanggal_awal = date('Y-m-d');
        $tanggal_akhir = date('Y-m-d');
    }

    $data['tanggal_awal'] = $tanggal_awal;
    $data['tanggal_akhir'] = $tanggal_akhir;

    // Ambil jenis pengeluaran dari input
    $jenis_pengeluaran = $this->input->get('jenis_pengeluaran') ?: '';
    $data['jenis_pengeluaran'] = $jenis_pengeluaran;

    // Ambil jumlah limit tampilan data
    $limit = $this->input->get('limit') ?: 9999;

    // Jika memilih "All", ambil seluruh data
    if ($limit === 'all') {
        $limit = $this->StoreRequest_model->count_all($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran);
    }

    $page = $this->input->get('page') ?: 1;
    $start = ($page - 1) * $limit;

    // **Ambil data transaksi berdasarkan rentang tanggal**
    $data['store_request'] = $this->StoreRequest_model->get_all($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran, $limit, $start);
    $data['total_rows'] = $this->StoreRequest_model->count_all($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran);

    // **Jika ada transaksi, jangan reset ke hari ini**
    if (!empty($data['store_request'])) {
        // Biarkan tanggal tetap sesuai filter yang dipilih
    } else {
        // Jika tidak ada transaksi dan belum difilter, reset ke hari ini
        if (!$this->input->get('tanggal_awal') || !$this->input->get('tanggal_akhir')) {
            $data['tanggal_awal'] = date('Y-m-d');
            $data['tanggal_akhir'] = date('Y-m-d');
        }
    }

    // Hitung total harga dari hasil filter
    $data['total_harga'] = array_sum(array_map(function($item) {
        return $item['kuantitas'] * $item['harga'];
    }, $data['store_request']));

    // **Buat pagination jika limit bukan "All"**
    if ($limit !== 'all') {
        $data['pagination'] = $this->generate_pagination(base_url('storerequest'), $data['total_rows'], $limit, $page);
    }

    $data['limit'] = $limit;
    $data['start'] = $start;

    // Ambil daftar tahun yang tersedia dalam database
    $data['tahun_list'] = $this->StoreRequest_model->get_available_years();

    // Load views
    $this->load->view('templates/header', $data);
    $this->load->view('storerequest/index', $data);
    $this->load->view('templates/footer');
}


// public function index() {
//     // Default title for the page
//     $data['title'] = 'Store Request';

//     // Get the selected filter dates
//     $tanggal_awal = $this->input->get('tanggal_awal') ?: date('Y-m-01');
//     $tanggal_akhir = $this->input->get('tanggal_akhir') ?: date('Y-m-t');
//     $data['tanggal_awal'] = $tanggal_awal;
//     $data['tanggal_akhir'] = $tanggal_akhir;

//     // Get jenis_pengeluaran filter from the request, default to all (empty)
//     $jenis_pengeluaran = $this->input->get('jenis_pengeluaran') ?: '';
//     $data['jenis_pengeluaran'] = $jenis_pengeluaran;

//     // Get the limit value from the request (including "all" for displaying all records)
//     $limit = $this->input->get('limit') ?: 10;

//     // If "All" is selected, get the total number of rows and skip pagination
//     if ($limit === 'all') {
//         $limit = $this->StoreRequest_model->count_all($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran); // Get total count
//     }

//     $page = $this->input->get('page') ?: 1;
//     $start = ($page - 1) * $limit;

//     // Get data for store request
//     $data['store_request'] = $this->StoreRequest_model->get_all($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran, $limit, $start);
//     $data['total_rows'] = $this->StoreRequest_model->count_all($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran);

//     // Calculate the total "Total Harga" based on the filtered data
//     $data['total_harga'] = array_sum(array_map(function($item) {
//         return $item['kuantitas'] * $item['harga']; // Total Harga calculation
//     }, $data['store_request']));

//     // Create pagination links only if pagination is enabled (not "All")
//     if ($limit !== 'all') {
//         $data['pagination'] = $this->generate_pagination(base_url('storerequest'), $data['total_rows'], $limit, $page);
//     }

//     $data['limit'] = $limit;
//     $data['start'] = $start;

//     // Load views
//     $this->load->view('templates/header', $data);
//     $this->load->view('storerequest/index', $data);
//     $this->load->view('templates/footer');
// }

private function generate_pagination($base_url, $total_rows, $limit, $page) {
    $this->load->library('pagination');
    $config['base_url'] = $base_url;
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $limit;
    $config['use_page_numbers'] = TRUE;
    $config['page_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';
    
    // Pagination styling
    $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul></nav>';
    $config['next_link'] = 'Next';
    $config['prev_link'] = 'Previous';
    $config['first_link'] = 'First';
    $config['last_link'] = 'Last';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close'] = '</span></li>';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['first_tag_open'] = '<li class="page-item">';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="page-item">';
    $config['last_tag_close'] = '</li>';
    
    $this->pagination->initialize($config);
    return $this->pagination->create_links();
}

public function search_barang() {
    $this->load->model('Gudang_model');

    $query = $this->input->get('query');
    $bulan = $this->input->get('bulan') ?: date('m'); // Default bulan ini
    $tahun = $this->input->get('tahun') ?: date('Y'); // Default tahun ini

    $results = $this->Gudang_model->search_barang_filtered($query, $bulan, $tahun);
    echo json_encode($results);
}


// function search_barang() {
//     $this->load->model('Gudang_model');
//     $query = $this->input->get('query');
//     $results = $this->Gudang_model->search_barang($query);
//     echo json_encode($results);
// }

public function filter_table() {
    $this->load->model('StoreRequest_model');

    $query = $this->input->get('query');

    if (!empty($query)) {
        $data = $this->StoreRequest_model->search_table($query);
    } else {
        $tanggal_awal = date('Y-m-d'); // Default tanggal awal
        $tanggal_akhir = date('Y-m-d'); // Default tanggal akhir
        $data = $this->StoreRequest_model->get_all($tanggal_awal, $tanggal_akhir, 10, 0); // Default 10 baris pertama
    }

    echo json_encode($data);
}

public function add() {
    $this->load->model('StoreRequest_model');
    $this->load->model('Gudang_model');
    $this->load->model('Stok_model');

    $data = $this->input->post();

    if (
        empty($data['tanggal']) ||
        empty($data['nama_barang']) ||
        empty($data['kuantitas']) ||
        empty($data['jenis_pengeluaran']) ||
        empty($data['bl_db_purchase_id'])
    ) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
        return;
    }

    // Ambil stok berdasarkan bulan tanggal input
    $stok_akhir = $this->Gudang_model->get_stok_akhir_per_bulan($data['bl_db_purchase_id'], $data['tanggal']);

    if ($data['kuantitas'] > $stok_akhir) {
        echo json_encode(['status' => 'error', 'message' => 'Kuantitas melebihi sisa stok.']);
        return;
    }

    // Tambahkan data ke `bl_store_request`
    $storeRequestData = [
        'tanggal' => $data['tanggal'],
        'jenis_pengeluaran' => $data['jenis_pengeluaran'],
        'bl_db_purchase_id' => $data['bl_db_purchase_id'],
        'kuantitas' => $data['kuantitas']
    ];

    $this->StoreRequest_model->insert($storeRequestData);

    // Update stok_keluar di tabel gudang berdasarkan bulan tanggal input
    $this->Gudang_model->update_stok_keluar_per_bulan($data['bl_db_purchase_id'], $data['kuantitas'], $data['tanggal']);
    $this->Gudang_model->update_stok_akhir($data['bl_db_purchase_id'], $data['tanggal']);

    $divisi_id = $this->jenis_pengeluaran_to_divisi($data['jenis_pengeluaran']);

    $this->Stok_model->update_stok_masuk_produksi(
        $bahan_id,
        $divisi_id,
        $data['tanggal'],
        $data['kuantitas'],
        0,
        'Dari Store Request'
    );

    echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan.']);
}


public function edit($id) {
    $this->load->model('Gudang_model');
    $store_request = $this->StoreRequest_model->get_by_id($id);

    if (!$store_request) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        redirect('storerequest');
    }

    // Ambil stok asli dari gudang
    $stok_gudang = $this->Gudang_model->get_stok_akhir($store_request['bl_db_purchase_id']);
    $stok_akhir = $stok_gudang + $store_request['kuantitas']; // Hitung stok akhir sebenarnya

    $data = $this->input->post();

    if ($this->input->method() === 'post') {
        if (
            empty($data['jenis_pengeluaran']) ||
            empty($data['kuantitas']) ||
            empty($store_request['bl_db_purchase_id'])
        ) {
            $this->session->set_flashdata('error', 'Harap lengkapi semua data.');
            redirect('storerequest/edit/' . $id);
        }

        // Hitung perubahan kuantitas
        $selisih_kuantitas = $data['kuantitas'] - $store_request['kuantitas'];

        if ($data['kuantitas'] > $stok_akhir) {
            $this->session->set_flashdata('error', 'Kuantitas melebihi stok akhir.');
            redirect('storerequest/edit/' . $id);
        }

        // Perbarui stok keluar di Gudang
        $this->Gudang_model->update_stok_keluar($store_request['bl_db_purchase_id'], $selisih_kuantitas);

        // Update data di bl_store_request
        $this->StoreRequest_model->update($id, [
            'jenis_pengeluaran' => $data['jenis_pengeluaran'],
            'kuantitas' => $data['kuantitas']
        ]);

        $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
        redirect('storerequest');
    }

    $data['title'] = 'Edit Store Request';
    $data['store_request'] = $store_request;
    $data['stok_akhir'] = $stok_akhir;
    $data['jenis_pengeluaran'] = $this->db->get('bl_jenis_pengeluaran')->result_array();

    $this->load->view('templates/header', $data);
    $this->load->view('storerequest/edit', $data);
    $this->load->view('templates/footer');
}

    public function delete($id) {
        $storeRequest = $this->StoreRequest_model->get_by_id($id);
        if (!$storeRequest) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('storerequest');
        }

        // Kembalikan stok_keluar dan stok_akhir
        $this->Gudang_model->reduce_stok_keluar_per_bulan(
            $storeRequest['bl_db_purchase_id'], 
            $storeRequest['kuantitas'], 
            $storeRequest['tanggal']
        );

        // Hapus store request
        $this->StoreRequest_model->delete($id);

        // Update stok akhir setelah penghapusan
        $this->Gudang_model->update_stok_akhir($storeRequest['bl_db_purchase_id'], $storeRequest['tanggal']);

        $this->Stok_model->kurangi_stok_masuk_produksi(
            $bahan_id,
            $divisi_id,
            $storeRequest['kuantitas'],
            $storeRequest['tanggal'],
            'Hapus Store Request ID #' . $id
        );

        $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        redirect('storerequest');
    }




public function laporan() {
    $this->load->model('StoreRequest_model');
    $this->load->model('JenisPengeluaran_model');

    // Ambil bulan yang difilter atau gunakan bulan saat ini
    $bulan = $this->input->get('bulan') ?: date('Y-m');
    if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
        $bulan = date('Y-m'); // Default ke bulan saat ini jika format tidak valid
    }

    // Ambil data laporan store request per tanggal dan jenis pengeluaran
    $data['laporan'] = $this->StoreRequest_model->get_laporan_store_request_per_tanggal($bulan);

    // Data untuk filter bulan
    $data['bulan'] = $bulan;

    // Ambil daftar jenis pengeluaran untuk header kolom
    $data['jenis_pengeluaran_list'] = $this->JenisPengeluaran_model->get_all();

    // Filter out jenis_pengeluaran that has no data in the report
    $valid_jenis_pengeluaran = [];
    foreach ($data['jenis_pengeluaran_list'] as $jenis) {
        foreach ($data['laporan'] as $row) {
            if ($row['nama_jenis_pengeluaran'] == $jenis['nama_jenis_pengeluaran']) {
                $valid_jenis_pengeluaran[] = $jenis;
                break;
            }
        }
    }

    $data['valid_jenis_pengeluaran'] = $valid_jenis_pengeluaran;

    // If no data for the month, show a message
    $data['is_data_empty'] = empty($data['laporan']);

    $data['title'] = 'Laporan SR';

    // Load view
    $this->load->view('templates/header', $data);
    $this->load->view('storerequest/laporan', $data);
    $this->load->view('templates/footer');
}

public function sr_umum() {
    // Default title for the page
    $data['title'] = 'Store Request Page';

    // Get the selected filter dates
    $tanggal_awal = $this->input->get('tanggal_awal') ?: date('Y-m-01');
    $tanggal_akhir = $this->input->get('tanggal_akhir') ?: date('Y-m-t');
    $data['tanggal_awal'] = $tanggal_awal;
    $data['tanggal_akhir'] = $tanggal_akhir;

    // Get jenis_pengeluaran filter from the request, default to all (empty)
    $jenis_pengeluaran = $this->input->get('jenis_pengeluaran') ?: '';
    $data['jenis_pengeluaran'] = $jenis_pengeluaran;

    // Pagination configuration
//    $limit = $this->input->get('limit') ?: 10;
    $page = $this->input->get('page') ?: 1;
//   $start = ($page - 1) * $limit;

    // Get data for store request
    $data['store_request'] = $this->StoreRequest_model->get_all_umum($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran);
    $data['total_rows'] = $this->StoreRequest_model->count_all($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran);

    // Load views
    $this->load->view('templates/header', $data);
    $this->load->view('sr_umum', $data);
    $this->load->view('templates/footer');
}
}
