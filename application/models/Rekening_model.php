<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekening_model extends CI_Model {

    // Mengambil semua data rekening
    public function get_all() {
        $this->db->select('id, nama_rekening');
        return $this->db->get('bl_rekening')->result_array();
    }
    // Mendapatkan data rekening berdasarkan ID
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('bl_rekening')->row_array();
    }
public function get_rekening_list() {
    $this->db->select('id, nama_rekening');
    $this->db->from('bl_rekening');
    $query = $this->db->get();
    return $query->result_array(); // This returns an array of rekening
}
    
}


    