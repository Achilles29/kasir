<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JenisPengeluaran_model extends CI_Model {
    /**
     * Mendapatkan semua data dari tabel `bl_jenis_pengeluaran`
     */
    public function get_all() {
        $this->db->select('*');
        $this->db->from('bl_jenis_pengeluaran');
        return $this->db->get()->result_array();
    }

    /**
     * Mencari data berdasarkan ID
     */
    public function get_by_id($id) {
        $this->db->select('*');
        $this->db->from('bl_jenis_pengeluaran');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    /**
     * Menambahkan data baru
     */
    public function insert($data) {
        $this->db->insert('bl_jenis_pengeluaran', $data);
        return $this->db->insert_id();
    }

    /**
     * Memperbarui data berdasarkan ID
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('bl_jenis_pengeluaran', $data);
    }

    /**
     * Menghapus data berdasarkan ID
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('bl_jenis_pengeluaran');
    }
}
