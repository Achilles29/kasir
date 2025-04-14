<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Laporan_model');
  }

public function index()
{
  $today = date('Y-m-d');
  $data['title'] = "Laporan Penjualan";
  $data['tanggal_awal'] = $today;
  $data['tanggal_akhir'] = $today;

  $data['transaksi'] = $this->Laporan_model->filter_transaksi('', $today, $today);
  $this->load->view('templates/header', $data);
  $this->load->view('laporan/index', $data);
  $this->load->view('templates/footer');
}

public function filter()
{
  $search = $this->input->get('search');
  $tanggal_awal = $this->input->get('tanggal_awal');
  $tanggal_akhir = $this->input->get('tanggal_akhir');

  $data['transaksi'] = $this->Laporan_model->filter_transaksi($search, $tanggal_awal, $tanggal_akhir);
  $this->load->view('laporan/tabel_transaksi', $data);
}
  public function detail($id)
  {
    $data['title'] = "Detail Transaksi";
    $data['transaksi'] = $this->Laporan_model->get_transaksi($id);
    $data['detail'] = $this->Laporan_model->get_detail_produk($id);
    $data['pembayaran'] = $this->Laporan_model->get_pembayaran($id);
    $data['refund'] = $this->Laporan_model->get_refund($id);
    $data['void'] = $this->Laporan_model->get_void($id);
    $this->load->view('templates/header', $data);
    $this->load->view('laporan/detail', $data);
    $this->load->view('templates/footer');
  }
}
