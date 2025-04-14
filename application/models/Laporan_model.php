<?php
class Laporan_model extends CI_Model {



public function filter_transaksi($search = '', $tanggal_awal = '', $tanggal_akhir = '')
{
  $this->db->select('t.id, t.no_transaksi, t.tanggal, t.waktu_order, t.waktu_bayar, jo.jenis_order, t.total_penjualan, t.total_pembayaran');
  $this->db->from('pr_transaksi t');
  $this->db->join('pr_jenis_order jo', 'jo.id = t.jenis_order_id', 'left');

  if ($search) {
    $this->db->like('t.no_transaksi', $search);
  }

  if ($tanggal_awal && $tanggal_akhir) {
    $this->db->where('DATE(t.tanggal) >=', $tanggal_awal);
    $this->db->where('DATE(t.tanggal) <=', $tanggal_akhir);
  }

  $this->db->order_by('t.tanggal', 'DESC');
  return $this->db->get()->result_array();
}


  // Fungsi tambahan tetap
  public function get_transaksi($id)
  {
    return $this->db->get_where('pr_transaksi', ['id' => $id])->row_array();
  }

  public function get_detail_produk($id)
  {
    return $this->db->select('d.*, p.nama_produk')
                    ->from('pr_detail_transaksi d')
                    ->join('pr_produk p', 'p.id = d.pr_produk_id')
                    ->where('d.pr_transaksi_id', $id)
                    ->get()->result_array();
  }

  public function get_pembayaran($id)
  {
    return $this->db->select('p.*, m.metode_pembayaran')
                    ->from('pr_pembayaran p')
                    ->join('pr_metode_pembayaran m', 'm.id = p.metode_id')
                    ->where('p.transaksi_id', $id)
                    ->get()->result_array();
  }

  public function get_refund($id)
  {
    return $this->db->get_where('pr_refund', ['pr_transaksi_id' => $id])->row_array();
  }
  public function get_void($id)
  {
    return $this->db->get_where('pr_void', ['pr_transaksi_id' => $id])->row_array();
  }


  public function get_ringkasan()
  {
    return $this->db->select('t.id, t.no_transaksi, t.waktu_order, t.waktu_bayar, jo.jenis_order, t.total_penjualan')
                    ->from('pr_transaksi t')
                    ->join('pr_jenis_order jo', 'jo.id = t.jenis_order_id', 'left')
                    ->order_by('t.waktu_order', 'DESC')
                    ->get()->result_array();
  }


}
