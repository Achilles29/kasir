<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_produk extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('PenjualanProduk_model');
        $this->load->model('Divisi_model');
        $this->load->model('Produk_model');
        $this->load->library('pagination');
    }

public function index() {
    // Get filter inputs
    $data['tanggal_awal'] = $this->input->get('tanggal_awal') ?: date('Y-m-d', strtotime('-1 day'));
    $data['tanggal_akhir'] = $this->input->get('tanggal_akhir') ?: date('Y-m-d');
    $data['produk'] = $this->input->get('produk') ?: null;
    $data['divisi'] = $this->input->get('divisi') ?: null;
    $data['kategori'] = $this->input->get('kategori') ?: null;
    $data['search'] = $this->input->get('search') ?: null;
    $data['title'] = 'Produk Sales';

    // Get data for divisi dropdown
    $data['divisi_list'] = $this->Divisi_model->get_all_divisi();

    // Get data for produk dropdown
    $data['produk_list'] = $this->Produk_model->get_all_produk();

    // Get filtered data from model
    $limit = $this->input->get('per_page') ?: 30;
    $page = $this->input->get('page') ?: 1;
    $start = ($page - 1) * $limit;

    // Get filtered penjualan data
    $data['penjualan'] = $this->PenjualanProduk_model->get_penjualan_produk(
        $data['tanggal_awal'], 
        $data['tanggal_akhir'], 
        $data['produk'], 
        $data['divisi'], 
        $data['kategori'], 
        $data['search'], 
        $limit, 
        $start
    );

    // Get totals for jumlah, nilai, refund, etc.
    $data['totals'] = $this->PenjualanProduk_model->get_totals(
        $data['tanggal_awal'], 
        $data['tanggal_akhir'], 
        $data['produk'], 
        $data['divisi'], 
        $data['kategori'], 
        $data['search']
    );

    // Get total rows for pagination
    $total_rows = $this->PenjualanProduk_model->count_penjualan_produk(
        $data['tanggal_awal'], 
        $data['tanggal_akhir'], 
        $data['produk'], 
        $data['divisi'], 
        $data['kategori'], 
        $data['search']
    );

    // Configure pagination
    $config['base_url'] = base_url('penjualan_produk/index?tanggal_awal=' . $data['tanggal_awal'] . '&tanggal_akhir=' . $data['tanggal_akhir'] . '&produk=' . $data['produk'] . '&divisi=' . $data['divisi'] . '&kategori=' . $data['kategori'] . '&search=' . $data['search']);
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

    // Adjust for pagination continuity
    $data['pagination'] = $this->pagination->create_links();
    $data['limit'] = $limit;
    $data['start'] = $start;

    // Load the view
    $this->load->view('templates/header', $data);
    $this->load->view('penjualan_produk/index', $data);
    $this->load->view('templates/footer');
}

    public function search_produk() {
        $search = $this->input->get('search');
        $results = $this->PenjualanProduk_model->search_produk($search);
        echo json_encode($results);
    }


    public function search_kategori() {
        $search = $this->input->get('search');
        $results = $this->PenjualanProduk_model->search_kategori($search);
        echo json_encode($results);
    }

    public function penjualan_produk_umum()
    {
        // Fetch all data from the model without any filters or pagination
        $data['penjualan'] = $this->PenjualanProduk_model->get_all_penjualan_produk();

        // Load the view
        $this->load->view('templates/header', $data);
        $this->load->view('penjualan_produk_umum', $data);
        $this->load->view('templates/footer');
    }
}
?>
