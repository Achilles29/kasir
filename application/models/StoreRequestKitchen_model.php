<?php
class StoreRequestKitchen_model extends CI_Model {
public function insert($data) {
    // Pastikan jenis_pengeluaran selalu 2
    if ($data['jenis_pengeluaran'] !== 3) {
        $data['jenis_pengeluaran'] = 3;
    }

    $this->db->insert('bl_store_request_kitchen', $data);
    return $this->db->insert_id();
}


    public function update_status($id, $status) {
        $this->db->where('id', $id)->update('bl_store_request_kitchen', ['status' => $status]);
    }
public function get_by_id($id) {
    $this->db->select('
        bl_store_request_kitchen.*, 
        bl_db_belanja.nama_barang,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan
    ');
    $this->db->join('bl_gudang', 'bl_gudang.bl_db_purchase_id = bl_store_request_kitchen.bl_db_purchase_id', 'left');
    $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->where('bl_store_request_kitchen.id', $id);
    return $this->db->get('bl_store_request_kitchen')->row_array();
}


public function update($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('bl_store_request_kitchen', $data);
}


public function delete($id) {
    $this->db->where('id', $id);
    return $this->db->delete('bl_store_request_kitchen');
}

    public function count_filtered($tanggal_awal, $tanggal_akhir) {
        $this->db->where('tanggal >=', $tanggal_awal);
        $this->db->where('tanggal <=', $tanggal_akhir);
        return $this->db->count_all_results('bl_store_request_kitchen');
    }

    public function get_filtered($tanggal_awal, $tanggal_akhir, $limit, $start) {
        $this->db->select('
            bl_store_request_kitchen.*, 
            bl_db_belanja.nama_barang, 
            bl_db_purchase.merk, 
            bl_db_purchase.keterangan, 
            bl_db_purchase.ukuran, 
            bl_db_purchase.unit, 
            bl_db_purchase.harga_satuan
        ');
        $this->db->join('bl_db_purchase', 'bl_store_request_kitchen.bl_db_purchase_id = bl_db_purchase.id', 'left');
        $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
        $this->db->where('bl_store_request_kitchen.tanggal >=', $tanggal_awal);
        $this->db->where('bl_store_request_kitchen.tanggal <=', $tanggal_akhir);
        $this->db->limit($limit, $start);
        return $this->db->get('bl_store_request_kitchen')->result_array();
    }
public function search_table($query) {
    $this->db->select('
        bl_store_request_kitchen.*,
        bl_db_belanja.nama_barang,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.harga_satuan,
        bl_jenis_pengeluaran.nama_jenis_pengeluaran
    ');
    $this->db->from('bl_store_request_kitchen');
    $this->db->join('bl_db_purchase', 'bl_store_request_kitchen.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_jenis_pengeluaran', 'bl_store_request_kitchen.jenis_pengeluaran = bl_jenis_pengeluaran.id', 'left');

    // Tambahkan filter pencarian
    $this->db->group_start();
    $this->db->like('bl_db_belanja.nama_barang', $query);
    $this->db->or_like('bl_db_purchase.merk', $query);
    $this->db->or_like('bl_jenis_pengeluaran.nama_jenis_pengeluaran', $query);
    $this->db->group_end();

    // Urutkan hasil berdasarkan tanggal terbaru
    $this->db->order_by('bl_store_request_kitchen.tanggal', 'DESC');

    return $this->db->get()->result_array();
}


}

