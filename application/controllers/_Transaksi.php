<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Transaksi_model');
    }

    public function index() {
        $this->load->view('templates/header');
        $this->load->view('transaksi/index');
        $this->load->view('templates/footer');
    }

    public function tambah() {
        $data = [
            'pelanggan' => $this->input->post('pelanggan'),
            'total' => $this->input->post('total'),
            'status' => 'pending'
        ];
        $this->Transaksi_model->simpan_transaksi($data);
        redirect('transaksi');
    }
}
