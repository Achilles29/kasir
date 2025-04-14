<?php
class SaldoKas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('SaldoKas_model');
    }

    public function index() {
        // Ambil filter bulan dan tahun
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');

        // Ambil data saldo kas berjalan
        $data['kas_berjalan'] = $this->SaldoKas_model->get_saldo_kas_berjalan($bulan, $tahun);
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['title'] = 'Saldo Kas Berjalan';

        // Tampilkan halaman
        $this->load->view('templates/header', $data);
        $this->load->view('saldo_kas/index', $data);
        $this->load->view('templates/footer');
    }
}
