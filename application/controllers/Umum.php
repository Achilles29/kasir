<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Umum extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Purchase_model');
        $this->load->model('Belanja_model');
        $this->load->model('Gudang_model');
        $this->load->model('DbPurchase_model');
        $this->load->model('Rekening_model');
        $this->load->model('PurchaseBar_model');
        $this->load->model('PurchaseKitchen_model');
        $this->load->model('JenisPengeluaran_model');

    }


public function index()
{
    $this->load->model('Rekening_model');
    $this->load->model('DbPurchase_model');
    $this->load->model('Purchase_model');
    $this->load->library('pagination');

    $data['title'] = 'Purchase Management';

    // Ambil data tanggal dan jumlah baris per halaman dari input
    $tanggal_awal = $this->input->get('tanggal_awal') ?: date('Y-m-d');
    $tanggal_akhir = $this->input->get('tanggal_akhir') ?: date('Y-m-d');
    $jenis_pengeluaran = $this->input->get('jenis_pengeluaran') ?: null;
    $per_page = $this->input->get('per_page') ?: 100;

        // If "All" is selected, don't apply pagination
    if ($per_page === 'all') {
        $per_page = $this->Purchase_model->count_filtered($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran); // Get total count
    }


    // Konfigurasi pagination
    $config['base_url'] = base_url('purchase/index');
    $config['total_rows'] = $this->Purchase_model->count_filtered($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran);
    $config['per_page'] = $per_page;
    $config['uri_segment'] = 3;

    // Tambahkan query string untuk mempertahankan filter
    $query_string = '?tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir . '&jenis_pengeluaran=' . $jenis_pengeluaran . '&per_page=' . $per_page;
    $config['suffix'] = '?tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir . '&jenis_pengeluaran=' . $jenis_pengeluaran . '&per_page=' . $per_page;
    $config['first_url'] = $config['base_url'] . $config['suffix'];

    // Styling pagination
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['attributes'] = ['class' => 'page-link'];
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';

    $this->pagination->initialize($config);

    // Ambil halaman saat ini dari segment URI
    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

    // Ambil data sesuai pagination
    $data['purchases'] = $this->Purchase_model->get_by_date_range($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran, $per_page, $page);

    // Data untuk view
    $data['pagination'] = $this->pagination->create_links();
    $data['tanggal_awal'] = $tanggal_awal;
    $data['tanggal_akhir'] = $tanggal_akhir;
    $data['jenis_pengeluaran'] = $jenis_pengeluaran;
    $data['per_page'] = $per_page;
    $data['total_harga'] = array_sum(array_column($data['purchases'], 'total_harga'));
    
    // Ambil data jenis pengeluaran untuk dropdown
    $data['jenis_pengeluaran_list'] = $this->JenisPengeluaran_model->get_all();
    // Data untuk dropdown
    $data['kategori_list'] = $this->db->get('bl_kategori')->result_array();
    $data['tipe_produksi_list'] = $this->db->get('bl_tipe_produksi')->result_array();
    $data['pegawai_list'] = $this->Purchase_model->get_all_pegawai();

    // Data untuk dropdown metode pembayaran
    $data['metode_pembayaran'] = $this->Rekening_model->get_all();

    $this->load->view('templates/header', $data);
    $this->load->view('purchase/index', $data);
    $this->load->view('templates/footer');
}

public function purchase_umum() {
    $this->load->model('Purchase_model');
    $this->load->model('JenisPengeluaran_model');
    
    // Fetch all purchases, ordered by tanggal, jenis_pengeluaran, and nama_barang
    $data['purchases'] = $this->Purchase_model->get_all_ordered(); // Ensure this gets all the necessary fields including jenis_pengeluaran name

    // Pass the data to the view
    $this->load->view('purchase_umum', $data);
}


}



