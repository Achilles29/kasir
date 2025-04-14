<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Belanja extends CI_Controller {
        public function __construct() {
            parent::__construct();
            $this->load->library('pagination'); // Pastikan library pagination di-load
            $this->load->model('Belanja_model');
        }

    public function index() {
        $data['title'] = 'Database Belanja';

        // Get limit from GET request, default to 10
        $limit = $this->input->get('limit') ?: 10;
        if ($limit == 'all') {
            $limit = 999999;  // Display all records if 'All' is selected
        }

        // Pagination Configuration
        $config['base_url'] = base_url('belanja/index');
        $config['total_rows'] = $this->Belanja_model->count_all();
        $config['per_page'] = $limit;
        $config['uri_segment'] = 3; // Pagination segment

        // Pagination styling
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->pagination->initialize($config);

        $page = $this->uri->segment(3);
        $page = (isset($page) && is_numeric($page) && $page >= 0) ? (int)$page : 0;

        // Fetch data
        $data['belanja'] = $this->Belanja_model->get_all($limit, $page);
        $data['pagination'] = $this->pagination->create_links();
        $data['page'] = $page;
        $data['limit'] = $limit;

        $this->load->view('templates/header', $data);
        $this->load->view('belanja/index', $data);
        $this->load->view('templates/footer');
    }


    // public function index() {
    //     $data['title'] = 'Database Belanja';

    //     // Konfigurasi Pagination
    //     $config['base_url'] = base_url('belanja/index');
    //     $config['total_rows'] = $this->Belanja_model->count_all();
    //     $config['per_page'] = 10;
    //     $config['uri_segment'] = 3; // Pastikan segment URI benar
    //     $config['reuse_query_string'] = true; // Tambahkan untuk mempertahankan query string

    //     $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
    //     $config['full_tag_close'] = '</ul></nav>';

    //     $config['first_link'] = 'First';
    //     $config['first_tag_open'] = '<li class="page-item">';
    //     $config['first_tag_close'] = '</li>';

    //     $config['last_link'] = 'Last';
    //     $config['last_tag_open'] = '<li class="page-item">';
    //     $config['last_tag_close'] = '</li>';

    //     $config['next_link'] = '&gt;';
    //     $config['next_tag_open'] = '<li class="page-item">';
    //     $config['next_tag_close'] = '</li>';

    //     $config['prev_link'] = '&lt;';
    //     $config['prev_tag_open'] = '<li class="page-item">';
    //     $config['prev_tag_close'] = '</li>';

    //     $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
    //     $config['cur_tag_close'] = '</a></li>';

    //     $config['num_tag_open'] = '<li class="page-item">';
    //     $config['num_tag_close'] = '</li>';

    //     $config['attributes'] = array('class' => 'page-link');

    //     $this->pagination->initialize($config);


    //     $page = $this->uri->segment(3);
    //     $page = (isset($page) && is_numeric($page) && $page >= 0) ? (int)$page : 0;


    //     // Pastikan semua parameter pagination valid
    //     if ($config['total_rows'] > 0 && $config['per_page'] > 0) {
    //         $this->pagination->initialize($config);
    //         $data['pagination'] = $this->pagination->create_links();
    //     } else {
    //         $data['pagination'] = '';
    //     }

    //     // Data untuk View
    //     $data['page'] = $page;
    //     $data['belanja'] = $this->Belanja_model->get_all($config['per_page'], $page);

    //     $this->load->view('templates/header', $data);
    //     $this->load->view('belanja/index', $data);
    //     $this->load->view('templates/footer');
    // }

    // public function add() {
    //     if ($this->input->post()) {
    //         $data = [
    //             'nama_barang' => $this->input->post('nama_barang'),
    //             'nama_bahan_baku' => $this->input->post('nama_bahan_baku'),
    //             'id_kategori' => $this->input->post('id_kategori'),
    //             'id_tipe_produksi' => $this->input->post('id_tipe_produksi'),
    //             'tanggal_update' => date('Y-m-d'),
    //         ];
    //         $this->Belanja_model->insert($data);
    //         echo json_encode(['status' => 'success']);
    //     } else {
    //         echo json_encode(['status' => 'error']);
    //     }
    // }
    public function add() {
        if ($this->input->post()) {
            $data = [
                'nama_barang' => $this->input->post('nama_barang'),
                'nama_bahan_baku' => $this->input->post('nama_bahan_baku'),
                'id_kategori' => $this->input->post('id_kategori'),
                'id_tipe_produksi' => $this->input->post('id_tipe_produksi'),
                'is_gudang' => $this->input->post('is_gudang') ?: null,
                'tanggal_update' => date('Y-m-d'),
            ];
            $this->Belanja_model->insert($data);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function search() {
        $keyword = $this->input->get('keyword');
        $result = $this->Belanja_model->search($keyword);
        echo json_encode($result);
    }

    // public function edit() {
    //     if ($this->input->post()) {
    //         $id = $this->input->post('id');
    //         $data = [
    //             'nama_barang' => $this->input->post('nama_barang'),
    //             'nama_bahan_baku' => $this->input->post('nama_bahan_baku'),
    //             'id_kategori' => $this->input->post('id_kategori'),
    //             'id_tipe_produksi' => $this->input->post('id_tipe_produksi'),
    //         ];
    //         $this->Belanja_model->update($id, $data);
    //         echo json_encode(['status' => 'success']);
    //     } else {
    //         echo json_encode(['status' => 'error']);
    //     }
    // }
    public function edit() {
        if ($this->input->post()) {
            $id = $this->input->post('id');
            $data = [
                'nama_barang' => $this->input->post('nama_barang'),
                'nama_bahan_baku' => $this->input->post('nama_bahan_baku'),
                'id_kategori' => $this->input->post('id_kategori'),
                'id_tipe_produksi' => $this->input->post('id_tipe_produksi'),
                'is_gudang' => $this->input->post('is_gudang') ?: null,
            ];
            $this->Belanja_model->update($id, $data);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function delete() {
        $id = $this->input->post('id');
        if ($this->Belanja_model->delete($id)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
}


}
