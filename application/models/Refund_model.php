<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refund_model extends CI_Model {

    public function get_filtered_refunds($start_date, $end_date) {
        $this->db->select('bl_refund.*, bl_rekening.nama_rekening AS rekening_name');
        $this->db->from('bl_refund');
        $this->db->join('bl_rekening', 'bl_refund.rekening = bl_rekening.id', 'left');
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->order_by('tanggal', 'ASC');
        return $this->db->get()->result_array();
    }

    public function insert_refund($data) {
        return $this->db->insert('bl_refund', $data);
    }

    public function get_refund_by_id($id) {
        return $this->db->get_where('bl_refund', ['id' => $id])->row_array();
    }

    public function update_refund($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('bl_refund', $data);
    }

    public function delete_refund($id) {
        return $this->db->delete('bl_refund', ['id' => $id]);
    }
}
