<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang_model extends CI_Model {

public function get_all($month, $year, $limit = 10, $start = 0, $sort_criteria = []) {
    $this->db->select('
        bl_gudang.*,
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
    $this->db->from('bl_gudang');
    $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->where('MONTH(bl_gudang.tanggal)', $month);
    $this->db->where('YEAR(bl_gudang.tanggal)', $year);

    if (!empty($sort_criteria)) {
        foreach ($sort_criteria as $column => $direction) {
            $this->db->order_by($column, $direction);
        }
    } else {
        $this->db->order_by('bl_kategori.nama_kategori', 'ASC');
        $this->db->order_by('bl_db_belanja.nama_barang', 'ASC');
        $this->db->order_by('bl_db_belanja.nama_bahan_baku', 'ASC');
        $this->db->order_by('bl_tipe_produksi.nama_tipe_produksi', 'ASC');
    }

    $this->db->limit($limit, $start);
    return $this->db->get()->result_array();
}


    public function count_all($month, $year) {
        $this->db->from('bl_gudang');
        $this->db->where('MONTH(tanggal)', $month);
        $this->db->where('YEAR(tanggal)', $year);
        return $this->db->count_all_results();
    }

public function calculate_stock($data) {
    foreach ($data as &$item) {
        $item['stok_akhir'] = $item['stok_awal'] + $item['stok_masuk'] - $item['stok_keluar'] - $item['stok_terbuang'] + $item['stok_penyesuaian'];
        $item['unit_total'] = $item['stok_akhir'] * $item['ukuran'];
        $item['nilai_total'] = $item['stok_akhir'] * $item['harga'];
    }
    return $data;
}

    public function get_stok_opname($month, $year) {
        $this->db->select('*');
        $this->db->from('bl_stok_opname');
        $this->db->where('MONTH(tanggal)', $month);
        $this->db->where('YEAR(tanggal)', $year);
        return $this->db->get()->result_array();
    }
public function insert_or_update($data) {
    $this->db->where('bl_db_belanja_id', $data['bl_db_belanja_id']);
    $this->db->where('bl_db_purchase_id', $data['bl_db_purchase_id']);
    $this->db->where('MONTH(tanggal)', date('m', strtotime($data['tanggal'])));
    $this->db->where('YEAR(tanggal)', date('Y', strtotime($data['tanggal'])));
    $existing = $this->db->get('bl_gudang')->row_array();

    if ($existing) {
        // Update stok_masuk tanpa mengubah stok_awal
        $data['stok_awal'] = $existing['stok_awal']; 
        $data['stok_masuk'] = ($existing['stok_masuk'] ?? 0) + ($data['stok_masuk'] ?? 0);
        $data['stok_akhir'] = $data['stok_awal'] + $data['stok_masuk'] - ($existing['stok_keluar'] ?? 0) - ($existing['stok_terbuang'] ?? 0) + ($existing['stok_penyesuaian'] ?? 0);

        // Update data
        $this->db->where('id', $existing['id']);
        $this->db->update('bl_gudang', $data);
    } else {
        // Insert data baru
        $data['stok_akhir'] = $data['stok_awal'] + ($data['stok_masuk'] ?? 0) - ($data['stok_keluar'] ?? 0) - ($data['stok_terbuang'] ?? 0) + ($data['stok_penyesuaian'] ?? 0);
        $this->db->insert('bl_gudang', $data);
    }

    // Pastikan stok_akhir selalu diperbarui
    $this->update_stok_akhir($data['bl_db_purchase_id'], $data['tanggal']);
}



public function update_stok_masuk($old_purchase_id, $stok_masuk, $new_purchase_id = null) {
    // Update stok_masuk jika bl_db_purchase_id tetap
    if ($new_purchase_id === null) {
        $this->db->where('bl_db_purchase_id', $old_purchase_id);
        $this->db->update('bl_gudang', ['stok_masuk' => $stok_masuk]);
    } else {
        // Update stok_masuk dengan id pembelian baru
        $this->db->where('bl_db_purchase_id', $old_purchase_id);
        $this->db->update('bl_gudang', [
            'bl_db_purchase_id' => $new_purchase_id,
            'stok_masuk' => $stok_masuk,
        ]);
    }
}

public function get_by_purchase($purchase_id) {
    $this->db->where('bl_db_purchase_id', $purchase_id);
    return $this->db->get('bl_gudang')->row_array();
}

public function update_stok_akhir($bl_db_purchase_id, $tanggal) {

    $bulan = date('m', strtotime($tanggal));
    $tahun = date('Y', strtotime($tanggal));

    // Ambil stok_awal dari `bl_gudang`
    $this->db->select('stok_awal');
    $this->db->where('bl_db_purchase_id', $bl_db_purchase_id);
    $this->db->where('MONTH(tanggal)', $bulan);
    $this->db->where('YEAR(tanggal)', $tahun);
    $existing = $this->db->get('bl_gudang')->row_array();
    $stok_awal = $existing ? $existing['stok_awal'] : 0;

    // Ambil stok_masuk terbaru dari `bl_purchase`
    $this->db->select_sum('kuantitas');
    $this->db->where('bl_db_purchase_id', $bl_db_purchase_id);
    $this->db->where('MONTH(tanggal)', $bulan);
    $this->db->where('YEAR(tanggal)', $tahun);
    $purchase = $this->db->get('bl_purchase')->row_array();
    $stok_masuk = $purchase ? $purchase['kuantitas'] : 0;

    // Ambil stok_keluar dari `bl_store_request`
    $this->db->select_sum('kuantitas');
    $this->db->where('bl_db_purchase_id', $bl_db_purchase_id);
    $this->db->where('MONTH(tanggal)', $bulan);
    $this->db->where('YEAR(tanggal)', $tahun);
    $store_request = $this->db->get('bl_store_request')->row_array();
    $stok_keluar = $store_request ? $store_request['kuantitas'] : 0;

    // Ambil stok_terbuang dan stok_penyesuaian
    $this->db->select('stok_terbuang, stok_penyesuaian');
    $this->db->where('bl_db_purchase_id', $bl_db_purchase_id);
    $this->db->where('MONTH(tanggal)', $bulan);
    $this->db->where('YEAR(tanggal)', $tahun);
    $gudang = $this->db->get('bl_gudang')->row_array();
    $stok_terbuang = $gudang ? $gudang['stok_terbuang'] : 0;
    $stok_penyesuaian = $gudang ? $gudang['stok_penyesuaian'] : 0;

    // Hitung stok_akhir
    $stok_akhir = $stok_awal + $stok_masuk - $stok_keluar - $stok_terbuang + $stok_penyesuaian;

    // Update stok_akhir di `bl_gudang`
    $this->db->where('bl_db_purchase_id', $bl_db_purchase_id);
    $this->db->where('MONTH(tanggal)', $bulan);
    $this->db->where('YEAR(tanggal)', $tahun);
    $this->db->update('bl_gudang', [
        'stok_masuk' => $stok_masuk,
        'stok_keluar' => $stok_keluar,
        'stok_akhir' => $stok_akhir
    ]);
}




// public function update_stok_akhir($id) {
//     $this->db->set('stok_akhir', 'stok_awal + stok_masuk - stok_keluar - stok_terbuang + stok_penyesuaian', FALSE);
//     $this->db->where('id', $id);
//     return $this->db->update('bl_gudang');
// }

public function insert($data) {
    $this->db->insert('bl_gudang', $data);
    $insert_id = $this->db->insert_id();

    // Update stok_akhir setelah insert
    $this->update_stok_akhir($insert_id, $data['tanggal']);

    return $insert_id;
}


public function get_by_purchase_and_date($bl_db_purchase_id, $tanggal) {
    $bulan = date('m', strtotime($tanggal));
    $tahun = date('Y', strtotime($tanggal));

    $this->db->where('bl_db_purchase_id', $bl_db_purchase_id);
    $this->db->where('MONTH(tanggal)', $bulan);
    $this->db->where('YEAR(tanggal)', $tahun);
    return $this->db->get('bl_gudang')->row_array();
}

public function update($id, $data) {
    $this->db->where('id', $id);
    $this->db->update('bl_gudang', $data);

    // Perbaikan: gunakan id yang benar
    $this->update_stok_akhir($id, $data['tanggal']); 
}



    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('bl_gudang');
    }

public function search($query, $month, $year, $limit, $start) {
    $this->db->select('
        bl_gudang.*,
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
    $this->db->from('bl_gudang');
    $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->where('MONTH(bl_gudang.tanggal)', $month);
    $this->db->where('YEAR(bl_gudang.tanggal)', $year);

    // Tambahkan kriteria pencarian
    if (!empty($query)) {
        $this->db->group_start();
        $this->db->like('bl_db_belanja.nama_barang', $query);
        $this->db->or_like('bl_db_belanja.nama_bahan_baku', $query);
        $this->db->or_like('bl_db_purchase.merk', $query);
        $this->db->or_like('bl_kategori.nama_kategori', $query);
        $this->db->group_end();
    }

    $this->db->limit($limit, $start);
    return $this->db->get()->result_array();
}
public function searchv2($query, $month, $year, $limit, $start) {
    $this->db->select('
        bl_gudang.*,
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
    $this->db->from('bl_gudang');
    $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->where('MONTH(bl_gudang.tanggal)', $month);
    $this->db->where('YEAR(bl_gudang.tanggal)', $year);

    // Tambahkan kriteria pencarian
    if (!empty($query)) {
        $this->db->group_start();
        $this->db->like('bl_db_belanja.nama_barang', $query);
        $this->db->or_like('bl_db_belanja.nama_bahan_baku', $query);
        $this->db->or_like('bl_db_purchase.merk', $query);
        $this->db->or_like('bl_kategori.nama_kategori', $query);
        $this->db->group_end();
    }

    $this->db->limit($limit, $start);
    return $this->db->get()->result_array();
}

public function search_barang($query) {
    $this->db->select('
        bl_gudang.bl_db_purchase_id,
        bl_db_belanja.nama_barang,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.harga_satuan AS harga,
        bl_gudang.stok_akhir
    ');
    $this->db->from('bl_gudang');
    $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->like('bl_db_belanja.nama_barang', $query);
    $this->db->or_like('bl_db_purchase.merk', $query);
    return $this->db->get()->result_array();
}

public function search_barang_filtered($query, $bulan, $tahun) {
    $this->db->select('
        bl_gudang.*, 
        bl_db_belanja.nama_barang, 
        bl_db_purchase.merk, 
        bl_db_purchase.keterangan, 
        bl_db_purchase.ukuran, 
        bl_db_purchase.unit, 
        bl_db_purchase.harga_satuan AS harga
    ');
    $this->db->from('bl_gudang');
    $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    
    // Filter pencarian berdasarkan nama barang
    $this->db->like('bl_db_belanja.nama_barang', $query);

    // Tambahkan filter bulan dan tahun
    $this->db->where('MONTH(bl_gudang.tanggal)', $bulan);
    $this->db->where('YEAR(bl_gudang.tanggal)', $tahun);

    return $this->db->get()->result_array();
}



    public function search_barang_ajax($keyword) {
        $this->db->select('
            bl_gudang.*, 
            bl_db_belanja.nama_barang, 
            bl_db_purchase.merk, 
            bl_db_purchase.keterangan, 
            bl_db_purchase.ukuran, 
            bl_db_purchase.unit, 
            bl_db_purchase.harga_satuan, 
            bl_gudang.stok_akhir
        ');
        $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
        $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
        $this->db->like('bl_db_belanja.nama_barang', $keyword);
        return $this->db->get('bl_gudang')->result_array();
    }

public function search_barang_terbuang($keyword) {
    $this->db->select('
        bl_gudang.*,
        bl_db_belanja.nama_barang,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.harga_satuan,
        bl_gudang.stok_akhir
    ');
    $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->like('bl_db_belanja.nama_barang', $keyword);
    return $this->db->get('bl_gudang')->result_array();
}



public function update_stok_keluar($purchase_id, $kuantitas) {
    $this->db->where('bl_db_purchase_id', $purchase_id);
    $gudang = $this->db->get('bl_gudang')->row_array();

    if ($gudang) {
        $stok_keluar_baru = ($gudang['stok_keluar'] ?? 0) + $kuantitas;

        // Update stok keluar dan stok akhir
        $this->db->where('bl_db_purchase_id', $purchase_id);
        $this->db->update('bl_gudang', [
            'stok_keluar' => $stok_keluar_baru,
            'stok_akhir' => $gudang['stok_awal'] + $gudang['stok_masuk'] - $stok_keluar_baru - $gudang['stok_terbuang'] + $gudang['stok_penyesuaian']
        ]);
    }
}


public function reduce_stok_keluar($purchase_id, $kuantitas) {
    $this->db->where('bl_db_purchase_id', $purchase_id);
    $gudang = $this->db->get('bl_gudang')->row_array();

    if ($gudang) {
        $stok_keluar_baru = max(0, ($gudang['stok_keluar'] ?? 0) - $kuantitas);
        $this->db->where('bl_db_purchase_id', $purchase_id);
        $this->db->update('bl_gudang', [
            'stok_keluar' => $stok_keluar_baru,
            'stok_akhir' => $gudang['stok_awal'] + $gudang['stok_masuk'] - $stok_keluar_baru - $gudang['stok_terbuang'] + $gudang['stok_penyesuaian']
        ]);
    }
}

public function get_stok_akhir($bl_db_purchase_id) {
    $this->db->select('stok_akhir');
    $this->db->from('bl_gudang');
    $this->db->where('bl_db_purchase_id', $bl_db_purchase_id);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->row()->stok_akhir;
    } else {
        return 0; // Default jika data tidak ditemukan
    }
}

public function update_stok_terbuang($purchase_id, $selisih) {
    $this->db->where('bl_db_purchase_id', $purchase_id);
    $gudang = $this->db->get('bl_gudang')->row_array();

    if ($gudang) {
        // Perbarui stok_terbuang dan stok_akhir
        $stok_terbuang_baru = max(0, ($gudang['stok_terbuang'] ?? 0) + $selisih);
        $this->db->where('bl_db_purchase_id', $purchase_id);
        $this->db->update('bl_gudang', [
            'stok_terbuang' => $stok_terbuang_baru,
            'stok_akhir' => $gudang['stok_awal'] + $gudang['stok_masuk'] - $gudang['stok_keluar'] - $stok_terbuang_baru + $gudang['stok_penyesuaian']
        ]);
    }
}
public function update_stok_penyesuaian($purchase_id, $selisih) {
    $this->db->where('bl_db_purchase_id', $purchase_id);
    $gudang = $this->db->get('bl_gudang')->row_array();

    if ($gudang) {
        // Perbarui stok_penyesuaian dan stok_akhir
        $stok_penyesuaian_baru = ($gudang['stok_penyesuaian'] ?? 0) + $selisih;
        $this->db->where('bl_db_purchase_id', $purchase_id);
        $this->db->update('bl_gudang', [
            'stok_penyesuaian' => $stok_penyesuaian_baru,
            'stok_akhir' => $gudang['stok_awal'] + $gudang['stok_masuk'] - $gudang['stok_keluar'] - $gudang['stok_terbuang'] + $stok_penyesuaian_baru
        ]);
    }
}


    // Get all data filtered by is_gudang = 1 and grouped by bl_db_belanja_id
public function get_all_filtered($month, $year, $limit = 10, $start = 0, $sort_criteria = []) {
    $this->db->select('
        bl_db_belanja.id AS bl_db_belanja_id,
        bl_db_belanja.nama_barang,
        bl_db_belanja.nama_bahan_baku,
        bl_kategori.nama_kategori AS kategori,
        bl_tipe_produksi.nama_tipe_produksi AS tipe,
        bl_db_belanja.id_kategori AS kategori_id,
        bl_db_purchase.id AS bl_db_purchase_id,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.pack,
        bl_db_purchase.harga_satuan AS harga,
        SUM(bl_gudang.stok_awal) AS stok_awal,
        SUM(bl_gudang.stok_masuk) AS stok_masuk,
        SUM(bl_gudang.stok_keluar) AS stok_keluar,
        SUM(bl_gudang.stok_terbuang) AS stok_terbuang,
        SUM(bl_gudang.stok_penyesuaian) AS stok_penyesuaian,
        SUM(bl_gudang.stok_akhir) AS stok_akhir,
        SUM(bl_gudang.stok_akhir * bl_db_purchase.ukuran) AS unit_total,
        SUM(bl_gudang.stok_akhir * bl_db_purchase.harga_satuan) AS nilai_total
    ');
    $this->db->from('bl_gudang');
    $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->where('MONTH(bl_gudang.tanggal)', $month);
    $this->db->where('YEAR(bl_gudang.tanggal)', $year);
    $this->db->where('bl_db_belanja.is_gudang', 1);
    $this->db->group_by('bl_db_belanja.id');

    // Corrected ORDER BY clause
    if (!empty($sort_criteria)) {
        foreach ($sort_criteria as $column => $direction) {
            $this->db->order_by($column, $direction);
        }
    } else {
        // Use the correct column name 'bl_kategori.id' instead of 'bl_kategori.id_kategori'
        $this->db->order_by('bl_kategori.id', 'ASC');
        $this->db->order_by('bl_db_belanja.nama_barang', 'ASC');
        $this->db->order_by('bl_db_belanja.nama_bahan_baku', 'ASC');
        $this->db->order_by('bl_tipe_produksi.nama_tipe_produksi', 'ASC');
    }

    $this->db->limit($limit, $start);
    return $this->db->get()->result_array();
}
// public function get_all_opnamed($month, $year, $limit = 9999, $start = 0, $sort_criteria = []) {
//     $this->db->select('
//         bl_db_belanja.id AS bl_db_belanja_id,
//         bl_db_belanja.nama_barang,
//         bl_db_belanja.nama_bahan_baku,
//         bl_kategori.nama_kategori AS kategori,
//         bl_tipe_produksi.nama_tipe_produksi AS tipe,
//         bl_db_belanja.id_kategori AS kategori_id,
//         bl_db_purchase.id AS bl_db_purchase_id,
//         bl_db_purchase.merk,
//         bl_db_purchase.keterangan,
//         bl_db_purchase.ukuran,
//         bl_db_purchase.unit,
//         bl_db_purchase.pack,
//         bl_db_purchase.harga_satuan AS harga,
//         SUM(bl_gudang.stok_awal) AS stok_awal,
//         SUM(bl_gudang.stok_masuk) AS stok_masuk,
//         SUM(bl_gudang.stok_keluar) AS stok_keluar,
//         SUM(bl_gudang.stok_terbuang) AS stok_terbuang,
//         SUM(bl_gudang.stok_penyesuaian) AS stok_penyesuaian,
//         SUM(bl_gudang.stok_akhir) AS stok_akhir,
//         SUM(bl_gudang.stok_akhir * bl_db_purchase.ukuran) AS unit_total,
//         SUM(bl_gudang.stok_akhir * bl_db_purchase.harga_satuan) AS nilai_total
//     ');
//     $this->db->from('bl_gudang');
//     $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
//     $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
//     $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
//     $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
//     $this->db->where('MONTH(bl_gudang.tanggal)', $month);
//     $this->db->where('YEAR(bl_gudang.tanggal)', $year);
//     $this->db->where('bl_db_belanja.is_gudang', 1);

//     // Add HAVING condition to filter based on non-zero transaction fields
//     $this->db->group_by('bl_db_belanja.id, bl_db_purchase.id');  // Group by both bl_db_belanja_id and bl_db_purchase_id
//     $this->db->having('SUM(bl_gudang.stok_awal) > 0')
//              ->or_having('SUM(bl_gudang.stok_masuk) > 0')
//              ->or_having('SUM(bl_gudang.stok_keluar) > 0')
//              ->or_having('SUM(bl_gudang.stok_terbuang) > 0')
//              ->or_having('SUM(bl_gudang.stok_penyesuaian) > 0')
//              ->or_having('SUM(bl_gudang.stok_akhir) > 0');

//     // Sorting based on user's choice
//     if (!empty($sort_criteria)) {
//         foreach ($sort_criteria as $column => $direction) {
//             $this->db->order_by($column, $direction);
//         }
//     } else {
//         $this->db->order_by('bl_kategori.id', 'ASC');
//         $this->db->order_by('bl_db_belanja.nama_barang', 'ASC');
//         $this->db->order_by('bl_db_belanja.nama_bahan_baku', 'ASC');
//         $this->db->order_by('bl_tipe_produksi.nama_tipe_produksi', 'ASC');
//     }

//     if ($limit !== 'all') {
//         $this->db->limit($limit, $start);
//     }

//     return $this->db->get()->result_array();
// }

public function get_all_opnamed($month, $year, $limit = 9999, $start = 0, $sort_criteria = []) {
    $this->db->select('
        bl_db_belanja.id AS bl_db_belanja_id,
        bl_db_belanja.nama_barang,
        bl_db_belanja.nama_bahan_baku,
        bl_kategori.nama_kategori AS kategori,
        bl_tipe_produksi.nama_tipe_produksi AS tipe,
        bl_db_belanja.id_kategori AS kategori_id,
        bl_db_purchase.id AS bl_db_purchase_id,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.pack,
        bl_db_purchase.harga_satuan AS harga,
        SUM(bl_gudang.stok_awal) AS stok_awal,
        SUM(bl_gudang.stok_masuk) AS stok_masuk,
        SUM(bl_gudang.stok_keluar) AS stok_keluar,
        SUM(bl_gudang.stok_terbuang) AS stok_terbuang,
        SUM(bl_gudang.stok_penyesuaian) AS stok_penyesuaian,
        SUM(bl_gudang.stok_akhir) AS stok_akhir,
        SUM(bl_gudang.stok_akhir * bl_db_purchase.ukuran) AS unit_total,
        SUM(bl_gudang.stok_akhir * bl_db_purchase.harga_satuan) AS nilai_total
    ');
    $this->db->from('bl_gudang');
    $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_db_purchase', 'bl_gudang.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->where('MONTH(bl_gudang.tanggal)', $month);
    $this->db->where('YEAR(bl_gudang.tanggal)', $year);
    $this->db->where('bl_db_belanja.is_gudang', 1);

    // Ambil hanya data yang memiliki transaksi
    $this->db->group_by('bl_db_belanja.id, bl_db_purchase.id');
    $this->db->having('SUM(bl_gudang.stok_awal) > 0')
             ->or_having('SUM(bl_gudang.stok_masuk) > 0')
             ->or_having('SUM(bl_gudang.stok_keluar) > 0')
             ->or_having('SUM(bl_gudang.stok_terbuang) > 0')
             ->or_having('SUM(bl_gudang.stok_penyesuaian) > 0')
             ->or_having('SUM(bl_gudang.stok_akhir) > 0');

    if (!empty($sort_criteria)) {
        foreach ($sort_criteria as $column => $direction) {
            $this->db->order_by($column, $direction);
        }
    } else {
        $this->db->order_by('bl_kategori.id', 'ASC');
        $this->db->order_by('bl_db_belanja.nama_barang', 'ASC');
        $this->db->order_by('bl_db_belanja.nama_bahan_baku', 'ASC');
        $this->db->order_by('bl_tipe_produksi.nama_tipe_produksi', 'ASC');
    }

    if ($limit !== 'all') {
        $this->db->limit($limit, $start);
    }

    return $this->db->get()->result_array();
}



    // Count all filtered records for pagination
    public function count_all_filtered($month, $year) {
        $this->db->from('bl_gudang');
        $this->db->join('bl_db_belanja', 'bl_gudang.bl_db_belanja_id = bl_db_belanja.id', 'left');
        $this->db->where('MONTH(bl_gudang.tanggal)', $month);
        $this->db->where('YEAR(bl_gudang.tanggal)', $year);
        $this->db->where('bl_db_belanja.is_gudang', 1);
        return $this->db->count_all_results();
    }

public function update_stok_masuk_per_bulan($bl_db_purchase_id, $stok_masuk, $tanggal) {
    $bulan = date('m', strtotime($tanggal));
    $tahun = date('Y', strtotime($tanggal));

    $this->db->where('bl_db_purchase_id', $bl_db_purchase_id);
    $this->db->where('MONTH(tanggal)', $bulan);
    $this->db->where('YEAR(tanggal)', $tahun);
    $gudang = $this->db->get('bl_gudang')->row_array();

    if ($gudang) {
        $stok_masuk_baru = ($gudang['stok_masuk'] ?? 0) + $stok_masuk;
        $this->db->where('id', $gudang['id']);
        $this->db->update('bl_gudang', ['stok_masuk' => $stok_masuk_baru]);
    }
}
public function kurangi_stok_masuk_per_bulan($bl_db_purchase_id, $stok_masuk, $tanggal) {
    $bulan = date('m', strtotime($tanggal));
    $tahun = date('Y', strtotime($tanggal));

    $this->db->where('bl_db_purchase_id', $bl_db_purchase_id);
    $this->db->where('MONTH(tanggal)', $bulan);
    $this->db->where('YEAR(tanggal)', $tahun);
    $gudang = $this->db->get('bl_gudang')->row_array();

    if ($gudang) {
        $stok_masuk_baru = max(0, $gudang['stok_masuk'] - $stok_masuk);

        $this->db->where('id', $gudang['id']);
        $this->db->update('bl_gudang', ['stok_masuk' => $stok_masuk_baru]);

        // Update stok_akhir setelah stok_masuk dikurangi
        $this->update_stok_akhir($bl_db_purchase_id, $tanggal);
    }
}

public function get_stok_akhir_per_bulan($bl_db_purchase_id, $tanggal) {
    $bulan = date('m', strtotime($tanggal));
    $tahun = date('Y', strtotime($tanggal));

    $this->db->select('stok_akhir');
    $this->db->from('bl_gudang');
    $this->db->where('bl_db_purchase_id', $bl_db_purchase_id);
    $this->db->where('MONTH(tanggal)', $bulan);
    $this->db->where('YEAR(tanggal)', $tahun);
    $query = $this->db->get();

    return $query->num_rows() > 0 ? $query->row()->stok_akhir : 0;
}
public function update_stok_keluar_per_bulan($bl_db_purchase_id, $stok_keluar, $tanggal) {
    $bulan = date('m', strtotime($tanggal));
    $tahun = date('Y', strtotime($tanggal));

    $this->db->where('bl_db_purchase_id', $bl_db_purchase_id);
    $this->db->where('MONTH(tanggal)', $bulan);
    $this->db->where('YEAR(tanggal)', $tahun);
    $this->db->set('stok_keluar', 'stok_keluar + ' . (int) $stok_keluar, FALSE);
    $this->db->update('bl_gudang');
}
public function reduce_stok_keluar_per_bulan($bl_db_purchase_id, $stok_keluar, $tanggal) {
    $bulan = date('m', strtotime($tanggal));
    $tahun = date('Y', strtotime($tanggal));

    $this->db->where('bl_db_purchase_id', $bl_db_purchase_id);
    $this->db->where('MONTH(tanggal)', $bulan);
    $this->db->where('YEAR(tanggal)', $tahun);
    $this->db->set('stok_keluar', 'GREATEST(0, stok_keluar - ' . (int) $stok_keluar . ')', FALSE);
    $this->db->update('bl_gudang');
}

}


