<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Extra extends CI_Controller {
    public function __construct() {
        parent::__construct();
        check_login();
        $this->load->model('Extra_model');
        $this->load->library('pagination');
    }

    public function index() {
        $data['title'] = 'Produk Extra';
        $this->load->view('templates/header', $data);
        $this->load->view('extra/index', $data);
        $this->load->view('templates/footer');
    }

    public function load_data() {
        $page = $this->input->get('page') ?: 1;
        $per_page = $this->input->get('per_page') ?? 30;
        $search = $this->input->get('search');
        $offset = ($page - 1) * $per_page;

        $this->load->library('pagination');
        $total_rows = $this->Extra_model->count_filtered($search);

        $config['base_url'] = "#";
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['use_page_numbers'] = true;
        $config['attributes'] = ['class' => 'page-link'];
        $config['reuse_query_string'] = TRUE; // Untuk bawa parameter search, dsb
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo;';
        $config['next_link'] = '&raquo;';
        $this->pagination->initialize($config);

        echo json_encode([
            'extra' => $this->Extra_model->get_filtered($per_page, $offset, $search),
            'pagination' => $this->pagination->create_links()
        ]);
    }

public function save() {
    $data = $this->input->post();
    unset($data['id']);
    $data['updated_at'] = date('Y-m-d H:i:s');

    if (!isset($data['status'])) $data['status'] = 1; // default aktif

    if ($this->input->post('id')) {
        $this->Extra_model->update($this->input->post('id'), $data);
        $msg = "Data berhasil diperbarui!";
    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->Extra_model->insert($data);
        $msg = "Data berhasil ditambahkan!";
    }

    echo json_encode(['status' => 'success', 'message' => $msg]);
}



    public function get($id) {
        $data = $this->Extra_model->get_by_id($id);
        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }


    public function delete() {
        $id = $this->input->post('id');
        if ($id) {
            $this->Extra_model->delete($id);
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan.']);
        }
    }

    public function getExtra() {
        $extra = $this->Extra_model->getAllExtra();
        if ($extra) {
            echo json_encode($extra);
        } else {
            echo json_encode([]);
        }
    }


}