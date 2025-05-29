<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stamp extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Stamp_model');
    }

    public function index() {
        $data['title'] = "Promo Stamp";
        $data['promo'] = $this->Stamp_model->get_all_promo();
        $this->load->view('templates/header', $data);
        $this->load->view('promo/stamp_list', $data);
        $this->load->view('templates/footer');
    }

    public function form($id = null) {
        $data['title'] = $id ? "Edit Promo Stamp" : "Tambah Promo Stamp";
        $data['promo'] = $id ? $this->Stamp_model->get_promo_by_id($id) : null;
        $this->load->view('templates/header', $data);
        $this->load->view('promo/stamp_form', $data);
        $this->load->view('templates/footer');
    }

public function save() {
    $produk_id = trim($this->input->post('produk_berlaku'));

    $data = [
        'nama_promo'         => $this->input->post('nama_promo'),
        'deskripsi'          => $this->input->post('deskripsi'),
        'minimal_pembelian'  => $this->input->post('minimal_pembelian'),
        'berlaku_kelipatan'  => $this->input->post('berlaku_kelipatan') ? 1 : 0,
        'produk_berlaku'     => is_numeric($produk_id) ? (int)$produk_id : null, // ✅ hanya ID atau null
        'total_stamp_target' => $this->input->post('total_stamp_target'),
        'hadiah'             => $this->input->post('hadiah'),
        'masa_berlaku_hari'  => $this->input->post('masa_berlaku_hari'),
        'aktif'              => $this->input->post('aktif') ? 1 : 0,
        'updated_at'         => date('Y-m-d H:i:s')
    ];

    $id = $this->input->post('id');
    if ($id) {
        $this->Stamp_model->update_promo($id, $data);
    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->Stamp_model->insert_promo($data);
    }

    redirect('stamp');
}


    public function delete($id) {
        $this->Stamp_model->delete_promo($id);
        redirect('stamp');
    }

    public function kadaluarsa_stamp()
    {
        // Subquery untuk mendapatkan promo_stamp_id yang tidak aktif
        $this->db->where('status', 'aktif');
        $this->db->where('masa_berlaku <', date('Y-m-d'));
        $this->db->where_in('promo_stamp_id', function($builder) {
            $builder->select('id')->from('pr_promo_stamp')->where('aktif', 0);
        });
        $this->db->update('pr_customer_stamp', ['status' => 'kadaluarsa']);
    }
    
}