<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_login(); // pastikan user sudah login
        $this->load->helper('url');
    }

    public function index() {
        $data['title'] = 'Dashboard'; // opsional, untuk title
        $this->load->view('templates/header', $data);
        $this->load->view('beranda'); // konten utama
        $this->load->view('templates/footer');
    }
}
