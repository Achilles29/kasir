<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StokOpname_model extends CI_Model {

public function insert_or_update_batch($data) {
    foreach ($data as $item) {
        $this->db->where('bl_db_belanja_id', $item['bl_db_belanja_id']);
        $this->db->where('bl_db_purchase_id', $item['bl_db_purchase_id']);
        $this->db->where('tanggal', $item['tanggal']);
        $existing = $this->db->get('bl_stok_opname')->row_array();

        if ($existing) {
            // Jika sudah ada, update
            $this->db->where('id', $existing['id']);
            $this->db->update('bl_stok_opname', $item);
        } else {
            // Jika belum ada, insert
            $this->db->insert('bl_stok_opname', $item);
        }
    }
    return true;
}


public function get_all($month, $year, $limit = 10, $start = 0, $sort_criteria = []) {
    $this->db->select('
        bl_stok_opname.*,
        bl_db_belanja.nama_barang,
        bl_db_belanja.nama_bahan_baku,
        bl_kategori.nama_kategori AS kategori,
        bl_tipe_produksi.nama_tipe_produksi AS tipe,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.pack,
        bl_db_purchase.harga_satuan AS harga
    ');
    $this->db->from('bl_stok_opname');
    $this->db->join('bl_db_belanja', 'bl_stok_opname.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_db_purchase', 'bl_stok_opname.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->where('MONTH(bl_stok_opname.tanggal)', $month);
    $this->db->where('YEAR(bl_stok_opname.tanggal)', $year);

    // Apply sorting criteria, specify table alias for ambiguous columns
    if (!empty($sort_criteria)) {
        foreach ($sort_criteria as $column => $direction) {
            if ($column == 'kategori') {
                $this->db->order_by('bl_kategori.nama_kategori', $direction);
            } elseif ($column == 'tipe') {
                $this->db->order_by('bl_tipe_produksi.nama_tipe_produksi', $direction);
            } else {
                $this->db->order_by($column, $direction);
            }
        }
    } else {
        // Default sorting
        $this->db->order_by('bl_kategori.nama_kategori', 'ASC');
        $this->db->order_by('bl_db_belanja.nama_barang', 'ASC');
        $this->db->order_by('bl_db_belanja.nama_bahan_baku', 'ASC');
        $this->db->order_by('bl_tipe_produksi.nama_tipe_produksi', 'ASC');
    }

    // Apply limit and offset for pagination
    $this->db->limit($limit, $start);

    return $this->db->get()->result_array();
}


    public function count_all($month, $year) {
        $this->db->from('bl_stok_opname');
        $this->db->where('MONTH(tanggal)', $month);
        $this->db->where('YEAR(tanggal)', $year);
        return $this->db->count_all_results();
    }

    // Search function for stock opname data
public function search($keyword, $month, $year, $limit) {
    $this->db->select('
        bl_stok_opname.*,
        bl_db_belanja.nama_barang,
        bl_db_belanja.nama_bahan_baku,
        bl_kategori.nama_kategori AS kategori,
        bl_tipe_produksi.nama_tipe_produksi AS tipe,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.pack,
        bl_db_purchase.harga_satuan AS harga
    ');
    $this->db->from('bl_stok_opname');
    $this->db->join('bl_db_belanja', 'bl_stok_opname.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_db_purchase', 'bl_stok_opname.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->where('MONTH(bl_stok_opname.tanggal)', $month);
    $this->db->where('YEAR(bl_stok_opname.tanggal)', $year);

    // Add search conditions
    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('bl_db_belanja.nama_barang', $keyword);
        $this->db->or_like('bl_db_belanja.nama_bahan_baku', $keyword);
        $this->db->or_like('bl_db_purchase.merk', $keyword);
        $this->db->or_like('bl_kategori.nama_kategori', $keyword);
        $this->db->group_end();
    }

    $this->db->limit($limit);
    return $this->db->get()->result_array();
}
    public function get_by_month_year($month, $year) {
        $this->db->select('
            bl_db_belanja_id,
            bl_db_purchase_id,
            stok_akhir
        ');
        $this->db->from('bl_stok_opname');
        $this->db->where('MONTH(tanggal)', $month);
        $this->db->where('YEAR(tanggal)', $year);
        $this->db->where('stok_akhir >', 0);  // Filter to include only those with stok_akhir > 0
        return $this->db->get()->result_array();
    }

}
?>
