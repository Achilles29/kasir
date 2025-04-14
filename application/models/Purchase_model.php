<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_model extends CI_Model {
public function get_all() {
    $this->db->select('
        bl_purchase.*, 
        bl_db_belanja.nama_barang, 
        bl_db_belanja.nama_bahan_baku, 
        bl_kategori.nama_kategori AS kategori, 
        bl_tipe_produksi.nama_tipe_produksi AS tipe_produksi,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.pack,
        bl_db_purchase.harga_satuan,
        bl_rekening.nama_rekening AS metode_pembayaran,
        bl_purchase.pengusul
    ');
    $this->db->from('bl_purchase');
    $this->db->join('bl_db_purchase', 'bl_purchase.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->join('bl_rekening', 'bl_purchase.metode_pembayaran = bl_rekening.id', 'left');
    return $this->db->get()->result_array();
}




public function insert($data) {
    if (
        !isset($data['bl_db_belanja_id']) || 
        !isset($data['bl_db_purchase_id']) || 
        !isset($data['kuantitas']) || 
        !isset($data['total_unit']) || 
        !isset($data['total_harga']) || 
        !isset($data['hpp']) || 
        !isset($data['metode_pembayaran']) || 
        !isset($data['jenis_pengeluaran'])
        
    ) {
        throw new Exception('Data tidak lengkap untuk insert ke bl_purchase');
    }

    return $this->db->insert('bl_purchase', $data);
}


    public function verify($id) {
        $this->db->set('status', 'verified');
        $this->db->where('id', $id);
        return $this->db->update('bl_purchase');
    }

    public function reject($id) {
        $this->db->set('status', 'rejected');
        $this->db->where('id', $id);
        return $this->db->update('bl_purchase');
    }

public function get_by_id($id) {
    $this->db->select('
        bl_purchase.*,
        bl_db_belanja.nama_barang,
        bl_db_belanja.nama_bahan_baku,
        bl_db_belanja.id_kategori,
        bl_db_belanja.id_tipe_produksi,
        bl_kategori.nama_kategori,
        bl_tipe_produksi.nama_tipe_produksi,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.pack,
        bl_db_purchase.harga_satuan,
        bl_rekening.nama_rekening AS metode_pembayaran
    ');
    $this->db->from('bl_purchase');
    $this->db->join('bl_db_purchase', 'bl_purchase.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->join('bl_rekening', 'bl_purchase.metode_pembayaran = bl_rekening.id', 'left');
    $this->db->where('bl_purchase.id', $id);
    return $this->db->get()->row_array();
}

public function delete($id) {
    return $this->db->delete('bl_purchase', ['id' => $id]);
}


public function update($id, $data) {
    $this->db->where('id', $id);
    return $this->db->update('bl_purchase', $data);
}
public function get_by_date_range($start_date, $end_date, $jenis_pengeluaran, $limit, $offset)
{
    $this->db->select('
        bl_purchase.*, 
        bl_db_belanja.nama_barang, 
        bl_db_belanja.nama_bahan_baku, 
        bl_kategori.nama_kategori AS kategori, 
        bl_tipe_produksi.nama_tipe_produksi AS tipe_produksi,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.pack,
        bl_db_purchase.harga_satuan,
        bl_rekening.nama_rekening AS metode_pembayaran,
        bl_jenis_pengeluaran.nama_jenis_pengeluaran AS jenis_pengeluaran
    ');
    $this->db->from('bl_purchase');
    $this->db->join('bl_db_purchase', 'bl_purchase.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->join('bl_rekening', 'bl_purchase.metode_pembayaran = bl_rekening.id', 'left');
    $this->db->join('bl_jenis_pengeluaran', 'bl_purchase.jenis_pengeluaran = bl_jenis_pengeluaran.id', 'left');
    $this->db->where('bl_purchase.tanggal >=', $start_date);
    $this->db->where('bl_purchase.tanggal <=', $end_date);

    if ($jenis_pengeluaran) {
        $this->db->where('bl_purchase.jenis_pengeluaran', $jenis_pengeluaran);
    }

    $this->db->order_by('bl_purchase.tanggal', 'ASC');
    $this->db->order_by('bl_jenis_pengeluaran.id', 'ASC');
    $this->db->order_by('bl_db_belanja.nama_barang', 'ASC');
    $this->db->limit($limit, $offset);

    return $this->db->get()->result_array();
}

public function count_filtered($start_date, $end_date)
{
    $this->db->where('tanggal >=', $start_date);
    $this->db->where('tanggal <=', $end_date);
    return $this->db->count_all_results('bl_purchase');
}

public function get_rekapitulasi_metode_pembayaran($tanggal_awal, $tanggal_akhir) {
    $this->db->select('DATE(p.tanggal) AS tanggal, r.nama_rekening, SUM(p.total_harga) AS total');
    $this->db->from('bl_purchase p');
    $this->db->join('bl_rekening r', 'p.metode_pembayaran = r.id', 'left');
    $this->db->where('p.tanggal >=', $tanggal_awal);
    $this->db->where('p.tanggal <=', $tanggal_akhir);
    $this->db->group_by(['DATE(p.tanggal)', 'r.nama_rekening']);
    $this->db->order_by('DATE(p.tanggal)', 'ASC');
    return $this->db->get()->result_array();
}
public function get_all_pegawai() {
    return $this->db->select('id, nama')->from('abs_pegawai')->get()->result_array();
}
    public function get_laporan_purchase_per_tanggal($bulan) {
        $tanggal_awal = $bulan . '-01';
        $tanggal_akhir = date("Y-m-t", strtotime($tanggal_awal));

        // Query untuk mengambil data berdasarkan tanggal dan jenis pengeluaran
        $this->db->select('
            bl_purchase.tanggal,
            bl_jenis_pengeluaran.nama_jenis_pengeluaran,
            SUM(bl_purchase.total_harga) AS total_harga
        ');
        $this->db->from('bl_purchase');
        $this->db->join('bl_jenis_pengeluaran', 'bl_purchase.jenis_pengeluaran = bl_jenis_pengeluaran.id', 'left');
        $this->db->where('bl_purchase.tanggal >=', $tanggal_awal);
        $this->db->where('bl_purchase.tanggal <=', $tanggal_akhir);
        $this->db->group_by('bl_purchase.tanggal, bl_purchase.jenis_pengeluaran');
        $this->db->order_by('bl_purchase.tanggal ASC, bl_jenis_pengeluaran.id ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
public function get_all_ordered() {
    $this->db->select('
        bl_purchase.*, 
        bl_db_belanja.nama_barang, 
        bl_db_belanja.nama_bahan_baku, 
        bl_kategori.nama_kategori AS kategori, 
        bl_tipe_produksi.nama_tipe_produksi AS tipe_produksi,
        bl_db_purchase.merk,
        bl_db_purchase.keterangan,
        bl_db_purchase.ukuran,
        bl_db_purchase.unit,
        bl_db_purchase.pack,
        bl_db_purchase.harga_satuan,
        bl_rekening.nama_rekening AS metode_pembayaran,
        bl_purchase.pengusul,
        bl_jenis_pengeluaran.nama_jenis_pengeluaran AS jenis_pengeluaran
    ');
    $this->db->from('bl_purchase');
    $this->db->join('bl_db_purchase', 'bl_purchase.bl_db_purchase_id = bl_db_purchase.id', 'left');
    $this->db->join('bl_db_belanja', 'bl_db_purchase.bl_db_belanja_id = bl_db_belanja.id', 'left');
    $this->db->join('bl_kategori', 'bl_db_belanja.id_kategori = bl_kategori.id', 'left');
    $this->db->join('bl_tipe_produksi', 'bl_db_belanja.id_tipe_produksi = bl_tipe_produksi.id', 'left');
    $this->db->join('bl_rekening', 'bl_purchase.metode_pembayaran = bl_rekening.id', 'left');
    $this->db->join('bl_jenis_pengeluaran', 'bl_purchase.jenis_pengeluaran = bl_jenis_pengeluaran.id', 'left');
    $this->db->order_by('bl_purchase.tanggal', 'ASC');
    $this->db->order_by('bl_jenis_pengeluaran.nama_jenis_pengeluaran', 'ASC');
    $this->db->order_by('bl_db_belanja.nama_barang', 'ASC');
    return $this->db->get()->result_array();
}


}
