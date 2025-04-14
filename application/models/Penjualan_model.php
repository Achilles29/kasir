<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_model extends CI_Model {


public function get_rekapitulasi_penjualan($tanggal_awal, $tanggal_akhir) {
    $this->db->select('DATE(p.tanggal) AS tanggal, r.nama_rekening, SUM(p.penjualan) AS total');
    $this->db->from('bl_penjualan_majoo p');
    $this->db->join('bl_rekening r', 'p.rekening_id = r.id', 'left');
    $this->db->where('p.tanggal >=', $tanggal_awal);
    $this->db->where('p.tanggal <=', $tanggal_akhir);
    $this->db->group_by(['DATE(p.tanggal)', 'r.nama_rekening']);
    $this->db->order_by('DATE(p.tanggal)', 'ASC');
    return $this->db->get()->result_array();
}

}
