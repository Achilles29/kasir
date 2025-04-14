<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SaldoKasBerjalan extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('SaldoKas_model');
    }

    public function index() {
        // Ambil filter bulan dan tahun
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');

        $data['title'] = 'Saldo Kas Berjalan';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        // Ambil data saldo awal
        $saldo_awal = $this->SaldoKas_model->get_saldo_awal($bulan, $tahun);

        // Ambil data transaksi per tanggal
        $transaksi = $this->SaldoKas_model->get_transaksi($bulan, $tahun);

        // Proses data untuk menghasilkan saldo berjalan
        $data['saldo_berjalan'] = $this->SaldoKas_model->calculate_saldo_berjalan($saldo_awal, $transaksi, $bulan, $tahun);

        // Load view
        $this->load->view('templates/header', $data);
        $this->load->view('saldo_kas_berjalan/index', $data);
        $this->load->view('templates/footer');
    }
}
