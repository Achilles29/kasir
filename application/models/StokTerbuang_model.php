<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StokTerbuang_model extends CI_Model {
    public function insert($data) {
        $this->db->insert('bl_stok_terbuang', $data);
        return $this->db->insert_id();
    }
public function get_by_month($bulan, $tahun) {
    $this->db->select('
        bl_stok_terbuang.*,
        bl_db_belanja.nama_barang,
        bl_db_purchase.merk,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit
    ');
    $this->db->join('bl_db_purchase', 'bl_stok_terbuang.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->where('MONTH(bl_stok_terbuang.tanggal)', $bulan);
    $this->db->where('YEAR(bl_stok_terbuang.tanggal)', $tahun);
    return $this->db->get('bl_stok_terbuang')->result_array();
}


public function get_filtered($tanggal_awal, $tanggal_akhir, $limit, $start) {
    $this->db->select('
        bl_stok_terbuang.*,
        bl_db_belanja.nama_barang,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.harga_satuan
    ');
    $this->db->join('bl_db_purchase', 'bl_stok_terbuang.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->where('bl_stok_terbuang.tanggal >=', $tanggal_awal);
    $this->db->where('bl_stok_terbuang.tanggal <=', $tanggal_akhir);
    $this->db->limit($limit, $start);
    return $this->db->get('bl_stok_terbuang')->result_array();
}

public function count_filtered($tanggal_awal, $tanggal_akhir) {
    $this->db->where('tanggal >=', $tanggal_awal);
    $this->db->where('tanggal <=', $tanggal_akhir);
    return $this->db->count_all_results('bl_stok_terbuang');
}
public function get_all($limit, $start) {
    $this->db->select('
        bl_stok_terbuang.*,
        bl_db_belanja.nama_barang,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.harga_satuan AS harga
    ');
    $this->db->from('bl_stok_terbuang');
    $this->db->join('bl_gudang', 'bl_stok_terbuang.bl_db_purchase_id = bl_gudang.bl_db_purchase_id', 'left');
    $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->limit($limit, $start);
    $this->db->order_by('bl_stok_terbuang.tanggal', 'DESC');
    return $this->db->get()->result_array();
}
public function update($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('bl_stok_terbuang', $data);
}

public function delete($id) {
    $this->db->where('id', $id);
    return $this->db->delete('bl_stok_terbuang');
}
public function get_by_id($id) {
    $this->db->select('bl_stok_terbuang.*, bl_db_belanja.nama_barang, bl_db_purchase.merk');
    $this->db->join('bl_gudang', 'bl_gudang.bl_db_purchase_id = bl_stok_terbuang.bl_db_purchase_id', 'left');
    $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->where('bl_stok_terbuang.id', $id);
    return $this->db->get('bl_stok_terbuang')->row_array();
}

}
