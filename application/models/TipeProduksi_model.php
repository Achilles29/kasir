<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TipeProduksi_model extends CI_Model {
    public function get_all() {
        $this->db->select('*');
        $this->db->from('bl_tipe_produksi'); // Sesuaikan nama tabel tipe produksi di database Anda
        return $this->db->get()->result_array();
    }

    public function get_by_id($id) {
        $this->db->select('*');
        $this->db->from('bl_tipe_produksi'); // Sesuaikan nama tabel tipe produksi
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }
}
