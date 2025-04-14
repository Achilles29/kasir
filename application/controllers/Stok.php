<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Stok_model');
  }

  public function index()
  {
    $data['title'] = 'Stok Bahan Baku';
    $data['stok'] = $this->Stok_model->get_all();
    $data['divisi'] = $this->Stok_model->get_divisi();
    $this->load->view('templates/header', $data);
    $this->load->view('stok/index', $data);
    $this->load->view('templates/footer');
  }

  public function tambah()
  {
    $data['title'] = 'Penyesuaian Stok Manual';
    $data['bahan'] = $this->Stok_model->get_bahan();
    $data['divisi'] = $this->Stok_model->get_divisi();
    $this->load->view('templates/header', $data);
    $this->load->view('stok/form_tambah', $data);
    $this->load->view('templates/footer');
  }

  public function simpan()
  {
    $this->Stok_model->insert_manual($this->input->post());
    $this->session->set_flashdata('success', 'Stok berhasil ditambahkan.');
    redirect('stok');
  }
public function log()
{
    $this->load->model('Stok_model');
    $data['title'] = 'Log Stok Bahan Baku';
    $data['log'] = $this->Stok_model->get_log();

    $this->load->view('templates/header', $data);
    $this->load->view('stok/log', $data); // Buat file ini
    $this->load->view('templates/footer');
}


}
