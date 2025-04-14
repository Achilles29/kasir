<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher_model extends CI_Model {
    public function get_all_voucher() {
        return $this->db->get('pr_voucher')->result_array();
    }

    public function get_voucher($id) {
        return $this->db->where('id', $id)->get('pr_voucher')->row_array();
    }

    public function insert_voucher($data) {
        return $this->db->insert('pr_voucher', $data);
    }

    public function update_voucher($id, $data) {
        return $this->db->where('id', $id)->update('pr_voucher', $data);
    }

    public function delete_voucher($id) {
        return $this->db->where('id', $id)->delete('pr_voucher');
    }
    // 🔍 Fungsi Filter Voucher (Kode, Status, Tanggal)
    public function get_filtered_voucher($search = "") {
        $this->db->select("pr_voucher.*, pr_produk.nama_produk");
        $this->db->from("pr_voucher");
        $this->db->join("pr_produk", "pr_voucher.produk_id = pr_produk.id", "left");

        // 🔍 Filter berdasarkan kode voucher
        if (!empty($search)) {
            $this->db->like("pr_voucher.kode_voucher", $search);
        }

        $this->db->order_by("pr_voucher.tanggal_mulai", "DESC");
        return $this->db->get()->result_array();
    }



}
