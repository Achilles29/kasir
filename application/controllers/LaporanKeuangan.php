<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanKeuangan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        check_login(); // pastikan user sudah login
        $this->load->helper('url');
        $this->load->model('Purchase_model');
        $this->load->model('Belanja_model');
        $this->load->model('Gudang_model');
        $this->load->model('DbPurchase_model');
        $this->load->model('Rekening_model');
        $this->load->model('PurchaseBar_model');
        $this->load->model('PurchaseKitchen_model');
        $this->load->model('JenisPengeluaran_model');

    }



    public function index() {
    $this->load->model('LaporanKeuangan_model');


    $bulan = $this->input->get('bulan') ?: date('m'); // Ambil hanya bulan (format 2 digit)
    $tahun = $this->input->get('tahun') ?: date('Y'); // Ambil tahun (format 4 digit)

    $data['bulan'] = $bulan;
    $data['tahun'] = $tahun;

    $tanggal_awal = "$tahun-$bulan-01";
    $tanggal_akhir = date("Y-m-t", strtotime($tanggal_awal));

    // // Ambil bulan yang difilter atau gunakan bulan saat ini
    // $bulan = $this->input->get('bulan') ?: date('Y-m');
    // if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
    //     $bulan = date('Y-m'); // Default ke bulan saat ini jika format tidak valid
    // }
    // $data['bulan'] = $bulan;

    // Ambil data laporan keuangan
    $data['laporan'] = $this->LaporanKeuangan_model->get_laporan_keuangan($bulan, $tahun);

    $data['title'] = 'Laporan Keuangan';

    // Load view
    $this->load->view('templates/header', $data);
    $this->load->view('laporan_keuangan/index', $data);
    $this->load->view('templates/footer');
}

}
