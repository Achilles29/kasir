<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GudangAwal_model extends CI_Model {


public function get_all($limit, $start) {
    $this->db->select('
        bl_gudang_awal.*,
        bl_db_belanja.nama_barang,
        bl_db_belanja.nama_bahan_baku,
        bl_db_purchase.merk,
        bl_db_purchase.ukuran,
        bl_db_purchase.harga_satuan
    ');
    $this->db->from('bl_gudang_awal');
    $this->db->join('bl_db_belanja', 'bl_db_belanja.id = bl_gudang_awal.bl_db_belanja_id', 'left');
    $this->db->join('bl_db_purchase', 'bl_db_purchase.id = bl_gudang_awal.bl_db_purchase_id', 'left');
    $this->db->limit($limit, $start);
    $this->db->order_by('bl_gudang_awal.tanggal', 'DESC');
    return $this->db->get()->result_array();
}


public function count_all() {
    return $this->db->count_all('bl_gudang_awal');
}


    public function insert($data) {
        return $this->db->insert('bl_gudang_awal', $data);
    }

public function get_by_id($id) {
    $this->db->select('
        bl_gudang_awal.*, 
        belanja.nama_barang, 
        belanja.nama_bahan_baku, 
        purchase.merk, 
        purchase.ukuran, 
        purchase.harga_satuan
    ');
    $this->db->from('bl_gudang_awal');
    $this->db->join('bl_db_belanja AS belanja', 'belanja.id = bl_gudang_awal.bl_db_belanja_id', 'left');
    $this->db->join('bl_db_purchase AS purchase', 'purchase.id = bl_gudang_awal.bl_db_purchase_id', 'left');
    $this->db->where('bl_gudang_awal.id', $id);
    return $this->db->get()->row_array();
}


public function update($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('bl_gudang_awal', $data);
}

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('bl_gudang_awal');
    }

    // Fetch `bl_db_belanja_id` for verification
    public function get_belanja_by_purchase_id($purchase_id) {
        $this->db->select('bl_db_belanja_id');
        $this->db->from('bl_db_purchase');
        $this->db->where('id', $purchase_id);
        return $this->db->get()->row_array();
    }

public function get_by_month_year($month, $year) {
    $this->db->select('bl_db_belanja_id, bl_db_purchase_id, kuantitas');
    $this->db->from('bl_gudang_awal');
    $this->db->where('MONTH(tanggal)', $month);
    $this->db->where('YEAR(tanggal)', $year);
    return $this->db->get()->result_array();
}


}
