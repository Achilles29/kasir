<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pos extends CI_Controller {
    public function __construct() {
        parent::__construct();
        check_login();
        $this->load->model('Produk_model');
    }

    public function index() {
        $data['produk'] = $this->Produk_model->get_all();
        $this->load->view('templates/header', $data);
        $this->load->view('pos/index', $data);
        $this->load->view('templates/footer');
    }
}
