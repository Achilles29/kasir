<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_Brankas extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Laporan_Brankas_model');
    }

    public function index() {
        // Get the selected month, default is current month
        $bulan = $this->input->get('bulan') ?: date('Y-m');
        $data['bulan'] = $bulan;

        // Get the data for brankas report
        $data['brankas_data'] = $this->Laporan_Brankas_model->get_branks_report_data($bulan);

        // Load view
        $this->load->view('templates/header', $data);
        $this->load->view('laporan_brankas/index', $data);
        $this->load->view('templates/footer');
    }
}
