<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model {
    public function get_all() {
        $this->db->select('*');
        $this->db->from('bl_kategori'); // Sesuaikan nama tabel kategori di database Anda
        return $this->db->get()->result_array();
    }

    public function get_by_id($id) {
        $this->db->select('*');
        $this->db->from('bl_kategori'); // Sesuaikan nama tabel kategori
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }
    public function get_all_kategori() {
        $this->db->select('pr_kategori.*, pr_divisi.nama_divisi, COUNT(pr_produk.id) as jumlah_produk');
        $this->db->from('pr_kategori');
        $this->db->join('pr_divisi', 'pr_kategori.pr_divisi_id = pr_divisi.id', 'left');
        $this->db->join('pr_produk', 'pr_produk.kategori_id = pr_kategori.id', 'left');
        $this->db->group_by('pr_kategori.id');
        $this->db->order_by('pr_kategori.urutan', 'ASC');
        return $this->db->get()->result_array();
    }

    public function check_duplicate_urutan($urutan, $id = null) {
        $this->db->where('urutan', $urutan);
        if ($id) {
            $this->db->where('id !=', $id);
        }
        return $this->db->get('pr_kategori')->num_rows() > 0;
    }
    public function insert_kategori($data) {
        return $this->db->insert('pr_kategori', $data);
    }
    
    public function update_kategori($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('pr_kategori', $data);
    }
    
    public function delete_kategori($id) {
        return $this->db->delete('pr_kategori', ['id' => $id]);
    }
    
    public function get_kategori_by_id($id) {
        return $this->db->get_where('pr_kategori', ['id' => $id])->row_array();
    }
    

}
