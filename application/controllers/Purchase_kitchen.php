<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_kitchen extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('PurchaseKitchen_model');
        $this->load->model('Belanja_model');
        $this->load->model('DbPurchase_model');
        $this->load->model('Purchase_model');
        $this->load->model('Rekening_model');
        $this->load->model('JenisPengeluaran_model');
    }

    public function index() {
        $this->load->library('pagination');
        $data['title'] = 'Purchase Order Kitchen';

        // Default values for dropdowns
        $data['default_jenis_pengeluaran'] = 3; // Default ID untuk jenis pengeluaran
        $data['default_metode_pembayaran'] = 1; // Default ID untuk metode pembayaran
                // Ambil data filter
        $tanggal_awal = $this->input->get('tanggal_awal') ?: date('Y-m-d');
        $tanggal_akhir = $this->input->get('tanggal_akhir') ?: date('Y-m-d');
        $per_page = $this->input->get('per_page') ?: 10;

        // Konfigurasi pagination
        $config['base_url'] = base_url('Purchase_kitchen/index');
        $config['total_rows'] = $this->PurchaseKitchen_model->count_filtered($tanggal_awal, $tanggal_akhir);
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 3;
        $query_string = '?tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir . '&per_page=' . $per_page;
        $config['suffix'] = $query_string;
        $config['first_url'] = $config['base_url'] . $query_string;
        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['purchases'] = $this->PurchaseKitchen_model->get_all($tanggal_awal, $tanggal_akhir, $per_page, $page);
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
        $this->load->view('purchase_kitchen/index', $data);
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
            redirect('Purchase_kitchen');
        }

        // Hitung total unit dan total harga
        $total_unit = $data['kuantitas'] * $data['ukuran'];
        $total_harga = $data['kuantitas'] * $data['harga_satuan'];
        $hpp = ($data['harga_satuan'] / $data['ukuran']);

        // Data yang akan disimpan ke database
        $insert_data = [
            'tanggal' => $data['tanggal'],
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

        $this->PurchaseKitchen_model->insert($insert_data);

        $this->session->set_flashdata('success', 'Data berhasil disimpan.');
        redirect('Purchase_kitchen');
    }

    public function verify($id) {
        $pendingData = $this->PurchaseKitchen_model->get_by_id($id);

        if (!$pendingData) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('Purchase_kitchen');
        }

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

        $this->PurchaseKitchen_model->update_status($id, 'verified');

        $this->session->set_flashdata('success', 'Data berhasil diverifikasi.');
        redirect('Purchase_kitchen');
    }

    public function reject($id) {
        $pendingData = $this->PurchaseKitchen_model->get_by_id($id);

        if (!$pendingData) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('Purchase_kitchen');
        }

        $this->PurchaseKitchen_model->update_status($id, 'rejected');
        $this->session->set_flashdata('success', 'Data berhasil ditolak.');
        redirect('Purchase_kitchen');
    }

    public function history() {
        $tanggal_awal = $this->input->get('tanggal_awal') ?: date('Y-m-01');
        $tanggal_akhir = $this->input->get('tanggal_akhir') ?: date('Y-m-d');
        $per_page = $this->input->get('per_page') ?: 10;

        $data['title'] = 'Purchase Order History';
        $data['history'] = $this->PurchaseKitchen_model->get_history($tanggal_awal, $tanggal_akhir);
        $data['tanggal_awal'] = $tanggal_awal;
        $data['tanggal_akhir'] = $tanggal_akhir;
        $data['per_page'] = $per_page;

        $this->load->view('templates/header', $data);
        $this->load->view('purchase_kitchen/history', $data);
        $this->load->view('templates/footer');
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
public function detail($id) {
    $data['title'] = 'Detail Purchase Order';

    // Ambil data detail berdasarkan ID
    $data['purchase'] = $this->PurchaseKitchen_model->get_full_detail($id);

    if (!$data['purchase']) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        redirect('Purchase_kitchen/history');
    }

    $this->load->view('templates/header', $data);
    $this->load->view('Purchase_kitchen/detail', $data);
    $this->load->view('templates/footer');
}


public function edit($id) {
    $data['title'] = 'Edit Purchase Order Kitchen';

    // Ambil data berdasarkan ID
    $data['purchase'] = $this->PurchaseKitchen_model->get_by_id($id);

    if (!$data['purchase']) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        redirect('Purchase_kitchen');
    }

    // Load data tambahan untuk dropdown
    $data['jenis_pengeluaran_list'] = $this->JenisPengeluaran_model->get_all();
    $data['kategori_list'] = $this->db->get('bl_kategori')->result_array();
    $data['tipe_produksi_list'] = $this->db->get('bl_tipe_produksi')->result_array();
    $data['metode_pembayaran'] = $this->Rekening_model->get_all();

    // Load view
    $this->load->view('templates/header', $data);
    $this->load->view('Purchase_kitchen/edit', $data);
    $this->load->view('templates/footer');
}

public function update($id) {
    $data = $this->input->post();

    // Validasi input
    if (
        empty($data['nama_barang']) || 
        empty($data['kuantitas']) || 
        empty($data['harga_satuan']) || 
        empty($data['metode_pembayaran']) || 
        empty($data['jenis_pengeluaran'])
    ) {
        $this->session->set_flashdata('error', 'Harap isi semua kolom wajib.');
        redirect('Purchase_kitchen/edit/' . $id);
    }

    // Hitung total unit dan total harga
    $total_unit = $data['kuantitas'] * $data['ukuran'];
    $total_harga = $data['kuantitas'] * $data['harga_satuan'];
    $hpp = ($data['harga_satuan'] / $data['ukuran']);

    // Data untuk diupdate
    $update_data = [
        'tanggal' => $data['tanggal'],
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
        'catatan' => $data['catatan'] ?? null,
    ];

    $this->PurchaseKitchen_model->update($id, $update_data);

    $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
    redirect('Purchase_kitchen');
}

}
