<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MutasiKas_model extends CI_Model {
    public function get_all() {
        $this->db->select('bl_mutasi_kas.*, bl_rekening.nama_rekening');
        $this->db->from('bl_mutasi_kas');
        $this->db->join('bl_rekening', 'bl_mutasi_kas.bl_rekening_id = bl_rekening.id', 'left');
        $this->db->order_by('tanggal', 'ASC');
        return $this->db->get()->result_array();
    }

    public function insert($data) {
        return $this->db->insert('bl_mutasi_kas', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('bl_mutasi_kas', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('bl_mutasi_kas');
    }
}
