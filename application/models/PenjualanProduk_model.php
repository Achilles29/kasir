<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenjualanProduk_model extends CI_Model {

    // Function to get penjualan produk
    public function get_penjualan_produk($tanggal_awal, $tanggal_akhir, $produk, $divisi, $kategori, $search, $limit, $start)
    {
        $this->db->select('
            p.tanggal,
            p.produk,
            p.sku,
            p.kategori,
            p.jenis_produk,
            p.jumlah,
            p.nilai,
            p.jumlah_refund,
            p.nilai_refund,
            p.penjualan,
            d.nama_divisi,
            pr.nama_produk
        ');
        $this->db->from('bl_penjualan_produk p');
        $this->db->join('bl_produk pr', 'p.sku = pr.sku', 'left');
        $this->db->join('bl_divisi d', 'pr.divisi_id = d.id', 'left');
        $this->db->where('p.tanggal >=', $tanggal_awal);  // Filter by start date
        $this->db->where('p.tanggal <=', $tanggal_akhir); // Filter by end date

        if ($produk) {
            $this->db->where('p.produk', $produk);
        }

        if ($divisi) {
            $this->db->where('pr.divisi_id', $divisi);
        }

        if ($kategori) {
            $this->db->where('p.kategori', $kategori);
        }

        if ($search) {
            $this->db->like('pr.nama_produk', $search);
        }

        $this->db->limit($limit, $start);
        $this->db->order_by('p.tanggal', 'DESC');  // Order by date

        $query = $this->db->get();
        return $query->result_array();
    }

    // Function to count total rows for pagination
    public function count_penjualan_produk($tanggal_awal, $tanggal_akhir, $produk, $divisi, $kategori, $search)
    {
        $this->db->from('bl_penjualan_produk p');
        $this->db->join('bl_produk pr', 'p.produk = pr.sku', 'left');
        $this->db->join('bl_divisi d', 'pr.divisi_id = d.id', 'left');
        $this->db->where('p.tanggal >=', $tanggal_awal);
        $this->db->where('p.tanggal <=', $tanggal_akhir);

        if ($produk) {
            $this->db->where('p.produk', $produk);
        }

        if ($divisi) {
            $this->db->where('pr.divisi_id', $divisi);
        }

        if ($kategori) {
            $this->db->where('p.kategori', $kategori);
        }

        if ($search) {
            $this->db->like('pr.nama_produk', $search);
        }

        return $this->db->count_all_results();
    }

    // Function to search for products via AJAX
public function search_produk($search) {
    // Fetch matching products and categories from the bl_penjualan_produk table
    $this->db->select('produk, kategori');
    $this->db->from('bl_penjualan_produk');
    $this->db->like('produk', $search);
    $this->db->or_like('kategori', $search); // Include both product and category in the search
    $query = $this->db->get();
    
    // Return results as an array
    return $query->result_array();
}

public function get_totals($tanggal_awal, $tanggal_akhir, $produk, $divisi, $kategori, $search) {
    $this->db->select('
        SUM(p.jumlah) as total_jumlah,
        SUM(p.nilai) as total_nilai,
        SUM(p.jumlah_refund) as total_jumlah_refund,
        SUM(p.nilai_refund) as total_nilai_refund,
        SUM(p.penjualan) as total_penjualan
    ');
    $this->db->from('bl_penjualan_produk p');
    $this->db->join('bl_produk pr', 'p.sku = pr.sku', 'left');
    $this->db->join('bl_divisi d', 'pr.divisi_id = d.id', 'left');
    $this->db->where('p.tanggal >=', $tanggal_awal);
    $this->db->where('p.tanggal <=', $tanggal_akhir);

    if ($produk) {
        $this->db->where('p.produk', $produk);
    }

    if ($divisi) {
        $this->db->where('pr.divisi_id', $divisi);
    }

    if ($kategori) {
        $this->db->where('p.kategori', $kategori);
    }

    if ($search) {
        $this->db->like('pr.nama_produk', $search);
    }

    $query = $this->db->get();
    return $query->row_array();  // Return the totals as an associative array
}
    public function get_all_penjualan_produk()
    {
        $this->db->select('
            p.tanggal,
            p.produk,
            p.sku,
            p.kategori,
            p.jenis_produk,
            p.jumlah,
            p.nilai,
            p.jumlah_refund,
            p.nilai_refund,
            p.penjualan,
            d.nama_divisi,
            pr.nama_produk
        ');
        $this->db->from('bl_penjualan_produk p');
        $this->db->join('bl_produk pr', 'p.sku = pr.sku', 'left');
        $this->db->join('bl_divisi d', 'pr.divisi_id = d.id', 'left');
        $this->db->order_by('p.tanggal ASC, p.jumlah DESC');  // First order by date (descending), then by jumlah (highest first)

        $query = $this->db->get();
        return $query->result_array();
    }
}
?>
