<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    /**
     * Get all products
     * 
     * @return array
     */
    public function get_all() {
        $this->db->select('*');
        $this->db->from('bl_produk');
        $query = $this->db->get();
        return $query->result_array();
    }
    /**
     * Get product by SKU
     * 
     * @param string $sku
     * @return array
     */
    public function get_by_sku($sku) {
        $this->db->select('*');
        $this->db->from('bl_produk');
        $this->db->where('sku', $sku);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_all_produk() {
        $this->db->select('pr_produk.*, pr_kategori.nama_kategori, pr_divisi.nama_divisi');
        $this->db->from('pr_produk');
        $this->db->join('pr_kategori', 'pr_produk.kategori_id = pr_kategori.id', 'left');
        $this->db->join('pr_divisi', 'pr_kategori.pr_divisi_id = pr_divisi.id', 'left');
        return $this->db->get()->result_array();
    }
        public function count_all_produk() {
        return $this->db->count_all('pr_produk');
    }
    public function insert_produk($data) {
        if (!isset($data['hpp']) || empty($data['hpp'])) {
            $data['hpp'] = 0;
        }
        return $this->db->insert('pr_produk', $data);
    }
    public function get_produk_by_id($id) {
        $this->db->select('pr_produk.*, pr_kategori.nama_kategori');
        $this->db->from('pr_produk');
        $this->db->join('pr_kategori', 'pr_produk.kategori_id = pr_kategori.id', 'left');
        $this->db->where('pr_produk.id', $id);
        return $this->db->get()->row_array();
    }
    public function update_produk($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('pr_produk', $data);
    }
    
    public function delete_produk($id) {
        return $this->db->delete('pr_produk', array('id' => $id));
    }
public function get_all_kategori() {
    return $this->db->get('pr_kategori')->result_array();
}

public function count_filtered($search, $kategori, $status) {
    $this->db->from('pr_produk');
    if (!empty($search)) {
        $this->db->like('nama_produk', $search);
    }
    if (!empty($kategori)) {
        $this->db->where('kategori_id', $kategori);
    }
    if (!empty($status)) {
        $this->db->where('tampil', $status);
    }
    return $this->db->count_all_results();
}

public function get_filtered($limit, $offset, $search, $kategori, $status) {
    $this->db->select('pr_produk.*, pr_kategori.nama_kategori');
    $this->db->from('pr_produk');
    $this->db->join('pr_kategori', 'pr_produk.kategori_id = pr_kategori.id', 'left');
    if (!empty($search)) {
        $this->db->like('pr_produk.nama_produk', $search);
    }
    if (!empty($kategori)) {
        $this->db->where('pr_produk.kategori_id', $kategori);
    }
    if (!empty($status)) {
        $this->db->where('pr_produk.tampil', $status);
    }
    $this->db->limit($limit, $offset);
    return $this->db->get()->result_array();
}



public function get_filtered_produk($limit, $offset, $kategori_id = '', $status = '', $search = '') {
    $this->db->select('pr_produk.*, pr_kategori.nama_kategori');
    $this->db->from('pr_produk');
    $this->db->join('pr_kategori', 'pr_produk.kategori_id = pr_kategori.id', 'left');

    if (!empty($kategori_id)) {
        $this->db->where('pr_produk.kategori_id', $kategori_id);
    }
    if (!empty($status)) {
        $this->db->where('pr_produk.tampil', $status);
    }
    if (!empty($search)) {
        $this->db->like('pr_produk.nama_produk', $search);
    }

    $this->db->limit($limit, $offset);
    
    return $this->db->get()->result_array();
}

    public function count_filtered_produk($kategori_id = '', $search = '') {
        $this->db->from('pr_produk');

        if (!empty($kategori_id)) {
            $this->db->where('kategori_id', $kategori_id);
        }
        if (!empty($search)) {
            $this->db->like('nama_produk', $search);
        }

        return $this->db->count_all_results();
    }
    /**
     * 🔥 FUNGSI BARU UNTUK POS 🔥
     * =====================================================
     */

    /**
     * Get kategori untuk tabulasi di POS
     * @return array
     */
    public function get_kategori_pos() {
        $this->db->select('id, nama_kategori, urutan');
        $this->db->from('pr_kategori');
        $this->db->order_by('urutan', 'ASC'); // Urut berdasarkan urutan kategori
        return $this->db->get()->result_array();
    }

    /**
     * Get produk berdasarkan kategori dan pencarian
     */
public function search_produk_pos($kategori_id = '', $search = '') {
    $this->db->select('pr_produk.id, pr_produk.nama_produk, FLOOR(pr_produk.harga_jual) AS harga_jual, pr_produk.foto, pr_kategori.urutan AS urutan_kategori');
    $this->db->from('pr_produk');
    $this->db->join('pr_kategori', 'pr_produk.kategori_id = pr_kategori.id', 'left');
    $this->db->where('pr_produk.tampil', 1); // Hanya produk yang tampil di menu

    if (!empty($kategori_id)) {
        $this->db->where('pr_produk.kategori_id', $kategori_id);
    }
    if (!empty($search)) {
        $this->db->like('pr_produk.nama_produk', $search);
    }

    $this->db->order_by('pr_kategori.urutan', 'ASC');
    $this->db->order_by('pr_produk.id', 'ASC');

    return $this->db->get()->result_array();
}
    /**
     * Get produk by ID untuk POS
     * @param int $id
     * @return array
     */
    public function get_produk_by_id_pos($id) {
        $this->db->select('id, nama_produk, harga_jual, foto');
        $this->db->from('pr_produk');
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    // untuk pencarian ajax voucher
public function search_produk($keyword) {
    $this->db->select('id, nama_produk');
    $this->db->from('pr_produk');
    $this->db->like('nama_produk', $keyword);
    $this->db->limit(10);
    return $this->db->get()->result_array();
}



}


