<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DbPurchase extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('DbPurchase_model');
        $this->load->model('Belanja_model');
        $this->load->library('pagination');
    }

    public function index() {
        $data['title'] = 'Database Purchase';

        // Ambil jumlah baris per halaman dari input GET (default 10)
        $per_page = $this->input->get('per_page') ?: 50;

        // Konfigurasi Pagination
        $config['base_url'] = site_url('dbpurchase/index');
        $config['total_rows'] = $this->DbPurchase_model->count_all();
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = true;

        // Tambahkan HTML untuk pagination
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        // Tentukan halaman berdasarkan URI segment
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // Ambil data sesuai jumlah baris per halaman
        $data['db_purchase'] = $this->DbPurchase_model->get_all($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        $data['per_page'] = $per_page;

        // Muat view
        $this->load->view('templates/header', $data);
        $this->load->view('dbpurchase/index', $data);
        $this->load->view('templates/footer');
    }

public function search_barang() {
    $keyword = $this->input->get('keyword');

    // Ambil data dari tabel bl_db_belanja
    $this->db->select('
        bl_db_belanja.id, 
        bl_db_belanja.nama_barang, 
        bl_db_belanja.nama_bahan_baku, 
        bl_db_belanja.id_kategori, 
        bl_db_belanja.id_tipe_produksi,
        bl_db_purchase.merk, 
        bl_db_purchase.ukuran, 
        bl_db_purchase.unit, 
        bl_db_purchase.harga_satuan
    ');
    $this->db->from('bl_db_belanja');
    $this->db->join('bl_db_purchase', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->like('bl_db_belanja.nama_barang', $keyword);
    $result = $this->db->get()->result_array();

    // Format hasil agar lebih informatif
    $formatted_result = [];
    foreach ($result as $row) {
        $formatted_result[] = [
            'id' => $row['id'],
            'nama_barang' => $row['nama_barang'],
            'nama_bahan_baku' => $row['nama_bahan_baku'],
            'id_kategori' => $row['id_kategori'],
            'id_tipe_produksi' => $row['id_tipe_produksi'],
            'merk' => $row['merk'] ?? '-',
            'ukuran' => $row['ukuran'] ?? '-',
            'unit' => $row['unit'] ?? '-',
            'harga_satuan' => $row['harga_satuan'] ?? 0,
            'preview' => "{$row['nama_barang']} - {$row['merk']} - {$row['ukuran']} {$row['unit']} - Rp " . number_format($row['harga_satuan'], 0, ',', '.'),
        ];
    }

    echo json_encode($formatted_result);
}



public function add() {
    $nama_barang = $this->input->post('nama_barang');
    $nama_bahan_baku = $this->input->post('nama_bahan_baku');
    $id_kategori = $this->input->post('id_kategori');
    $id_tipe_produksi = $this->input->post('id_tipe_produksi');
    $merk = $this->input->post('merk');
    $keterangan = $this->input->post('keterangan');
    $ukuran = $this->input->post('ukuran');
    $unit = $this->input->post('unit');
    $pack = $this->input->post('pack');
    $harga_satuan = $this->input->post('harga_satuan');

    // Cek apakah barang ada di `bl_db_belanja`
    $barang = $this->Belanja_model->search_exact($nama_barang);

    if (empty($barang)) {
        // Tambahkan ke `bl_db_belanja`
        $belanja_data = [
            'nama_barang' => $nama_barang,
            'nama_bahan_baku' => $nama_bahan_baku,
            'id_kategori' => $id_kategori,
            'id_tipe_produksi' => $id_tipe_produksi,
            'tanggal_update' => date('Y-m-d'),
        ];
        $this->Belanja_model->insert($belanja_data);
        $belanja_id = $this->db->insert_id();
    } else {
        $belanja_id = $barang['id'];
    }

    // Hitung HPP
    $hpp = $harga_satuan / ($ukuran ?: 1);

    // Tambahkan ke `bl_db_purchase`
    $purchase_data = [
        'bl_db_belanja_id' => $belanja_id,
        'merk' => $merk,
        'keterangan' => $keterangan,
        'ukuran' => $ukuran,
        'unit' => $unit,
        'pack' => $pack,
        'harga_satuan' => $harga_satuan,
        'hpp' => $hpp,
        'tanggal' => date('Y-m-d'),
    ];

    if ($this->DbPurchase_model->insert($purchase_data)) {
        $this->session->set_flashdata('success', 'Data berhasil disimpan.');
        redirect('dbpurchase');
    } else {
        $this->session->set_flashdata('error', 'Gagal menyimpan data.');
        redirect('dbpurchase');
    }
}

    public function get_by_id() {
        $id = $this->input->get('id');
        $data = $this->DbPurchase_model->get_by_id($id);
        echo json_encode($data);
    }

    // Halaman edit
public function edit($id) {
    $data['title'] = 'Edit Purchase';
    $data['purchase'] = $this->DbPurchase_model->get_by_id($id);
    $data['categories'] = $this->db->get('bl_kategori')->result();
    $data['production_types'] = $this->db->get('bl_tipe_produksi')->result();

    if (empty($data['purchase'])) {
        show_404(); // Jika data tidak ditemukan
    }

    // Pastikan variabel `id_kategori` dan `id_tipe_produksi` tersedia
    $data['purchase']['id_kategori'] = $data['purchase']['id_kategori'] ?? null;
    $data['purchase']['id_tipe_produksi'] = $data['purchase']['id_tipe_produksi'] ?? null;

    $this->load->view('templates/header', $data);
    $this->load->view('dbpurchase/edit', $data);
    $this->load->view('templates/footer');
}


public function update() {
    $id = $this->input->post('id');
    $nama_barang = $this->input->post('nama_barang');
    $nama_bahan_baku = $this->input->post('nama_bahan_baku');
    $id_kategori = $this->input->post('id_kategori');
    $id_tipe_produksi = $this->input->post('id_tipe_produksi');
    $merk = $this->input->post('merk');
    $keterangan = $this->input->post('keterangan');
    $ukuran = $this->input->post('ukuran');
    $unit = $this->input->post('unit');
    $pack = $this->input->post('pack');
    $harga_satuan = $this->input->post('harga_satuan');
    $hpp = $harga_satuan / ($ukuran ?: 1);

    // Cek di bl_db_belanja apakah kombinasi baru sudah ada
    $this->db->where('nama_barang', $nama_barang);
    $this->db->where('nama_bahan_baku', $nama_bahan_baku);
    $this->db->where('id_kategori', $id_kategori);
    $this->db->where('id_tipe_produksi', $id_tipe_produksi);
    $existing = $this->db->get('bl_db_belanja')->row();

    if (!$existing) {
        // Tambahkan ke bl_db_belanja
        $this->db->insert('bl_db_belanja', [
            'nama_barang' => $nama_barang,
            'nama_bahan_baku' => $nama_bahan_baku,
            'id_kategori' => $id_kategori,
            'id_tipe_produksi' => $id_tipe_produksi,
            'tanggal_update' => date('Y-m-d'),
        ]);
        $belanja_id = $this->db->insert_id();
    } else {
        $belanja_id = $existing->id;
    }

    // Update di bl_db_purchase
    $data = [
        'bl_db_belanja_id' => $belanja_id,
        'merk' => $merk,
        'keterangan' => $keterangan,
        'ukuran' => $ukuran,
        'unit' => $unit,
        'pack' => $pack,
        'harga_satuan' => $harga_satuan,
        'hpp' => $hpp,
    ];
    $this->DbPurchase_model->update($id, $data);

    $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
    redirect('dbpurchase');
}


    public function delete($id) {
        if ($this->DbPurchase_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data.');
        }

        redirect('dbpurchase');
    }

    
}
