<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller {
    public function __construct() {
        parent::__construct();
        check_login(); // pastikan user sudah login
        $this->load->helper('url');
        $this->load->model('Purchase_model');
        $this->load->model('Belanja_model');
        $this->load->model('Gudang_model');
        $this->load->model('DbPurchase_model');
        $this->load->model('Rekening_model');
        $this->load->model('PurchaseBar_model');
        $this->load->model('PurchaseKitchen_model');
        $this->load->model('JenisPengeluaran_model');
        $this->load->model('Stok_model');

    }


public function index()
{
    $this->load->model('Rekening_model');
    $this->load->model('DbPurchase_model');
    $this->load->model('Purchase_model');
    $this->load->library('pagination');

    $data['title'] = 'Purchase Management';

    // Ambil data tanggal dan jumlah baris per halaman dari input
    $tanggal_awal = $this->input->get('tanggal_awal') ?: date('Y-m-d');
    $tanggal_akhir = $this->input->get('tanggal_akhir') ?: date('Y-m-d');
    $jenis_pengeluaran = $this->input->get('jenis_pengeluaran') ?: null;
    $per_page = $this->input->get('per_page') ?: 100;

        // If "All" is selected, don't apply pagination
    if ($per_page === 'all') {
        $per_page = $this->Purchase_model->count_filtered($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran); // Get total count
    }


    // Konfigurasi pagination
    $config['base_url'] = base_url('purchase/index');
    $config['total_rows'] = $this->Purchase_model->count_filtered($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran);
    $config['per_page'] = $per_page;
    $config['uri_segment'] = 3;

    // Tambahkan query string untuk mempertahankan filter
    $query_string = '?tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir . '&jenis_pengeluaran=' . $jenis_pengeluaran . '&per_page=' . $per_page;
    $config['suffix'] = '?tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir . '&jenis_pengeluaran=' . $jenis_pengeluaran . '&per_page=' . $per_page;
    $config['first_url'] = $config['base_url'] . $config['suffix'];

    // Styling pagination
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    $config['attributes'] = ['class' => 'page-link'];
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';

    $this->pagination->initialize($config);

    // Ambil halaman saat ini dari segment URI
    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

    // Ambil data sesuai pagination
    $data['purchases'] = $this->Purchase_model->get_by_date_range($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran, $per_page, $page);

    // Data untuk view
    $data['pagination'] = $this->pagination->create_links();
    $data['tanggal_awal'] = $tanggal_awal;
    $data['tanggal_akhir'] = $tanggal_akhir;
    $data['jenis_pengeluaran'] = $jenis_pengeluaran;
    $data['per_page'] = $per_page;
    $data['total_harga'] = array_sum(array_column($data['purchases'], 'total_harga'));
    
    // Ambil data jenis pengeluaran untuk dropdown
    $data['jenis_pengeluaran_list'] = $this->JenisPengeluaran_model->get_all();
    // Data untuk dropdown
    $data['kategori_list'] = $this->db->get('bl_kategori')->result_array();
    $data['tipe_produksi_list'] = $this->db->get('bl_tipe_produksi')->result_array();
    $data['pegawai_list'] = $this->Purchase_model->get_all_pegawai();

    // Data untuk dropdown metode pembayaran
    $data['metode_pembayaran'] = $this->Rekening_model->get_all();

    $this->load->view('templates/header', $data);
    $this->load->view('purchase/index', $data);
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
        empty($data['jenis_pengeluaran']) ||
        ($data['jenis_pengeluaran'] == 17 && empty($data['pegawai_id'])) // Validasi pegawai untuk KASBON
    ) {
        $this->session->set_flashdata('error', 'Harap isi semua kolom wajib.');
        redirect('purchase');
    }

    // Konversi tipe data numerik
    $kuantitas = (float) $data['kuantitas'];
    $harga_satuan = (float) $data['harga_satuan'];
//   $unit = (float) $data['unit'];

    $this->load->model('Belanja_model');
    $this->load->model('DbPurchase_model');
    $this->load->model('Purchase_model');
    $this->load->model('Gudang_model');
    $this->load->model('Stok_model');

    // Periksa atau tambahkan ke bl_db_belanja
    $belanja = $this->Belanja_model->search_exact(
        $data['nama_barang'], 
        $data['nama_bahan_baku'], 
        $data['kategori'], 
        $data['tipe_produksi']
    );
       
    if (!$belanja) {
    // Cek apakah jenis_pengeluaran adalah STOREROOM (id = 1)
    $is_gudang = ($data['jenis_pengeluaran'] == 1) ? 1 : 0;

    // Tambahkan data baru ke bl_db_belanja dengan is_gudang sesuai jenis pengeluaran
    $belanja_id = $this->Belanja_model->insert([
        'nama_barang' => $data['nama_barang'],
        'nama_bahan_baku' => $data['nama_bahan_baku'],
        'id_kategori' => $data['kategori'],
        'id_tipe_produksi' => $data['tipe_produksi'],
        'tanggal_update' => date('Y-m-d'),
        'is_gudang' => $is_gudang
    ]);
    }else {
        $belanja_id = $belanja['id'];
    }

    // Periksa data di bl_db_purchase
    $purchase = $this->DbPurchase_model->search($belanja_id, [
        'merk' => $data['merk'],
        'ukuran' => $data['ukuran'],
        'keterangan' => $data['keterangan'],
        'unit' => $data['unit'],
        'pack' => $data['pack'],
        'harga_satuan' => $harga_satuan,
    ]);

    if (!$purchase) {
        // Tambahkan data baru ke bl_db_purchase
        $purchase_id = $this->DbPurchase_model->insert([
            'bl_db_belanja_id' => $belanja_id,
            'merk' => $data['merk'],
            'ukuran' => $data['ukuran'],
            'keterangan' => $data['keterangan'],        
            'unit' => $data['unit'],
            'pack' => $data['pack'],
            'harga_satuan' => $harga_satuan,
            'hpp' => ($harga_satuan / $data['ukuran']),
            'tanggal' => date('Y-m-d'),
        ]);
    } else {
        $purchase_id = $purchase['id'];
    }

    // Hitung total unit, total harga
    $total_unit = $data['ukuran'] * $kuantitas;
    $total_harga = $kuantitas * $harga_satuan;

    // Jika jenis pengeluaran adalah KASBON, gunakan nama pegawai sebagai keterangan
    $keterangan = '';
    if ($data['jenis_pengeluaran'] == 17) {
        $pegawai = $this->db->select('nama')
            ->where('id', $data['pegawai_id'])
            ->get('abs_pegawai')
            ->row();
        $keterangan = $pegawai ? $pegawai->nama : '';
    }

    // Insert ke bl_purchase tanpa keterangan
    $this->Purchase_model->insert([
        'bl_db_belanja_id' => $belanja_id,
        'bl_db_purchase_id' => $purchase_id,
        'kuantitas' => $kuantitas,
        'total_unit' => $total_unit,
        'total_harga' => $total_harga,
        'hpp' => ($harga_satuan / $data['ukuran']),
        'metode_pembayaran' => $data['metode_pembayaran'],
        'jenis_pengeluaran' => $data['jenis_pengeluaran'],
        'status' => 'verified',
        'tanggal' => $data['tanggal'] ?? date('Y-m-d'),
        'pengusul' => 'purchase',
    ]);

    // Jika jenis pengeluaran adalah KASBON, simpan data ke abs_kasbon
    if ($data['jenis_pengeluaran'] == 17) {
        $this->db->insert('abs_kasbon', [
            'pegawai_id' => $data['pegawai_id'],
            'tanggal' => $data['tanggal'] ?? date('Y-m-d'),
            'nilai' => $total_harga,
            'keterangan' => $keterangan,
            'jenis' => 'kasbon',
        ]);
    }

if ($data['jenis_pengeluaran'] == 1) { 
    $gudang_entry = $this->Gudang_model->get_by_purchase_and_date($purchase_id, $data['tanggal']);

    if (!$gudang_entry) {
        // Jika barang belum ada di gudang, masukkan dengan stok_awal = 0
        $this->Gudang_model->insert([
            'bl_db_belanja_id' => $belanja_id,
            'bl_db_purchase_id' => $purchase_id,
            'stok_awal' => 0, // Biarkan `generate_stok_awal()` yang mengatur stok_awal
            'stok_masuk' => $kuantitas,
            'stok_keluar' => 0,
            'stok_terbuang' => 0,
            'stok_penyesuaian' => 0,
            'tanggal' => $data['tanggal'],
        ]);
    } else {
        // Jika sudah ada, cukup update stok masuk
        $this->Gudang_model->update_stok_masuk_per_bulan($purchase_id, $kuantitas, $data['tanggal']);
    }
}

    // Pastikan pemanggilan memiliki 2 parameter
    $this->Gudang_model->update_stok_akhir($purchase_id, $data['tanggal']);

  // ✅ Update stok masuk dan log
    $this->load->model('Stok_model');

    // Hanya update stok jika jenis pengeluaran termasuk BAR/KITCHEN/EVENT
    if (in_array($data['jenis_pengeluaran'], [2, 3, 5])) {
        $divisi_id_map = [2 => 1, 3 => 2, 5 => 3]; // relasi
        $divisi_id = $divisi_id_map[$data['jenis_pengeluaran']];
        $this->Stok_model->update_stok_masuk(
            $belanja_id,
            $divisi_id,
            $data['tanggal'],
            $kuantitas,
            ($harga_satuan / $data['ukuran']),
            'purchase'
        );
    }


    $this->session->set_flashdata('success', 'Data berhasil disimpan.');

    // Redirect dengan mempertahankan parameter tanggal
    $tanggal_awal = $this->input->get('tanggal_awal');
    $tanggal_akhir = $this->input->get('tanggal_akhir');
    redirect('purchase/index?tanggal_awal=' . $tanggal_awal . '&tanggal_akhir=' . $tanggal_akhir);
}


public function edit($id) {
    $this->load->model('Purchase_model');
    $this->load->model('DbPurchase_model');
    $this->load->model('Belanja_model');

    $data['purchase'] = $this->Purchase_model->get_by_id($id);

    if (!$data['purchase']) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        redirect('purchase');
    }

    // Load data untuk dropdown
    $data['kategori_list'] = $this->db->get('bl_kategori')->result_array();
    $data['tipe_produksi_list'] = $this->db->get('bl_tipe_produksi')->result_array();
    $data['jenis_pengeluaran_list'] = $this->db->get('bl_jenis_pengeluaran')->result_array();
    $data['metode_pembayaran'] = $this->db->get('bl_rekening')->result_array();

    // Load view edit
    $this->load->view('templates/header', $data);
    $this->load->view('purchase/edit', $data);
    $this->load->view('templates/footer');
}


public function update($id) {
    $this->load->model('Purchase_model');
    $this->load->model('DbPurchase_model');
    $this->load->model('Gudang_model');
    $this->load->model('Belanja_model');

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
        redirect('purchase/edit/' . $id);
    }

    // Ambil data lama
    $existing_purchase = $this->Purchase_model->get_by_id($id);
    if (!$existing_purchase) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        redirect('purchase');
    }

    // Periksa perubahan pada merk, keterangan, ukuran, unit, pack, harga_satuan
    $is_changed = (
        $existing_purchase['merk'] !== $data['merk'] ||
        $existing_purchase['keterangan'] !== $data['keterangan'] ||
        $existing_purchase['ukuran'] !== $data['ukuran'] ||
        $existing_purchase['unit'] !== $data['unit'] ||
        $existing_purchase['pack'] !== $data['pack'] ||
        $existing_purchase['harga_satuan'] !== $data['harga_satuan']
    );
    $new_purchase_id = $existing_purchase['bl_db_purchase_id'];


    if ($is_changed) {
        // Tambahkan data baru ke bl_db_purchase
        $new_purchase_id = $this->DbPurchase_model->insert([
            'bl_db_belanja_id' => $existing_purchase['bl_db_belanja_id'],
            'merk' => $data['merk'],
            'keterangan' => $data['keterangan'],
            'ukuran' => $data['ukuran'],
            'unit' => $data['unit'],
            'pack' => $data['pack'],
            'harga_satuan' => $data['harga_satuan'],
            'hpp' => $data['harga_satuan'] / $data['ukuran'],
            'tanggal' => $data['tanggal'],
        ]);

        // Perbarui bl_purchase untuk menggunakan data baru
        $this->Purchase_model->update($id, [
            'bl_db_purchase_id' => $new_purchase_id,
            'kuantitas' => $data['kuantitas'],
            'total_unit' => $data['kuantitas'] * $data['ukuran'],
            'total_harga' => $data['kuantitas'] * $data['harga_satuan'],
            'hpp' => $data['harga_satuan'] / $data['ukuran'],
            'metode_pembayaran' => $data['metode_pembayaran'],
            'jenis_pengeluaran' => $data['jenis_pengeluaran'],
        ]);
    } else {
        // Tidak ada perubahan pada bl_db_purchase, cukup perbarui data bl_purchase
        $this->Purchase_model->update($id, [
            'kuantitas' => $data['kuantitas'],
            'total_unit' => $data['kuantitas'] * $data['ukuran'],
            'total_harga' => $data['kuantitas'] * $data['harga_satuan'],
            'hpp' => $data['harga_satuan'] / $data['ukuran'],
            'metode_pembayaran' => $data['metode_pembayaran'],
            'jenis_pengeluaran' => $data['jenis_pengeluaran'],
        ]);
    }
    // Update stok masuk di tabel gudang jika jenis pengeluaran adalah STOREROOM
    // if ($data['jenis_pengeluaran'] == 1) { // STOREROOM
    //     $this->Gudang_model->update_stok_masuk($existing_purchase['bl_db_purchase_id'], $data['kuantitas'], $new_purchase_id);
    // }

    if ($data['jenis_pengeluaran'] == 1) {
        $this->Gudang_model->update_stok_masuk_per_bulan($existing_purchase['bl_db_purchase_id'], $data['kuantitas'], $data['tanggal']);
        }

    $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
    redirect('purchase');
}


public function delete($id) {
    $this->load->model('Purchase_model');
    $this->load->model('Gudang_model');
    $this->load->model('Stok_model');

    // Ambil data purchase yang akan dihapus
    $purchase = $this->Purchase_model->get_by_id($id);
    if (!$purchase) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        redirect('purchase');
    }

    // Jika jenis pengeluaran adalah STOREROOM, kurangi stok masuk dan update stok akhir
    if ($purchase['jenis_pengeluaran'] == 1) { 
        $this->Gudang_model->kurangi_stok_masuk_per_bulan($purchase['bl_db_purchase_id'], $purchase['kuantitas'], $purchase['tanggal']);
    }

    // Hapus data purchase
    $this->Purchase_model->delete($id);

    // Pastikan stok_akhir diperbarui
    $this->Gudang_model->update_stok_akhir($purchase['bl_db_purchase_id'], $purchase['tanggal']);

    $divisi_id = $this->jenis_pengeluaran_to_divisi($purchase['jenis_pengeluaran']);

    $this->Stok_model->kurangi_stok_masuk(
        $purchase['bl_db_belanja_id'],
        $divisi_id,
        $purchase['kuantitas'],
        $purchase['tanggal'],
        'Hapus Purchase ID #' . $id
    );

    $this->session->set_flashdata('success', 'Data berhasil dihapus.');
    redirect('purchase');
}

private function jenis_pengeluaran_to_divisi($jenis_pengeluaran)
{
    $map = [
        2 => 1, // BAR → divisi 1
        3 => 2, // KITCHEN → divisi 2
        5 => 3  // EVENT → divisi 3
    ];

    return $map[$jenis_pengeluaran] ?? null;
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
    public function verify($id, $source) {
        if ($source === 'bar') {
            $this->PurchaseBar_model->update_status($id, 'verified');
        } elseif ($source === 'kitchen') {
            $this->PurchaseKitchen_model->update_status($id, 'verified');
        }
        redirect('purchase');
    }

    public function reject($id, $source) {
        if ($source === 'bar') {
            $this->PurchaseBar_model->update_status($id, 'rejected');
        } elseif ($source === 'kitchen') {
            $this->PurchaseKitchen_model->update_status($id, 'rejected');
        }
        redirect('purchase');
    }

public function laporan() {
    $this->load->model('Purchase_model');
    
    // Ambil bulan yang difilter atau gunakan bulan saat ini
    $bulan = $this->input->get('bulan') ?: date('Y-m');
    if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
        $bulan = date('Y-m'); // Default ke bulan saat ini jika format tidak valid
    }

    // Ambil data laporan pembelian per tanggal dan jenis pengeluaran
    $data['laporan'] = $this->Purchase_model->get_laporan_purchase_per_tanggal($bulan);
    
    // Data untuk filter bulan
    $data['bulan'] = $bulan;
    
    // Ambil daftar jenis pengeluaran untuk header kolom
    $this->load->model('JenisPengeluaran_model');
    $data['jenis_pengeluaran_list'] = $this->JenisPengeluaran_model->get_all();
    $data['title'] = 'Laporan Purchase';

    // Load view
    $this->load->view('templates/header', $data);
    $this->load->view('purchase/laporan', $data);
    $this->load->view('templates/footer');
}

// public function purchase_umum() {
//     $this->load->model('Purchase_model');
//     $this->load->model('JenisPengeluaran_model');
    
//     // Fetch all purchases, ordered by tanggal, jenis_pengeluaran, and nama_barang
//     $data['purchases'] = $this->Purchase_model->get_all_ordered(); // Ensure this gets all the necessary fields including jenis_pengeluaran name

//     // Pass the data to the view
//     $this->load->view('purchase_umum', $data);
// }


}



