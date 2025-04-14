<?php
class StoreRequestBar_model extends CI_Model {
public function insert($data) {
    // Pastikan jenis_pengeluaran selalu 2
    if ($data['jenis_pengeluaran'] !== 2) {
        $data['jenis_pengeluaran'] = 2;
    }

    $this->db->insert('bl_store_request_bar', $data);
    return $this->db->insert_id();
}


public function update_status($id, $status) {
    $this->db->where('id', $id);
    $this->db->update('bl_store_request_bar', ['status' => $status]);
}

public function get_by_id($id) {
    $this->db->select('
        bl_store_request_bar.*, 
        bl_db_belanja.nama_barang,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan
    ');
    $this->db->join('bl_gudang', 'bl_gudang.bl_db_purchase_id = bl_store_request_bar.bl_db_purchase_id', 'left');
    $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->where('bl_store_request_bar.id', $id);
    return $this->db->get('bl_store_request_bar')->row_array();
}


public function update($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('bl_store_request_bar', $data);
}


public function delete($id) {
    $this->db->where('id', $id);
    return $this->db->delete('bl_store_request_bar');
}

    public function count_filtered($tanggal_awal, $tanggal_akhir) {
        $this->db->where('tanggal >=', $tanggal_awal);
        $this->db->where('tanggal <=', $tanggal_akhir);
        return $this->db->count_all_results('bl_store_request_bar');
    }

    public function get_filtered($tanggal_awal, $tanggal_akhir, $limit, $start) {
        $this->db->select('
            bl_store_request_bar.*, 
            bl_db_belanja.nama_barang, 
            bl_db_purchase.merk, 
            bl_db_purchase.keterangan, 
            bl_db_purchase.ukuran, 
            bl_db_purchase.unit, 
            bl_db_purchase.harga_satuan
        ');
        $this->db->join('bl_db_purchase', 'bl_store_request_bar.bl_db_purchase_id = bl_db_purchase.id', 'left');
        $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
        $this->db->where('bl_store_request_bar.tanggal >=', $tanggal_awal);
        $this->db->where('bl_store_request_bar.tanggal <=', $tanggal_akhir);
        $this->db->limit($limit, $start);
        return $this->db->get('bl_store_request_bar')->result_array();
    }
public function search_table($query) {
    $this->db->select('
        bl_store_request_bar.*,
        bl_db_belanja.nama_barang,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.harga_satuan,
        bl_jenis_pengeluaran.nama_jenis_pengeluaran
    ');
    $this->db->from('bl_store_request_bar');
    $this->db->join('bl_db_purchase', 'bl_store_request_bar.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_jenis_pengeluaran', 'bl_store_request_bar.jenis_pengeluaran = bl_jenis_pengeluaran.id', 'left');

    // Tambahkan filter pencarian
    $this->db->group_start();
    $this->db->like('bl_db_belanja.nama_barang', $query);
    $this->db->or_like('bl_db_purchase.merk', $query);
    $this->db->or_like('bl_jenis_pengeluaran.nama_jenis_pengeluaran', $query);
    $this->db->group_end();

    // Urutkan hasil berdasarkan tanggal terbaru
    $this->db->order_by('bl_store_request_bar.tanggal', 'DESC');

    return $this->db->get()->result_array();
}


}

// class Gudang_model extends CI_Model {
//     public function search_barang($keyword) {
//         $this->db->select('
//             bl_gudang.*, 
//             bl_db_belanja.nama_barang, 
//             bl_db_purchase.merk, 
//             bl_db_purchase.keterangan, 
//             bl_db_purchase.ukuran, 
//             bl_db_purchase.unit, 
//             bl_db_purchase.harga_satuan, 
//             bl_gudang.stok_akhir
//         ');
//         $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
//         $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
//         $this->db->like('bl_db_belanja.nama_barang', $keyword);
//         return $this->db->get('bl_gudang')->result_array();
//     }

//     public function update_stok_keluar($purchase_id, $quantity) {
//         $this->db->set('stok_keluar', 'stok_keluar + ' . $quantity, FALSE);
//         $this->db->where('bl_db_purchase_id', $purchase_id);
//         $this->db->update('bl_gudang');
//     }

//     public function get_stok_akhir($bl_db_purchase_id) {
//     $this->db->select('stok_akhir');
//     $this->db->from('bl_gudang');
//     $this->db->where('bl_db_purchase_id', $bl_db_purchase_id);
//     $query = $this->db->get();

//     if ($query->num_rows() > 0) {
//         return $query->row()->stok_akhir;
//     } else {
//         return 0; // Default jika data tidak ditemukan
//     }
// }
// }
