<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StoreRequest_model extends CI_Model {

    // Mendapatkan semua data store request berdasarkan rentang tanggal dengan pagination
public function get_all($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran, $limit, $start) {
    $this->db->select('
        bl_store_request.*,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.pack,
        bl_db_purchase.harga_satuan AS harga,
        bl_db_belanja.nama_barang,
        bl_jenis_pengeluaran.nama_jenis_pengeluaran
    ');
    $this->db->from('bl_store_request');
    $this->db->join('bl_db_purchase', 'bl_store_request.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_jenis_pengeluaran', 'bl_store_request.jenis_pengeluaran = bl_jenis_pengeluaran.id', 'left');
    $this->db->where('bl_store_request.tanggal >=', $tanggal_awal);
    $this->db->where('bl_store_request.tanggal <=', $tanggal_akhir);

    // Apply filter by jenis_pengeluaran if set
    if ($jenis_pengeluaran) {
        $this->db->where('bl_store_request.jenis_pengeluaran', $jenis_pengeluaran);
    }

    $this->db->limit($limit, $start);
    $this->db->order_by('bl_store_request.tanggal', 'DESC');
    $this->db->order_by('bl_store_request.jenis_pengeluaran', 'ASC');
    return $this->db->get()->result_array();
}

public function count_all($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran) {
    $this->db->from('bl_store_request');
    $this->db->where('tanggal >=', $tanggal_awal);
    $this->db->where('tanggal <=', $tanggal_akhir);

    // Apply filter by jenis_pengeluaran if set
    if ($jenis_pengeluaran) {
        $this->db->where('jenis_pengeluaran', $jenis_pengeluaran);
    }

    return $this->db->count_all_results();
}

    // Mendapatkan data store request berdasarkan ID
public function get_by_id($id) {
    $this->db->select('
        bl_store_request.*,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.harga_satuan AS harga,
        bl_db_belanja.nama_barang
    ');
    $this->db->from('bl_store_request');
    $this->db->join('bl_db_purchase', 'bl_store_request.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->where('bl_store_request.id', $id);
    return $this->db->get()->row_array();
}
public function search_table($query) {
    $this->db->select('
        bl_store_request.*,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.pack,
        bl_db_purchase.harga_satuan AS harga,
        bl_db_belanja.nama_barang,
        bl_jenis_pengeluaran.nama_jenis_pengeluaran
    ');
    $this->db->from('bl_store_request');
    $this->db->join('bl_db_purchase', 'bl_store_request.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_jenis_pengeluaran', 'bl_store_request.jenis_pengeluaran = bl_jenis_pengeluaran.id', 'left');
    $this->db->like('bl_db_belanja.nama_barang', $query);
    $this->db->or_like('bl_db_purchase.merk', $query);
    $this->db->or_like('bl_db_purchase.keterangan', $query);
    $this->db->or_like('bl_jenis_pengeluaran.nama_jenis_pengeluaran', $query); // Filter nama jenis pengeluaran
    return $this->db->get()->result_array();
}


    // Menambahkan data store request baru
    public function insert($data) {
        return $this->db->insert('bl_store_request', $data);
    }
public function update($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('bl_store_request', $data);
}
    // Menghapus data store request berdasarkan ID
public function delete($id) {
    $this->db->where('id', $id);
    return $this->db->delete('bl_store_request');
}

public function get_all_with_stock($bulan, $tahun) {
    $this->db->select('
        bl_store_request.*,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.pack,
        bl_db_purchase.harga_satuan AS harga,
        bl_db_belanja.nama_barang,
        bl_gudang.stok_awal,
        (bl_gudang.stok_awal + bl_gudang.stok_masuk - bl_gudang.stok_keluar - bl_gudang.stok_terbuang + bl_gudang.stok_penyesuaian) AS sisa_stok
    ');
    $this->db->from('bl_store_request');
    $this->db->join('bl_db_purchase', 'bl_store_request.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_gudang', 'bl_store_request.bl_db_purchase_id = bl_gudang.bl_db_purchase_id', 'left');
    $this->db->where('MONTH(bl_store_request.tanggal)', $bulan);
    $this->db->where('YEAR(bl_store_request.tanggal)', $tahun);
    $this->db->where('MONTH(bl_gudang.tanggal)', $bulan);
    $this->db->where('YEAR(bl_gudang.tanggal)', $tahun);
    $this->db->order_by('bl_store_request.tanggal', 'ASC');

    return $this->db->get()->result_array();
}

public function get_available_years() {
    $this->db->select('DISTINCT(YEAR(tanggal)) AS tahun', FALSE);
    $this->db->from('bl_store_request');
    $this->db->order_by('tahun', 'DESC');
    return $this->db->get()->result_array();
}

public function get_laporan_store_request_per_tanggal($bulan) {
    $tanggal_awal = $bulan . '-01';
    $tanggal_akhir = date("Y-m-t", strtotime($tanggal_awal));

    // Query untuk mengambil data berdasarkan tanggal dan jenis pengeluaran
    $this->db->select('
        bl_store_request.tanggal,
        bl_jenis_pengeluaran.nama_jenis_pengeluaran,
        SUM(bl_store_request.kuantitas * bl_db_purchase.harga_satuan) AS total_harga
    ');
    $this->db->from('bl_store_request');
    $this->db->join('bl_db_purchase', 'bl_store_request.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_jenis_pengeluaran', 'bl_store_request.jenis_pengeluaran = bl_jenis_pengeluaran.id', 'left');
    $this->db->where('bl_store_request.tanggal >=', $tanggal_awal);
    $this->db->where('bl_store_request.tanggal <=', $tanggal_akhir);
    $this->db->group_by('bl_store_request.tanggal, bl_store_request.jenis_pengeluaran');
    $this->db->order_by('bl_store_request.tanggal ASC, bl_jenis_pengeluaran.id ASC');
    $query = $this->db->get();
    return $query->result_array();
}


public function get_all_umum($tanggal_awal, $tanggal_akhir, $jenis_pengeluaran) {
    $this->db->select('
        bl_store_request.*,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.pack,
        bl_db_purchase.harga_satuan AS harga,
        bl_db_belanja.nama_barang,
        bl_jenis_pengeluaran.nama_jenis_pengeluaran
    ');
    $this->db->from('bl_store_request');
    $this->db->join('bl_db_purchase', 'bl_store_request.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_jenis_pengeluaran', 'bl_store_request.jenis_pengeluaran = bl_jenis_pengeluaran.id', 'left');
    $this->db->where('bl_store_request.tanggal >=', $tanggal_awal);
    $this->db->where('bl_store_request.tanggal <=', $tanggal_akhir);

    // Apply filter by jenis_pengeluaran if set
    if ($jenis_pengeluaran) {
        $this->db->where('bl_store_request.jenis_pengeluaran', $jenis_pengeluaran);
    }

//    $this->db->limit($limit, $start);
    $this->db->order_by('bl_store_request.tanggal', 'ASC');
    return $this->db->get()->result_array();
}
}
