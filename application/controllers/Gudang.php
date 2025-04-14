<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Gudang_model');
    }

public function index() {
    $this->load->model('Gudang_model');

    $data['title'] = ' Gudang';

    // Filter bulan dan tahun
    $data['month'] = $this->input->get('month') ?: date('m');
    $data['year'] = $this->input->get('year') ?: date('Y');

    // Sortir kolom
    $data['sort_1'] = $this->input->get('sort_1') ?: 'kategori';
    $data['sort_2'] = $this->input->get('sort_2') ?: 'nama_barang';
    $data['sort_3'] = $this->input->get('sort_3') ?: 'nama_bahan_baku';
    $data['sort_4'] = $this->input->get('sort_4') ?: 'tipe';

    $sort_criteria = [
        $data['sort_1'] => 'ASC',
        $data['sort_2'] => 'ASC',
        $data['sort_3'] => 'ASC',
        $data['sort_4'] => 'ASC',
    ];

    // Pagination
    $limit = $this->input->get('limit') ?: 50;
        // If the user selects "Tampil Semua", set limit to a very large number
    if ($limit == 'all') {
        $limit = 999999;  // or any large number that suits your case
    }

    $page = $this->input->get('page') ?: 1;
    $start = ($page - 1) * $limit;
 

    // Ambil data gudang
    $data['gudang'] = $this->Gudang_model->get_all($data['month'], $data['year'], $limit, $start, $sort_criteria);
#    $total_rows = $this->Gudang_model->count_all($data['month'], $data['year']);
    // Ensure data exists before proceeding
    if (empty($data['gudang'])) {
        $data['gudang'] = [];  // Ensure we pass an empty array if no data found
    }
        // Count total rows for pagination
    $total_rows = $this->Gudang_model->count_all($data['month'], $data['year']);

    // Konfigurasi pagination
    $this->load->library('pagination');
    $config['base_url'] = base_url('gudang/index?month=' . $data['month'] . '&year=' . $data['year'] . '&limit=' . $limit);
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $limit;
    $config['use_page_numbers'] = true;
    $config['page_query_string'] = true;
    $config['query_string_segment'] = 'page';
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

    $data['pagination'] = $this->pagination->create_links();
    $data['limit'] = $limit;
    $data['start'] = $start; // Kirimkan offset ke view

    // Calculate total nilai_total
    $total_nilai_total = 0;
    foreach ($data['gudang'] as $item) {
        $total_nilai_total += $item['stok_akhir'] * $item['harga'];
    }

    // Pass the total to the view
    $data['total_nilai_total'] = $total_nilai_total;
    // Load view
    $this->load->view('templates/header', $data);
    $this->load->view('gudang/index', $data);
    $this->load->view('templates/footer');
}
public function save() {
    $data = $this->input->post();

    $insert_data = [
        'bl_db_belanja_id' => $data['bl_db_belanja_id'],
        'bl_db_purchase_id' => $data['bl_db_purchase_id'],
        'stok_awal' => $data['stok_awal'],
        'stok_masuk' => $data['stok_masuk'],
        'stok_keluar' => $data['stok_keluar'],
        'stok_terbuang' => $data['stok_terbuang'],
        'stok_penyesuaian' => $data['stok_penyesuaian'],
        'tanggal' => $data['tanggal'],
    ];

    if (empty($data['id'])) {
        $this->Gudang_model->insert($insert_data);
    } else {
        $this->Gudang_model->update($data['id'], $insert_data);
    }

    $this->session->set_flashdata('success', 'Data berhasil disimpan.');
    redirect('gudang');
}

public function update($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('bl_gudang', $data);
}

public function generate_stok_awal() {
    $this->load->model('Gudang_model');
    $this->load->model('StokOpname_model');

    try {
        // Ambil tanggal yang dipilih oleh pengguna atau gunakan default bulan ini
        $tanggal_generate = $this->input->get('tanggal_generate') ?: date('Y-m-d');

        // Pisahkan tanggal untuk mendapatkan bulan dan tahun sebelumnya
        $bulan_sekarang = date('m', strtotime($tanggal_generate));
        $tahun_sekarang = date('Y', strtotime($tanggal_generate));
        
        // Hitung bulan sebelumnya dan tahun sebelumnya jika bulan adalah Januari
        $bulan_sebelumnya = ($bulan_sekarang - 1 == 0) ? 12 : $bulan_sekarang - 1;
        $tahun_sebelumnya = ($bulan_sebelumnya == 12) ? $tahun_sekarang - 1 : $tahun_sekarang;

        // Ambil data dari `bl_stok_opname` bulan sebelumnya
        $stok_opname = $this->StokOpname_model->get_by_month_year($bulan_sebelumnya, $tahun_sebelumnya);

        // Jika tidak ada data stok opname, hentikan proses
        if (empty($stok_opname)) {
            echo json_encode(['status' => 'error', 'message' => 'Data stok opname bulan sebelumnya tidak ditemukan.']);
            return;
        }

        // Menyiapkan data stok awal
        $stok_awal_data = [];
        foreach ($stok_opname as $item) {
            $key = $item['bl_db_belanja_id'] . '-' . $item['bl_db_purchase_id'];
            $stok_awal_data[$key] = [
                'bl_db_belanja_id' => $item['bl_db_belanja_id'],
                'bl_db_purchase_id' => $item['bl_db_purchase_id'],
                'stok_awal' => $item['stok_akhir'], // Stok awal berasal dari stok akhir bulan sebelumnya
                'stok_masuk' => 0,
                'stok_keluar' => 0,
                'stok_terbuang' => 0,
                'stok_penyesuaian' => 0,
                'stok_akhir' => $item['stok_akhir'], // Stok akhir disamakan dengan stok awal
                'tanggal' => $tanggal_generate, // Gunakan tanggal yang dipilih
            ];
        }

        // Masukkan atau perbarui data di `bl_gudang`
        foreach ($stok_awal_data as $item) {
            // Cek apakah sudah ada data dengan bl_db_purchase_id dan tanggal yang sama
            $existing = $this->Gudang_model->get_by_purchase_and_date($item['bl_db_purchase_id'], $tanggal_generate);

            if ($existing) {
                // Jika data sudah ada, update tanpa mengubah stok awal
                $this->Gudang_model->update($existing['id'], [
                    'stok_masuk' => 0,
                    'stok_keluar' => 0,
                    'stok_terbuang' => 0,
                    'stok_penyesuaian' => 0,
                    'stok_akhir' => $existing['stok_awal'], // Pastikan stok akhir tetap sama dengan stok awal
                    'tanggal' => $tanggal_generate
                ]);
            } else {
                // Jika belum ada, insert data baru
                $this->Gudang_model->insert($item);
            }
        }

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}



// public function generate_stok_opname() {
//     $this->load->model('Gudang_model');
//     $this->load->model('StokOpname_model');

//     $month = $this->input->get('month') ?: date('m');
//     $year = $this->input->get('year') ?: date('Y');

//     // Get the last day of the selected month
//     $last_day = date('Y-m-t', strtotime("$year-$month-01"));

//     // Retrieve data from `bl_gudang` based on selected month and year
//     $gudang_data = $this->Gudang_model->get_all_opnamed($month, $year);

//     if (empty($gudang_data)) {
//         $this->session->set_flashdata('error', 'Tidak ada data gudang untuk bulan dan tahun ini.');
//         redirect('gudang');
//     }


//     // Prepare data for `bl_stok_opname`
//     $stok_opname_data = [];
//     foreach ($gudang_data as $item) {
//         // Pastikan semua nilai NULL menjadi 0
//         $stok_awal = $item['stok_awal'] ?? 0;
//         $stok_masuk = $item['stok_masuk'] ?? 0;
//         $stok_keluar = $item['stok_keluar'] ?? 0;
//         $stok_terbuang = $item['stok_terbuang'] ?? 0;
//         $stok_penyesuaian = $item['stok_penyesuaian'] ?? 0;
//         $stok_akhir = $item['stok_akhir'] ?? 0;
//         $unit_total = $stok_akhir * ($item['ukuran'] ?? 0);
//         $nilai_total = $stok_akhir * ($item['harga'] ?? 0);

//         $stok_opname_data[] = [
//             'kategori_id' => $item['kategori_id'],
//             'bl_db_belanja_id' => $item['bl_db_belanja_id'],
//             'bl_db_purchase_id' => $item['bl_db_purchase_id'],
//             'tipe' => $item['tipe'],
//             'merk' => $item['merk'],
//             'ukuran' => $item['ukuran'],
//             'keterangan' => $item['keterangan'],
//             'unit' => $item['unit'],
//             'pack' => $item['pack'],
//             'harga' => $item['harga'],
//             'stok_awal' => $stok_awal,
//             'stok_masuk' => $stok_masuk,
//             'stok_keluar' => $stok_keluar,
//             'stok_terbuang' => $stok_terbuang,
//             'stok_penyesuaian' => $stok_penyesuaian,
//             'stok_akhir' => $stok_akhir,
//             'unit_total' => $unit_total,
//             'nilai_total' => $nilai_total,
//             'tanggal' => $last_day,
//         ];
//     }


//     if (!empty($stok_opname_data)) {
//         // Insert or update data in `bl_stok_opname`
//         $success = $this->StokOpname_model->insert_or_update_batch($stok_opname_data);

//         if ($success) {
//             $this->session->set_flashdata('success', 'Data stok opname berhasil digenerate.');
//         } else {
//             $this->session->set_flashdata('error', 'Terjadi kesalahan saat generate stok opname.');
//         }
//     } else {
//         $this->session->set_flashdata('error', 'Tidak ada transaksi yang dapat digenerate.');
//     }

//     redirect('gudang');
// }

public function generate_stok_opname() {
    $this->load->model('Gudang_model');
    $this->load->model('StokOpname_model');

    // Ambil bulan dan tahun dari bulan sebelumnya
    $previous_month = date('m', strtotime('last month'));
    $previous_year = date('Y', strtotime('last month'));

    // Ambil tanggal terakhir dari bulan sebelumnya
    $last_day = date('Y-m-t', strtotime("$previous_year-$previous_month-01"));

    // Ambil data gudang dari bulan sebelumnya
    $gudang_data = $this->Gudang_model->get_all_opnamed($previous_month, $previous_year);

    if (empty($gudang_data)) {
        $this->session->set_flashdata('error', 'Tidak ada data gudang untuk bulan dan tahun sebelumnya.');
        redirect('gudang');
    }

    // Siapkan data stok opname untuk disimpan
    $stok_opname_data = [];
    foreach ($gudang_data as $item) {
        $stok_awal = $item['stok_awal'] ?? 0;
        $stok_masuk = $item['stok_masuk'] ?? 0;
        $stok_keluar = $item['stok_keluar'] ?? 0;
        $stok_terbuang = $item['stok_terbuang'] ?? 0;
        $stok_penyesuaian = $item['stok_penyesuaian'] ?? 0;
        $stok_akhir = $item['stok_akhir'] ?? 0;
        $unit_total = $stok_akhir * ($item['ukuran'] ?? 0);
        $nilai_total = $stok_akhir * ($item['harga'] ?? 0);

        $stok_opname_data[] = [
            'kategori_id' => $item['kategori_id'],
            'bl_db_belanja_id' => $item['bl_db_belanja_id'],
            'bl_db_purchase_id' => $item['bl_db_purchase_id'],
            'tipe' => $item['tipe'],
            'merk' => $item['merk'],
            'ukuran' => $item['ukuran'],
            'keterangan' => $item['keterangan'],
            'unit' => $item['unit'],
            'pack' => $item['pack'],
            'harga' => $item['harga'],
            'stok_awal' => $stok_awal,
            'stok_masuk' => $stok_masuk,
            'stok_keluar' => $stok_keluar,
            'stok_terbuang' => $stok_terbuang,
            'stok_penyesuaian' => $stok_penyesuaian,
            'stok_akhir' => $stok_akhir,
            'unit_total' => $unit_total,
            'nilai_total' => $nilai_total,
            'tanggal' => $last_day, // Simpan dengan tanggal akhir bulan sebelumnya
        ];
    }

    // Simpan data stok opname ke dalam `bl_stok_opname`
    $this->StokOpname_model->insert_or_update_batch($stok_opname_data);

    $this->session->set_flashdata('success', 'Stok Opname berhasil digenerate untuk akhir bulan sebelumnya.');
    redirect('gudang');
}

public function search() {
    $this->load->model('Gudang_model');

    $query = $this->input->get('searchQuery');
    $month = $this->input->get('month');
    $year = $this->input->get('year');

    $limit = 20; // Set limit hasil pencarian
    $start = 0; // Awal data

    // Ambil data berdasarkan pencarian
    $result = $this->Gudang_model->search($query, $month, $year, $limit, $start);

    $data['gudang'] = $result;
    $data['start'] = $start;

    // Load bagian tabel sebagai respon AJAX
    $this->load->view('gudang/ajax_table', $data);
}

    public function index_v2() {
        $data['title'] = 'Gudang Ver 2';

        // Filter bulan dan tahun
        $data['month'] = $this->input->get('month') ?: date('m');
        $data['year'] = $this->input->get('year') ?: date('Y');

        // Sortir kolom
        $data['sort_1'] = $this->input->get('sort_1') ?: 'kategori';
        $data['sort_2'] = $this->input->get('sort_2') ?: 'nama_barang';
        $data['sort_3'] = $this->input->get('sort_3') ?: 'nama_bahan_baku';
        $data['sort_4'] = $this->input->get('sort_4') ?: 'tipe';

        $sort_criteria = [
            $data['sort_1'] => 'ASC',
            $data['sort_2'] => 'ASC',
            $data['sort_3'] => 'ASC',
            $data['sort_4'] => 'ASC',
        ];

        // Pagination
        $limit = $this->input->get('limit') ?: 50;
        if ($limit == 'all') {
            $limit = 999999;  // Display all records if 'All' is selected
        }

        $page = $this->input->get('page') ?: 1;
        $start = ($page - 1) * $limit;

        // Ambil data gudang dengan filter is_gudang = 1
        $data['gudang'] = $this->Gudang_model->get_all_filtered($data['month'], $data['year'], $limit, $start, $sort_criteria);
        
        // Ensure data exists before proceeding
        if (empty($data['gudang'])) {
            $data['gudang'] = [];
        }

        // Count total rows for pagination
        $total_rows = $this->Gudang_model->count_all_filtered($data['month'], $data['year']);

        // Calculate total nilai_total
        $total_nilai_total = 0;
        foreach ($data['gudang'] as $item) {
            $total_nilai_total += $item['nilai_total'];
        }

        // Konfigurasi pagination
        $this->load->library('pagination');
        $config['base_url'] = base_url('gudang/index_v2?month=' . $data['month'] . '&year=' . $data['year'] . '&limit=' . $limit);
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $limit;
        $config['use_page_numbers'] = true;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
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

        $data['pagination'] = $this->pagination->create_links();
        $data['limit'] = $limit;
        $data['start'] = $start; // Kirimkan offset ke view

            // Pass the total to the view
        $data['total_nilai_total'] = $total_nilai_total;
        // Load view
        $this->load->view('templates/header', $data);
        $this->load->view('gudang/index_v2', $data);
        $this->load->view('templates/footer');
    }
public function searchv2() {
    $this->load->model('Gudang_model');

    $query = $this->input->get('searchQuery');
    $month = $this->input->get('month');
    $year = $this->input->get('year');

    $limit = 20; // Set limit hasil pencarian
    $start = 0; // Awal data

    // Ambil data berdasarkan pencarian
    $result = $this->Gudang_model->searchv2($query, $month, $year, $limit, $start);

    $data['gudang'] = $result;
    $data['start'] = $start;

    // Load bagian tabel sebagai respon AJAX
    $this->load->view('gudang/ajax_table2', $data);
}

    public function gudang_umum() {
        $data['month'] = $this->input->get('month') ?: date('m');
        $data['year'] = $this->input->get('year') ?: date('Y');

        // Ambil data gudang berdasarkan bulan dan tahun
        $data['gudang'] = $this->Gudang_model->get_all($data['month'], $data['year'], 1000, 0, []);

        // Pastikan data tidak kosong
        if (empty($data['gudang'])) {
            $data['gudang'] = [];
        }

        // Load tampilan gudang umum tanpa template
        $this->load->view('gudang_umum', $data);
    }
}
