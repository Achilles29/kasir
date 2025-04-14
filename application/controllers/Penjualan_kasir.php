<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_kasir extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('PenjualanKasir_model');
        $this->load->model('Rekening_model');
    }

public function index()
{
    $this->load->model('PenjualanKasir_model');
    $this->load->model('Rekening_model');
    $data['title'] = 'Penjualan Kasir';

    // Filter
    $data['tanggal_awal'] = $this->input->get('tanggal_awal') ?: date('Y-m-d', strtotime('-1 day'));
    $data['tanggal_akhir'] = $this->input->get('tanggal_akhir') ?: date('Y-m-d', strtotime('+0 day'));
    $data['rekening_id'] = $this->input->get('rekening_id') ?: null;
    $data['search_nota'] = $this->input->get('search_nota') ?: null;
    $data['metode_pembayaran'] = $this->input->get('metode_pembayaran') ?: null;

    // Perbarui nilai penyesuaian dan selisih jika kosong
    $this->PenjualanKasir_model->update_null_penyesuaian_selisih($data['tanggal_awal'], $data['tanggal_akhir'], $data['rekening_id']);

    // Pagination
    $limit = $this->input->get('per_page') ?: 30;
    $page = $this->input->get('page') ?: 1;
    $start = ($page - 1) * $limit;

    $data['penjualan'] = $this->PenjualanKasir_model->get_penjualan(
        $data['tanggal_awal'],
        $data['tanggal_akhir'],
        $data['rekening_id'],
        $data['search_nota'],
        $data['metode_pembayaran'],
        $limit,
        $start
    );

    $total_rows = $this->PenjualanKasir_model->count_penjualan(
        $data['tanggal_awal'],
        $data['tanggal_akhir'],
        $data['rekening_id'],
        $data['search_nota'],
        $data['metode_pembayaran']
    );

    // Pagination Config
    $this->load->library('pagination');
    $config['base_url'] = base_url('penjualan_kasir/index?tanggal_awal=' . $data['tanggal_awal'] . '&tanggal_akhir=' . $data['tanggal_akhir'] . '&rekening_id=' . $data['rekening_id'] . '&search_nota=' . $data['search_nota'] . '&metode_pembayaran=' . $data['metode_pembayaran'] . '&per_page=' . $limit);
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
    $data['start'] = $start;

    // Dropdown rekening
    $data['rekening_list'] = $this->Rekening_model->get_all();

    // Dropdown metode pembayaran
    $data['metode_pembayaran_list'] = $this->PenjualanKasir_model->get_metode_pembayaran_list();

    // Load view
    $this->load->view('templates/header', $data);
    $this->load->view('penjualan_kasir/index', $data);
    $this->load->view('templates/footer');
}


public function update_keterangan()
{
    $input = json_decode(file_get_contents('php://input'), true);
    $no_nota = $input['no_nota'];
    $keterangan = $input['keterangan'];

    $this->load->model('PenjualanKasir_model');
    $result = $this->PenjualanKasir_model->update_keterangan($no_nota, $keterangan);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Update gagal.']);
    }
}

public function kirim_mutasi()
{
    $input = json_decode(file_get_contents('php://input'), true);
    
    $tanggal = $input['tanggal'];
    
    // Pastikan angka disimpan dengan format titik sebagai pemisah desimal
    $selisih = str_replace(',', '.', str_replace('.', '', $input['selisih']));
    $selisih = floatval($selisih); // Konversi ke float untuk penyimpanan yang benar
    
    $keterangan = $input['keterangan'];
    $rekening_id = $input['rekening_id'];

    if (!$rekening_id) {
        echo json_encode(['success' => false, 'message' => 'Rekening ID tidak valid.']);
        return;
    }

    $this->load->model('PenjualanKasir_model');
    $result = $this->PenjualanKasir_model->kirim_mutasi($tanggal, $selisih, $keterangan, $rekening_id);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Mutasi berhasil dikirim.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Mutasi gagal dikirim.']);
    }
}



// public function kirim_mutasi()
// {
//     $input = json_decode(file_get_contents('php://input'), true);
//     $tanggal = $input['tanggal'];
//     $selisih = $input['selisih'];
//     $keterangan = $input['keterangan'];
//     $rekening_id = $input['rekening_id']; // Ambil rekening_id dari input

//     $this->load->model('PenjualanKasir_model');
//     $result = $this->PenjualanKasir_model->kirim_mutasi($tanggal, $selisih, $keterangan, $rekening_id);

//     if ($result) {
//         echo json_encode(['success' => true, 'message' => 'Mutasi berhasil dikirim.']);
//     } else {
//         echo json_encode(['success' => false, 'message' => 'Mutasi gagal dikirim.']);
//     }
// }

public function update_penyesuaian()
{
    $input = json_decode(file_get_contents('php://input'), true);
    $no_nota = $input['no_nota'];
    $penyesuaian = $input['penyesuaian'];

    $this->load->model('PenjualanKasir_model');
    $result = $this->PenjualanKasir_model->update_penyesuaian($no_nota, $penyesuaian);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}



    public function update_field()
{
    $data = $this->input->post();
    $this->load->model('PenjualanKasir_model');

    $result = $this->PenjualanKasir_model->update_field($data['tanggal'], $data['no_nota'], $data['field'], $data['value']);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Update gagal.']);
    }
}
    // Create a new method for the public view
    public function penjualan_umum() {
        $data['title'] = 'Data Penjualan Kasir Umum';

        // Fetch all data without filters or pagination
        $data['penjualan'] = $this->PenjualanKasir_model->get_all_penjualan(); // New method to get all data

        // Load the view without using templates
        $this->load->view('penjualan_umum', $data);
    }
}
