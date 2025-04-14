<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resep extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Resep_model');
  }

  public function index()
  {
    $data['title'] = "Manajemen Resep";

    // Ambil semua resep produk (langsung dari bahan baku dan dari base)
    $data['resep_produk'] = $this->Resep_model->get_resep_produk(); // dari pr_resep_produk

    // Ambil resep base (resep yang bahan bakunya langsung dari bahan mentah)
    $data['resep_base'] = $this->Resep_model->get_resep_base(); // dari pr_resep_base

    $this->load->view('templates/header', $data);
    $this->load->view('resep/index', $data);
    $this->load->view('templates/footer');
  }

public function create()
{
  $data['title'] = 'Input Resep';
  $data['produk'] = $this->Resep_model->get_produk();
  $data['bahan'] = $this->Resep_model->get_bahan_baku();
  $this->load->view('templates/header', $data);
  $this->load->view('resep/create', $data);
  $this->load->view('templates/footer');
}

  public function input()
{
  $data['title'] = 'Input Resep';
  $data['produk'] = $this->Resep_model->get_produk();
  $data['bahan'] = $this->Resep_model->get_bahan_baku();
  $this->load->view('templates/header', $data);
  $this->load->view('resep/form_input', $data);
  $this->load->view('templates/footer');
}
public function simpan()
{
  $tipe = $this->input->post('tipe'); // 'produk' atau 'base'

  $produk_id = $this->input->post('produk_id');
  $bahan_id = $this->input->post('bahan_id');
  $jumlah = $this->input->post('jumlah');
  $satuan = $this->input->post('satuan');
  $hpp = $this->input->post('hpp');

  $data = [
    'pr_' . ($tipe == 'produk' ? 'produk_id' : 'base_id') => $produk_id,
    'bahan_id' => $bahan_id,
    'jumlah' => $jumlah,
    'satuan' => $satuan,
    'hpp' => $hpp,
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s'),
  ];

  if ($tipe == 'produk') {
    $this->db->insert('pr_resep_produk', $data);
  } else {
    $this->db->insert('pr_resep_base', $data);
  }

  $this->session->set_flashdata('success', 'Data resep berhasil disimpan!');
  redirect('resep/input');
}
public function get_harga_terbaru_bahan($bahan_id)
{
  return $this->db->select('harga_satuan')
    ->from('bl_db_purchase')
    ->where('bl_db_belanja_id', $bahan_id)
    ->order_by('tanggal', 'DESC')
    ->limit(1)
    ->get()->row('harga_satuan');
}

}
