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
    public function generate_kode_refund()
    {
        $prefix = 'RF/' . date('ymd') . '/';
        $last = $this->db->like('kode_refund', $prefix)
                         ->order_by('kode_refund', 'DESC')
                         ->limit(1)
                         ->get('pr_refund')
                         ->row();

        $next = 1;
        if ($last) {
            $last_number = (int) substr($last->kode_refund, -4);
            $next = $last_number + 1;
        }

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
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

    public function get_refund_by_kode($kode_refund)
    {
        $this->db->select('r.*, p.nama_produk, e.nama_extra, k.nama_kategori, d.nama_divisi');
        $this->db->from('pr_refund r');
        $this->db->join('pr_produk p', 'r.pr_produk_id = p.id', 'left');
        $this->db->join('pr_detail_extra de', 'r.detail_extra_id = de.id', 'left');
        $this->db->join('pr_produk_extra e', 'r.produk_extra_id = e.id', 'left');
        $this->db->join('pr_kategori k', 'p.kategori_id = k.id', 'left');
        $this->db->join('pr_divisi d', 'k.dr_divisi_id = d.id', 'left');
        $this->db->where('r.kode_refund', $kode_refund);
        $this->db->order_by('d.nama_divisi, r.nama_produk');
        return $this->db->get()->result();
    }


}