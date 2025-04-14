<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DbPurchase_model extends CI_Model {
    public function get_all($limit, $start) {
        $this->db->select('
            bl_db_purchase.*, 
            bl_db_belanja.nama_barang, 
            bl_db_belanja.nama_bahan_baku, 
            bl_kategori.nama_kategori AS kategori, 
            bl_tipe_produksi.nama_tipe_produksi AS tipe_produksi
        ');
        $this->db->join('bl_db_belanja', 'bl_db_belanja.id = bl_db_purchase.bl_db_belanja_id', 'left');
        $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
        $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
        $this->db->limit($limit, $start);
        return $this->db->get('bl_db_purchase')->result_array();
    }

public function get_by_id($id) {
    $this->db->select('
        bl_db_purchase.*, 
        bl_db_belanja.nama_barang, 
        bl_db_belanja.nama_bahan_baku, 
        bl_db_belanja.id_kategori, 
        bl_db_belanja.id_tipe_produksi, 
        bl_kategori.nama_kategori AS kategori, 
        bl_tipe_produksi.nama_tipe_produksi AS tipe_produksi
    ');
    $this->db->join('bl_db_belanja', 'bl_db_belanja.id = bl_db_purchase.bl_db_belanja_id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->where('bl_db_purchase.id', $id);
    return $this->db->get('bl_db_purchase')->row_array();
}

    public function count_all() {
        return $this->db->count_all('bl_db_purchase');
    }
public function search($belanja_id, $data) {
    $this->db->where('bl_db_belanja_id', $belanja_id);
    $this->db->where('merk', $data['merk']);
    $this->db->where('keterangan', $data['keterangan']);
    $this->db->where('ukuran', $data['ukuran']);
    $this->db->where('unit', $data['unit']);
    $this->db->where('pack', $data['pack']);
    $this->db->where('harga_satuan', $data['harga_satuan']);
    return $this->db->get('bl_db_purchase')->row_array();
}

public function insert($data) {
    $this->db->insert('bl_db_purchase', $data);
    return $this->db->insert_id();
}

public function update($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('bl_db_purchase', $data);
}

public function delete($id) {
    $this->db->where('id', $id);
    return $this->db->delete('bl_db_purchase');
}

public function get_by_name($nama_barang) {
    $this->db->select('
        bl_db_purchase.*, 
        bl_db_belanja.id AS bl_db_belanja_id,
        bl_db_belanja.id_kategori,
        bl_db_belanja.id_tipe_produksi
    ');
    $this->db->from('bl_db_purchase');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->where('bl_db_belanja.nama_barang', $nama_barang);
    return $this->db->get()->row_array();
}

public function get_by_jenis_pengeluaran($jenis_pengeluaran) {
    $this->db->select('*');
    $this->db->from('bl_purchase');
    $this->db->where('jenis_pengeluaran', $jenis_pengeluaran);
    return $this->db->get()->result_array();
}

public function count_storeroom() {
    $this->db->where('jenis_pengeluaran', 'STOREROOM');
    return $this->db->count_all_results('bl_purchase');
}

    
}
