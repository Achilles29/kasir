<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_pending extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('PurchasePending_model');
        $this->load->model('Belanja_model');
        $this->load->model('DbPurchase_model');
        $this->load->model('Purchase_model');
        $this->load->model('Rekening_model');
        $this->load->model('JenisPengeluaran_model');
    }

    public function index() {
        $this->load->library('pagination');
        $data['title'] = 'Purchase Pending Management';

        // Ambil data filter
        $tanggal_awal = $this->input->get('tanggal_awal') ?: date('Y-m-d');
        $tanggal_akhir = $this->input->get('tanggal_akhir') ?: date('Y-m-d');
        $per_page = $this->input->get('per_page') ?: 10;

        // Konfigurasi pagination
        $config['base_url'] = base_url('purchase_pending/index');
        $config['total_rows'] = $this->PurchasePending_model->count_filtered($tanggal_awal, $tanggal_akhir);
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 3;
        $query_string = '?tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir . '&per_page=' . $per_page;
        $config['suffix'] = $query_string;
        $config['first_url'] = $config['base_url'] . $query_string;
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['purchases'] = $this->PurchasePending_model->get_all($tanggal_awal, $tanggal_akhir, $per_page, $page);
        $data['pagination'] = $this->pagination->create_links();
        $data['tanggal_awal'] = $tanggal_awal;
        $data['tanggal_akhir'] = $tanggal_akhir;
        $data['per_page'] = $per_page;

        // Load data tambahan
        $data['jenis_pengeluaran_list'] = $this->JenisPengeluaran_model->get_all();
        $data['kategori_list'] = $this->db->get('bl_kategori')->result_array();
        $data['tipe_produksi_list'] = $this->db->get('bl_tipe_produksi')->result_array();
        $data['metode_pembayaran'] = $this->Rekening_model->get_all();
        $data['total_harga'] = array_sum(array_column($data['purchases'], 'total_harga'));
        
        $this->load->view('templates/header', $data);
        $this->load->view('purchase_pending/index', $data);
        $this->load->view('templates/footer');
    }

    public function add() {
        $data = $this->input->post();

        // Validasi data input
        if (
            empty($data['nama_barang']) || 
            empty($data['kuantitas']) || 
            empty($data['harga_satuan']) || 
            empty($data['metode_pembayaran']) || 
            empty($data['jenis_pengeluaran'])
        ) {
            $this->session->set_flashdata('error', 'Harap isi semua kolom wajib.');
            redirect('purchase_pending');
        }

        // Hitung total unit dan total harga
        $total_unit = $data['kuantitas'] * $data['ukuran'];
        $total_harga = $data['kuantitas'] * $data['harga_satuan'];
        $hpp = ($data['harga_satuan'] / $data['ukuran']);

        // Data yang akan disimpan ke database
        $insert_data = [
            'tanggal_pembelian' => $data['tanggal'],
            'jenis_pengeluaran' => $data['jenis_pengeluaran'],
            'nama_barang' => $data['nama_barang'],
            'nama_bahan_baku' => $data['nama_bahan_baku'],
            'kategori_id' => $data['kategori'],
            'tipe_produksi_id' => $data['tipe_produksi'],
            'merk' => $data['merk'],
            'keterangan' => $data['keterangan'],
            'ukuran' => $data['ukuran'],
            'unit' => $data['unit'],
            'pack' => $data['pack'],
            'harga_satuan' => $data['harga_satuan'],
            'kuantitas' => $data['kuantitas'],
            'total_unit' => $total_unit,
            'total_harga' => $total_harga,
            'hpp' => $hpp,
            'metode_pembayaran' => $data['metode_pembayaran'],
            'status' => 'pending',
            'catatan' => $data['catatan'] ?? null,
        ];

        $this->PurchasePending_model->insert($insert_data);

        $this->session->set_flashdata('success', 'Data berhasil disimpan.');
        redirect('purchase_pending');
    }

    public function verify($id) {
        $pendingData = $this->PurchasePending_model->get_by_id($id);

        if (!$pendingData) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('purchase_pending');
        }

        // ** 1. Periksa di bl_db_belanja **
        $belanjaData = $this->Belanja_model->search_exact(
            $pendingData['nama_barang'],
            $pendingData['nama_bahan_baku'],
            $pendingData['kategori_id'],
            $pendingData['tipe_produksi_id']
        );

        if (!$belanjaData) {
            $belanja_id = $this->Belanja_model->insert([
                'nama_barang' => $pendingData['nama_barang'],
                'nama_bahan_baku' => $pendingData['nama_bahan_baku'],
                'id_kategori' => $pendingData['kategori_id'],
                'id_tipe_produksi' => $pendingData['tipe_produksi_id'],
                'tanggal_update' => date('Y-m-d'),
            ]);
        } else {
            $belanja_id = $belanjaData['id'];
        }

        // ** 2. Periksa di bl_db_purchase **
        $purchaseData = $this->DbPurchase_model->search($belanja_id, [
            'merk' => $pendingData['merk'],
            'keterangan' => $pendingData['keterangan'],
            'ukuran' => $pendingData['ukuran'],
            'unit' => $pendingData['unit'],
            'pack' => $pendingData['pack'],
            'harga_satuan' => $pendingData['harga_satuan']
        ]);

        if (!$purchaseData) {
            $purchase_id = $this->DbPurchase_model->insert([
                'bl_db_belanja_id' => $belanja_id,
                'merk' => $pendingData['merk'],
                'keterangan' => $pendingData['keterangan'],
                'ukuran' => $pendingData['ukuran'],
                'unit' => $pendingData['unit'],
                'pack' => $pendingData['pack'],
                'harga_satuan' => $pendingData['harga_satuan'],
                'hpp' => $pendingData['harga_satuan'] / $pendingData['ukuran'],
                'tanggal' => date('Y-m-d'),
            ]);
        } else {
            $purchase_id = $purchaseData['id'];
        }

        // ** 3. Tambahkan data ke bl_purchase **
        $this->Purchase_model->insert([
            'bl_db_belanja_id' => $belanja_id,
            'bl_db_purchase_id' => $purchase_id,
            'tanggal_pembelian' => $pendingData['tanggal_pembelian'],
            'jenis_pengeluaran' => $pendingData['jenis_pengeluaran'],
            'kuantitas' => $pendingData['kuantitas'],
            'total_unit' => $pendingData['total_unit'],
            'total_harga' => $pendingData['total_harga'],
            'hpp' => $pendingData['hpp'],
            'metode_pembayaran' => $pendingData['metode_pembayaran'],
            'status' => 'verified',
            'pengusul' => 'bar',

        ]);

        // Hapus data dari bl_purchase_pending
        $this->PurchasePending_model->delete($id);

        $this->session->set_flashdata('success', 'Data berhasil diverifikasi.');
        redirect('purchase_pending');
    }

public function search_barang() {
    $keyword = $this->input->get('keyword');

    if (empty($keyword)) {
        echo json_encode([]);
        return;
    }

    $this->db->select('
        bl_db_purchase.id AS purchase_id,
        bl_db_belanja.nama_barang,
        bl_db_belanja.nama_bahan_baku,
        bl_db_belanja.id_kategori,
        bl_db_belanja.id_tipe_produksi,
        bl_kategori.nama_kategori AS kategori,
        bl_tipe_produksi.nama_tipe_produksi AS tipe_produksi,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.pack,
        bl_db_purchase.harga_satuan
    ');
    $this->db->from('bl_db_purchase');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->like('bl_db_belanja.nama_barang', $keyword, 'both');
    $result = $this->db->get()->result_array();

    echo json_encode($result);
}


}
