<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poin_model extends CI_Model {
    public function get_all_poin() {
        $this->db->select('pr_poin.*, pr_produk.nama_produk');
        $this->db->join('pr_produk', 'pr_produk.id = pr_poin.produk_id', 'left');
        return $this->db->get('pr_poin')->result_array();
    }

public function simpan_poin($data) {
    if (!empty($data['id'])) {
        // Update jika ada ID
        $this->db->where('id', $data['id']);
        $this->db->update('pr_poin', $data);
    } else {
        // Insert data baru
        $this->db->insert('pr_poin', $data);
    }
}
public function update_poin($id, $data) {
    $this->db->where('id', $id);
    $this->db->update('pr_poin', $data);
}

    public function hapus_poin($id) {
        $this->db->where('id', $id)->delete('pr_poin');
    }
}
