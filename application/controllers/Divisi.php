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


}