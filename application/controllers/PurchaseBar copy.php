<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseBar extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('PurchaseBar_model');
        $this->load->model('JenisPengeluaran_model');
        $this->load->library('pagination');
    }

    public function index() {
        $data['title'] = 'Purchase Bar Management';

        // Ambil tanggal dari GET atau default ke hari ini
        $tanggal_awal = $this->input->get('tanggal_awal') ?: date('Y-m-d');
        $tanggal_akhir = $this->input->get('tanggal_akhir') ?: date('Y-m-d');

        // Konfigurasi pagination
        $config = $this->config_pagination($tanggal_awal, $tanggal_akhir);
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // Ambil data berdasarkan range tanggal dan halaman
        $data['purchases'] = $this->PurchaseBar_model->get_by_date_range($tanggal_awal, $tanggal_akhir, $config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();

        // Data tambahan untuk view
        $data['tanggal_awal'] = $tanggal_awal;
        $data['tanggal_akhir'] = $tanggal_akhir;
        $data['total_harga'] = array_sum(array_column($data['purchases'], 'total_harga'));

        // Data dropdown
        $data['jenis_pengeluaran_list'] = $this->JenisPengeluaran_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('purchase_bar/index', $data);
        $this->load->view('templates/footer');
    }

    public function search_barang() {
        $keyword = $this->input->get('keyword');
        
        if (empty($keyword)) {
            echo json_encode([]);
            return;
        }

        // Panggil fungsi search di model
        $result = $this->PurchaseBar_model->search_barang($keyword);

        echo json_encode($result);
    }


    public function add() {
        $data = $this->input->post();

        // Validasi input
        if (!$this->validate_input($data)) {
            $this->session->set_flashdata('error', 'Harap isi semua kolom wajib dengan benar.');
            redirect('purchase_bar');
        }

        // Insert ke tabel `bl_purchase_bar`
        $this->PurchaseBar_model->insert([
            'tanggal_pembelian' => date('Y-m-d'),
            'jenis_pengeluaran' => $data['jenis_pengeluaran'],
            'nama_barang' => $data['nama_barang'],
            'kuantitas' => $data['kuantitas'],
            'harga_satuan' => $data['harga_satuan'],
            'total_harga' => $data['kuantitas'] * $data['harga_satuan'],
            'status' => 'pending'
        ]);

        $this->session->set_flashdata('success', 'Data berhasil ditambahkan dengan status pending.');
        redirect('purchase_bar');
    }

    public function delete($id) {
        if ($this->PurchaseBar_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data.');
        }
        redirect('purchase_bar');
    }

    /**
     * Helper function to validate input data
     */
    private function validate_input($data) {
        return !(
            empty($data['nama_barang']) ||
            empty($data['kuantitas']) ||
            empty($data['harga_satuan']) ||
            empty($data['jenis_pengeluaran']) ||
            !is_numeric($data['kuantitas']) ||
            !is_numeric($data['harga_satuan'])
        );
    }

    /**
     * Helper function to configure pagination
     */
    private function config_pagination($tanggal_awal, $tanggal_akhir) {
        return [
            'base_url' => base_url('purchase_bar/index'),
            'total_rows' => $this->PurchaseBar_model->count_filtered($tanggal_awal, $tanggal_akhir),
            'per_page' => 10,
            'uri_segment' => 3,
            'full_tag_open' => '<ul class="pagination">',
            'full_tag_close' => '</ul>',
            'attributes' => ['class' => 'page-link'],
            'first_link' => 'First',
            'last_link' => 'Last',
            'next_link' => '&raquo;',
            'prev_link' => '&laquo;',
            'cur_tag_open' => '<li class="page-item active"><a class="page-link">',
            'cur_tag_close' => '</a></li>',
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',
        ];
    }
}
