<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Kategori_model');
        $this->load->model('Divisi_model');
    }
    
    public function index() {
        $data['title'] = ' Daftar Kategori';

        $data['kategori'] = $this->Kategori_model->get_all_kategori();
        $this->load->view('templates/header', $data);
        $this->load->view('kategori/index', $data);
        $this->load->view('templates/footer');
    }
    
public function tambah() {
    $data['title'] = 'Tambah Kategori';
    $data['divisi'] = $this->Divisi_model->get_all_divisi();
    
    if ($this->input->post()) {
        $urutan = $this->input->post('urutan');
        if ($this->Kategori_model->check_duplicate_urutan($urutan)) {
            $this->session->set_flashdata('error', 'Urutan tampilan sudah digunakan!');
            redirect('kategori/tambah');
        }

        $data_insert = [
            'nama_kategori' => $this->input->post('nama_kategori'),
            'urutan' => $urutan,
            'pr_divisi_id' => $this->input->post('pr_divisi_id'),
            'status' => $this->input->post('status')
        ];
        $this->db->insert('pr_kategori', $data_insert);
        redirect('kategori');
    }
    
    $this->load->view('templates/header', $data);
    $this->load->view('kategori/tambah', $data);
    $this->load->view('templates/footer');
}

public function edit($id) {
    $data['title'] = 'Edit Kategori';
    $data['kategori'] = $this->Kategori_model->get_kategori_by_id($id);
    $data['divisi'] = $this->Divisi_model->get_all_divisi();

    if ($this->input->post()) {
        $urutan = $this->input->post('urutan');
        if ($this->Kategori_model->check_duplicate_urutan($urutan, $id)) {
            $this->session->set_flashdata('error', 'Urutan tampilan sudah digunakan!');
            redirect('kategori/edit/'.$id);
        }

        $data_update = [
            'nama_kategori' => $this->input->post('nama_kategori'),
            'urutan' => $urutan,
            'pr_divisi_id' => $this->input->post('pr_divisi_id'),
            'status' => $this->input->post('status')
        ];
        $this->db->where('id', $id);
        $this->db->update('pr_kategori', $data_update);
        redirect('kategori');
    }
    
    $this->load->view('templates/header', $data);
    $this->load->view('kategori/edit', $data);
    $this->load->view('templates/footer');
}    
    public function hapus($id) {
        $this->Kategori_model->delete_kategori($id);
        redirect('kategori');
    }
}