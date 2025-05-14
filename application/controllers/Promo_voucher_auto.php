<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promo_voucher_auto extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Promo_voucher_model');
    }

    public function index()
    {
        $this->load->model('Produk_model');
        $data['title'] = 'Promo Voucher Otomatis';
        $data['list'] = $this->Promo_voucher_model->get_all();
        $data['produk_map'] = $this->Produk_model->get_id_nama_map();
        $this->load->view('templates/header', $data);
        $this->load->view('promo_voucher_auto/index', $data);
        $this->load->view('templates/footer');
    }
    
    
    public function get_data()
    {
        $result = $this->Promo_voucher_model->get_all();
        echo json_encode($result);
    }

    public function simpan()
    {
        $data = $this->input->post();
        $res = $this->Promo_voucher_model->simpan($data);
        echo json_encode($res);
    }

    public function get_by_id($id)
    {
        $res = $this->Promo_voucher_model->get($id);
        echo json_encode($res);
    }

    public function hapus($id)
    {
        $this->db->where('id', $id)->delete('pr_promo_voucher_auto');
        echo json_encode(['status' => 'ok']);
    }

}