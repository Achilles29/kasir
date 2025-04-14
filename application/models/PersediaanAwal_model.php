<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PersediaanAwal_model extends CI_Model {
public function get_all($limit, $start) {
    $this->db->select('
        bl_persediaan_awal.*,
        bl_db_belanja.nama_barang,
        bl_db_belanja.nama_bahan_baku,
        bl_kategori.nama_kategori AS kategori,
        bl_tipe_produksi.nama_tipe_produksi AS tipe_produksi,
        bl_db_purchase.merk,
        bl_db_purchase.ukuran,
        bl_db_purchase.harga_satuan
    ');
    $this->db->from('bl_persediaan_awal');
    $this->db->join('bl_db_belanja', 'bl_db_belanja.id = bl_persediaan_awal.bl_db_belanja_id', 'left');
    $this->db->join('bl_db_purchase', 'bl_db_purchase.id = bl_persediaan_awal.bl_db_purchase_id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->limit($limit, $start);
    $this->db->order_by('bl_persediaan_awal.tanggal', 'DESC');
    return $this->db->get()->result_array();
}

    public function count_all() {
        return $this->db->count_all('bl_persediaan_awal');
    }

public function insert($data) {
    if ($this->db->insert('bl_persediaan_awal', $data)) {
        log_message('info', 'Insert Query: ' . $this->db->last_query());
        return true;
    } else {
        log_message('error', 'Database Error: ' . json_encode($this->db->error()));
        return false;
    }
}


    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('bl_persediaan_awal');
    }

    public function search_barang($keyword) {
        $this->db->select('
            bl_db_purchase.id AS purchase_id,
            bl_db_belanja.id AS belanja_id,
            bl_db_belanja.nama_barang,
            bl_db_belanja.nama_bahan_baku,
            bl_db_purchase.merk,
            bl_db_purchase.harga_satuan
        ');
        $this->db->from('bl_db_purchase');
        $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
        $this->db->like('bl_db_belanja.nama_barang', $keyword, 'both');
        return $this->db->get()->result_array();
    }

    public function get_by_id($id) {
        $this->db->select('
            bl_db_purchase.*,
            bl_db_belanja.nama_barang,
            bl_db_belanja.id AS bl_db_belanja_id
        ');
        $this->db->from('bl_db_purchase');
        $this->db->join('bl_db_belanja', 'bl_db_belanja.id = bl_db_purchase.bl_db_belanja_id', 'left');
        $this->db->where('bl_db_purchase.id', $id);
        return $this->db->get()->row_array();
    }
}
