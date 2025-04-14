<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DailyInventory_model extends CI_Model {


public function get_all($limit, $start) {
    $this->db->select('
        bl_daily_inventory.*,
        bl_purchase.tanggal AS tanggal,
        bl_db_belanja.nama_barang,
        bl_jenis_pengeluaran.nama_jenis_pengeluaran AS jenis_pengeluaran
    ');
    $this->db->from('bl_daily_inventory');
    $this->db->join('bl_purchase', 'bl_purchase.id = bl_daily_inventory.bl_purchase_id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_belanja.id = bl_purchase.bl_db_belanja_id', 'left');
    $this->db->join('bl_jenis_pengeluaran', 'bl_jenis_pengeluaran.nama_jenis_pengeluaran = bl_purchase.jenis_pengeluaran', 'left');
    $this->db->limit($limit, $start);
    $this->db->order_by('bl_daily_inventory.id', 'DESC');
    return $this->db->get()->result_array();
}


public function insert($data) {
    return $this->db->insert('bl_daily_inventory', $data);
}

public function update_by_purchase_id($purchase_id, $data) {
    $this->db->where('bl_purchase_id', $purchase_id);
    return $this->db->update('bl_daily_inventory', $data);
}

public function delete_by_purchase_id($purchase_id) {
    $this->db->where('bl_purchase_id', $purchase_id);
    return $this->db->delete('bl_daily_inventory');
}
}
