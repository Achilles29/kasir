<?php
class Transaksi_model extends CI_Model {
    public function get_transaksi($id) {
        return $this->db->get_where('pr_transaksi', ['id' => $id])->row_array();
    }

    public function get_detail_transaksi($id) {
        return $this->db
            ->select('pr_detail_transaksi.*, pr_produk.nama_produk')
            ->join('pr_produk', 'pr_produk.id = pr_detail_transaksi.pr_produk_id')
            ->get_where('pr_detail_transaksi', ['pr_detail_transaksi.pr_transaksi_id' => $id])
            ->result_array();
    }
}