<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SomeController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pegawai_model'); // Model untuk tabel abs_pegawai
    }

    public function some_page() {
        // Hanya bisa diakses oleh superadmin (99) dan supervisor (1)
        check_access([99, 1]);

        // Konten halaman
        $data['title'] = 'Some Page';
        $this->load->view('templates/header', $data);
        $this->load->view('some_page', $data);
        $this->load->view('templates/footer');
    }

    public function purchase_bar() {
        // Hanya bisa diakses oleh divisi BAR (divisi_id = 2)
        check_access([2]);

        // Konten halaman Purchase Bar
        $data['title'] = 'Purchase Bar';
        $this->load->view('templates/header', $data);
        $this->load->view('purchase_bar', $data);
        $this->load->view('templates/footer');
    }

    public function purchase_kitchen() {
        // Hanya bisa diakses oleh divisi KITCHEN (divisi_id = 3)
        check_access([3]);

        // Konten halaman Purchase Kitchen
        $data['title'] = 'Purchase Kitchen';
        $this->load->view('templates/header', $data);
        $this->load->view('purchase_kitchen', $data);
        $this->load->view('templates/footer');
    }
}
