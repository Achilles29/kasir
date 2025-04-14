<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MetodePembayaran_model extends CI_Model {

    // Fungsi untuk mengambil semua data metode pembayaran
    public function get_all() {
        $this->db->select('*');
        $this->db->from('metode_pembayaran'); // Pastikan nama tabel sesuai dengan database Anda
        $query = $this->db->get();
        return $query->result_array();
    }
}
