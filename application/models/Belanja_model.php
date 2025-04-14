<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Belanja_model extends CI_Model {
    public function get_all($limit, $start) {
        $this->db->select('bl_db_belanja.*, bl_kategori.nama_kategori, bl_tipe_produksi.nama_tipe_produksi');
        $this->db->join('bl_kategori', 'bl_kategori.id = bl_db_belanja.id_kategori');
        $this->db->join('bl_tipe_produksi', 'bl_tipe_produksi.id = bl_db_belanja.id_tipe_produksi');
        $this->db->limit($limit, $start);
        return $this->db->get('bl_db_belanja')->result_array();
    }

    public function count_all() {
        return $this->db->count_all('bl_db_belanja');
    }
public function insert($data) {
    $this->db->insert('bl_db_belanja', $data);
    return $this->db->insert_id();
}
public function search_exact($nama_barang, $nama_bahan_baku, $id_kategori, $id_tipe_produksi) {
    $this->db->where('nama_barang', $nama_barang);
    $this->db->where('nama_bahan_baku', $nama_bahan_baku);
    $this->db->where('id_kategori', $id_kategori);
    $this->db->where('id_tipe_produksi', $id_tipe_produksi);
    return $this->db->get('bl_db_belanja')->row_array();
}

    public function search($keyword) {
        $this->db->select('bl_db_belanja.*, bl_kategori.nama_kategori, bl_tipe_produksi.nama_tipe_produksi');
        $this->db->join('bl_kategori', 'bl_kategori.id = bl_db_belanja.id_kategori', 'left');
        $this->db->join('bl_tipe_produksi', 'bl_tipe_produksi.id = bl_db_belanja.id_tipe_produksi', 'left');
        $this->db->like('bl_db_belanja.nama_barang', $keyword);
        $this->db->or_like('bl_db_belanja.nama_bahan_baku', $keyword);
        return $this->db->get('bl_db_belanja')->result_array();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('bl_db_belanja', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('bl_db_belanja');
    }
public function get_by_id($id) {
    $this->db->select('id, nama_barang, nama_bahan_baku, id_kategori, id_tipe_produksi');
    $this->db->from('bl_db_belanja');
    $this->db->where('id', $id);
    return $this->db->get()->row_array();
}


}
