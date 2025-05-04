<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Generate Kode Pelanggan
    private function generate_kode_pelanggan() {
        $tanggal = date("Ymd");
        $this->db->select("MAX(id) as max_id");
        $this->db->from("pr_customer");
        $result = $this->db->get()->row();
        $next_id = str_pad(($result->max_id + 1), 4, "0", STR_PAD_LEFT);
        return $tanggal . $next_id;
    }

public function get_all_customers($limit, $start, $search = '')
{
    $this->db->select("c.*, COALESCE(SUM(p.jumlah_poin), 0) as total_poin");
    $this->db->from("pr_customer c");
    $this->db->join("pr_customer_poin p", "p.customer_id = c.id AND p.status = 'aktif'", "left");

    if (!empty($search)) {
        $this->db->group_start();
        $this->db->like("c.nama", $search);
        $this->db->or_like("c.telepon", $search);
        $this->db->or_like("c.kode_pelanggan", $search);
        $this->db->group_end();
    }

    $this->db->group_by("c.id");
    $this->db->order_by("c.created_at", "DESC");
    $this->db->limit($limit, $start);

    return $this->db->get()->result_array();
}


    // Hitung total pelanggan untuk pagination
    public function count_customers($search = '') {
        $this->db->from("pr_customer");

        if (!empty($search)) {
            $this->db->like("nama", $search);
            $this->db->or_like("telepon", $search);
            $this->db->or_like("kode_pelanggan", $search);
        }

        return $this->db->count_all_results();
    }
    // Tambah pelanggan baru
    public function insert_customer($data) {
        $data['kode_pelanggan'] = $this->generate_kode_pelanggan();
        return $this->db->insert("pr_customer", $data);
    }

    // Ambil data pelanggan berdasarkan ID
    public function get_customer_by_id($id) {
        return $this->db->get_where("pr_customer", ["id" => $id])->row_array();
    }

    // Edit pelanggan
    public function update_customer($id, $data) {
        $this->db->where("id", $id);
        return $this->db->update("pr_customer", $data);
    }

    // Hapus pelanggan
    public function delete_customer($id) {
        return $this->db->delete("pr_customer", ["id" => $id]);
    }

public function get_transaksi_by_customer($customer_id, $start_date, $end_date, $search = '') {
    $this->db->select("t.tanggal, p.nama_produk, dt.jumlah, dt.subtotal");
    $this->db->from("pr_transaksi t");
    $this->db->join("pr_detail_transaksi dt", "dt.pr_transaksi_id = t.id");
    $this->db->join("pr_produk p", "p.id = dt.pr_produk_id");
    $this->db->where("t.customer_id", $customer_id);
    $this->db->where("DATE(t.tanggal) >=", $start_date);
    $this->db->where("DATE(t.tanggal) <=", $end_date);

    if (!empty($search)) {
        $this->db->like("p.nama_produk", $search);
    }

    return $this->db->get()->result_array();
}
public function get_poin_by_customer($customer_id) {
    return $this->db->get_where('pr_customer_poin', ['customer_id' => $customer_id])->result_array();
}
public function get_transaksi_with_detail($customer_id, $start, $end, $search = '') {
    $this->db->select("t.id, t.tanggal, t.no_transaksi, t.total_penjualan");
    $this->db->from("pr_transaksi t");
    $this->db->where("t.customer_id", $customer_id);
    $this->db->where("DATE(t.tanggal) >=", $start);
    $this->db->where("DATE(t.tanggal) <=", $end);
    $this->db->order_by("t.tanggal", "DESC");
    $transaksi = $this->db->get()->result_array();

    $result = [];

    foreach ($transaksi as $t) {
        // Ambil detail produk
        $this->db->select("d.id, p.nama_produk, d.jumlah, d.harga, (d.jumlah * d.harga) as subtotal");
        $this->db->from("pr_detail_transaksi d");
        $this->db->join("pr_produk p", "p.id = d.pr_produk_id");
        $this->db->where("d.pr_transaksi_id", $t['id']);
        if (!empty($search)) {
            $this->db->like("p.nama_produk", $search);
        }
        $detail = $this->db->get()->result_array();

        foreach ($detail as &$d) {
            // Ambil extra per produk
            $this->db->select("e.nama_extra, x.jumlah, x.harga, x.subtotal");
            $this->db->from("pr_detail_extra x");
            $this->db->join("pr_produk_extra e", "e.id = x.pr_produk_extra_id");
            $this->db->where("x.detail_transaksi_id", $d['id']);
            $d['extra'] = $this->db->get()->result_array();
        }

        $result[] = [
            'transaksi' => $t,
            'detail'    => $detail
        ];
    }

    return $result;
}

}