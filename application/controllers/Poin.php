<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poin extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Poin_model');
    }

    public function index() {
        $data['title'] = 'Daftar Poin';

        $data['poin'] = $this->Poin_model->get_all_poin();
        $data['produk'] = $this->db->get('pr_produk')->result_array();
        $this->load->view("templates/header", $data);
        $this->load->view('poin/index', $data);
        $this->load->view("templates/footer");

    }

public function simpan_poin() {
    $id = $this->input->post('id'); // Ambil ID jika ada (untuk edit)
    
    $data = [
        'jenis_point'   => $this->input->post('jenis_point'),
        'produk_id'     => $this->input->post('produk_id'),
        'min_pembelian' => $this->input->post('min_pembelian'),
        'nilai_point'   => $this->input->post('nilai_point'),
    ];

    if (!empty($id)) {
        // Jika ID ada, lakukan UPDATE
        $this->Poin_model->update_poin($id, $data);
    } else {
        // Jika ID kosong, lakukan INSERT
        $this->Poin_model->simpan_poin($data);
    }

    echo json_encode(["status" => "success"]);
}



    public function hapus_poin() {
        $this->Poin_model->hapus_poin($this->input->post('id'));
        echo json_encode(["status" => "success"]);
    }
public function cari_produk() {
    $search = $this->input->get('search');

    $this->db->select('id, nama_produk as text');
    $this->db->like('nama_produk', $search);
    $this->db->limit(10);
    $query = $this->db->get('pr_produk')->result();

    echo json_encode($query);
}


}
