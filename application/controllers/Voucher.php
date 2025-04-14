<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Voucher_model');
        $this->load->model('Produk_model');
    }

    public function index() {
    $data['title'] = 'Daftar Voucher';

    $this->load->view('templates/header', $data);
    $this->load->view('voucher/index');
    $this->load->view('templates/footer');
    }
public function apply() {
    $kode_voucher = $this->input->post('kode_voucher');

    $voucher = $this->db->get_where('pr_voucher', ['kode_voucher' => $kode_voucher, 'status' => 'aktif'])->row_array();

    if (!$voucher) {
        echo json_encode(['status' => 'error', 'message' => 'Voucher tidak ditemukan atau sudah tidak aktif.']);
        return;
    }

    $diskon = 0;
    $total_pembelian = $this->input->post('total_harga');

    if ($voucher['jenis'] == 'persentase') {
        $diskon = ($voucher['nilai'] / 100) * $total_pembelian;
    } elseif ($voucher['jenis'] == 'nominal') {
        $diskon = $voucher['nilai'];
    }

    echo json_encode(['status' => 'success', 'diskon' => $diskon]);
}

public function get_all() {
    $search = $this->input->get('search');
    
    $this->load->model('Voucher_model');
    $data = $this->Voucher_model->get_filtered_voucher($search);

    echo json_encode($data);
}



    public function simpan() {
        $data = [
            'kode_voucher' => $this->input->post('kode_voucher'),
            'jenis' => $this->input->post('jenis'),
            'nilai' => $this->input->post('nilai'),
            'min_pembelian' => $this->input->post('min_pembelian'),
            'produk_id' => $this->input->post('produk_id'),
            'jumlah_gratis' => $this->input->post('jumlah_gratis'),
            'max_diskon' => $this->input->post('max_diskon'),
            'status' => $this->input->post('status'),
            'tanggal_mulai' => $this->input->post('tanggal_mulai'),
            'tanggal_berakhir' => $this->input->post('tanggal_berakhir')
        ];

        $id = $this->input->post('id');
        
        if ($id) {
            $this->Voucher_model->update_voucher($id, $data);
            echo json_encode(["status" => "success", "message" => "Voucher berhasil diperbarui"]);
        } else {
            $this->Voucher_model->insert_voucher($data);
            echo json_encode(["status" => "success", "message" => "Voucher berhasil ditambahkan"]);
        }
    }

    public function hapus() {
        $id = $this->input->post('id');
        $this->Voucher_model->delete_voucher($id);
        echo json_encode(["status" => "success", "message" => "Voucher berhasil dihapus"]);
    }

public function search_produk() {
    $keyword = $this->input->get('keyword');
    $this->load->model('Produk_model');

    $produk = $this->Produk_model->search_produk($keyword);

    if (!empty($produk)) {
        echo json_encode($produk);
    } else {
        echo json_encode([]);
    }
}

public function get() {
    $id = $this->input->get('id');
    $voucher = $this->Voucher_model->get_voucher($id);

    if ($voucher) {
        // Ambil nama produk jika produk_id tidak kosong
        if (!empty($voucher['produk_id'])) {
            $this->db->select('nama_produk');
            $this->db->where('id', $voucher['produk_id']);
            $produk = $this->db->get('pr_produk')->row_array();
            $voucher['nama_produk'] = $produk ? $produk['nama_produk'] : "";
        }

        echo json_encode($voucher);
    } else {
        echo json_encode(["error" => "Voucher tidak ditemukan"]);
    }
}
    // public function get() {
    //     $id = $this->input->get('id');
    //     $voucher = $this->Voucher_model->get_voucher($id);

    //     if ($voucher) {
    //         echo json_encode($voucher);
    //     } else {
    //         echo json_encode(["error" => "Voucher tidak ditemukan"]);
    //     }
    // }



}
?>
