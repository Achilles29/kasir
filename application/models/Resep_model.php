<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Resep_model extends CI_Model {

  public function get_resep_produk()
  {
    return $this->db->select('rp.id, p.nama_produk AS produk, b.nama_barang AS bahan, rp.jumlah, rp.satuan, rp.hpp')
    
                    ->from('pr_resep_produk rp')
                    ->join('pr_produk p', 'p.id = rp.pr_produk_id')
                    ->join('bl_db_belanja b', 'b.id = rp.bahan_id', 'left')
                    ->get()->result_array();
  }

  public function get_resep_base()
  {
    return $this->db->select('rb.id, p.nama_produk AS produk, b.nama_barang AS bahan, rb.jumlah, rb.satuan, rb.hpp')
                    ->from('pr_resep_base rb')
                    ->join('pr_produk p', 'p.id = rb.pr_base_id')
                    ->join('bl_db_belanja b', 'b.id = rb.bahan_id')
                    ->get()->result_array();
  }


public function get_produk()
{
  return $this->db->where('monitor_persediaan', 1)->get('pr_produk')->result_array();
}

public function get_bahan_baku()
{
  return $this->db->get('bl_db_belanja')->result_array();
}


}
