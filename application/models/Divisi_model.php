<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Divisi_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all divisi
     * 
     * @return array
     */
    public function get_all() {
        $this->db->select('*');
        $this->db->from('bl_divisi');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_divisi() {
        $this->db->select('pr_divisi.*, COUNT(pr_kategori.id) as jumlah_kategori');
        $this->db->from('pr_divisi');
        $this->db->join('pr_kategori', 'pr_kategori.pr_divisi_id = pr_divisi.id', 'left');
        $this->db->group_by('pr_divisi.id');
        $this->db->order_by('pr_divisi.urutan_tampilan', 'ASC');
        return $this->db->get()->result_array();
    }
    public function get_divisi_by_id($id) {
        return $this->db->get_where('pr_divisi', ['id' => $id])->row_array();
    }

    public function insert_divisi($data) {
        return $this->db->insert('pr_divisi', $data);
    }

    public function update_divisi($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('pr_divisi', $data);
    }
    
    public function delete_divisi($id) {
        return $this->db->delete('pr_divisi', ['id' => $id]);
    }


    public function cek_urutan_tampilan($urutan) {
        return $this->db->get_where('pr_divisi', ['urutan_tampilan' => $urutan])->row_array();
    }

}