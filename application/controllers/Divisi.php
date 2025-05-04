<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Divisi extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Divisi_model');
    }
    
    public function index() {
        $data['title'] = ' Daftar Divisi';

        $data['divisi'] = $this->Divisi_model->get_all_divisi();
        $this->load->view('templates/header', $data);
        $this->load->view('divisi/index', $data);
        $this->load->view('templates/footer');
    }
public function load_data() {
    $page = $this->input->get('page') ?: 1;
    $per_page = $this->input->get('per_page') ?: 10;
    $search = $this->input->get('search');
    $offset = ($page - 1) * $per_page;

    $this->load->library('pagination');
    $total_rows = $this->Divisi_model->count_filtered($search);

    $config['base_url'] = '#';
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $per_page;
    $config['use_page_numbers'] = true;
    $config['attributes'] = ['class' => 'page-link', 'data-ci-pagination-page' => '{page}'];
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['prev_link'] = '&laquo;';
    $config['next_link'] = '&raquo;';
    $this->pagination->initialize($config);

    $data = $this->Divisi_model->get_filtered($per_page, $offset, $search);

    echo json_encode([
        'data' => $data, // <-- ini yang dibaca di JavaScript
        'pagination' => $this->pagination->create_links()
    ]);

}

    
public function tambah() {
    $data['title'] = 'Tambah Divisi';
    
    if ($this->input->post()) {
        $data_insert = [
            'nama_divisi' => $this->input->post('nama_divisi'),
            'urutan_tampilan' => $this->input->post('urutan_tampilan')
        ];
        $this->db->insert('pr_divisi', $data_insert);
        redirect('divisi');
    }
    
    $this->load->view('templates/header', $data);
    $this->load->view('divisi/tambah', $data);
    $this->load->view('templates/footer');
}

public function edit($id) {
    $data['title'] = 'Edit Divisi';
    $data['divisi'] = $this->Divisi_model->get_divisi_by_id($id);

    if ($this->input->post()) {
        $data_update = [
            'nama_divisi' => $this->input->post('nama_divisi'),
            'urutan_tampilan' => $this->input->post('urutan_tampilan')
        ];
        $this->Divisi_model->update_divisi($id, $data_update);
        redirect('divisi');
    }
    
    $this->load->view('templates/header', $data);
    $this->load->view('divisi/edit', $data);
    $this->load->view('templates/footer');
}
    public function hapus($id) {
        $this->Divisi_model->delete_divisi($id);
        redirect('divisi');
    }


    public function get($id) {
    $divisi = $this->Divisi_model->get_divisi_by_id($id);
    if ($divisi) {
        echo json_encode(['status' => 'success', 'data' => $divisi]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
    }
}

public function save() {
    $data = $this->input->post();
    $id = $data['id'] ?? null;
    unset($data['id']);

    $data['updated_at'] = date('Y-m-d H:i:s');

    if ($id) {
        $this->Divisi_model->update_divisi($id, $data);
        $message = "Data berhasil diperbarui!";
    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->Divisi_model->insert_divisi($data);
        $message = "Data berhasil ditambahkan!";
    }

    echo json_encode(['status' => 'success', 'message' => $message]);
}

public function delete() {
    $id = $this->input->post('id');
    if ($this->Divisi_model->delete_divisi($id)) {
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data.']);
    }
}


}