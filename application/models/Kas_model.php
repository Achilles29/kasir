<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kas_model extends CI_Model {
    public function get_all() {
        $this->db->select('bl_kas.*, bl_rekening.nama_rekening');
        $this->db->from('bl_kas');
        $this->db->join('bl_rekening', 'bl_kas.bl_rekening_id = bl_rekening.id', 'left');
        return $this->db->get()->result_array();
    }

    public function insert($data) {
        return $this->db->insert('bl_kas', $data);
    }
public function update($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('bl_kas', $data);
}

public function delete($id) {
    $this->db->where('id', $id);
    return $this->db->delete('bl_kas');
}

}
