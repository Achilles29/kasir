<?php
class Laporan_model extends CI_Model {

  // Fungsi tambahan tetap
  public function get_transaksi($id)
  {
    return $this->db->get_where('pr_transaksi', ['id' => $id])->row_array();
  }

public function get_detail_produk($id)
{
    return $this->db->select('d.*, p.nama_produk, (d.jumlah * d.harga) as subtotal')
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

public function filter_transaksi($search = '', $tanggal_awal = '', $tanggal_akhir = '', $limit = 10, $offset = 0)
{
    $this->db->select('t.id, t.no_transaksi, t.tanggal, t.waktu_order, t.waktu_bayar, jo.jenis_order, t.total_penjualan, t.total_pembayaran');
    $this->db->from('pr_transaksi t');
    $this->db->join('pr_jenis_order jo', 'jo.id = t.jenis_order_id', 'left');

    if ($search) {
        $this->db->like('t.no_transaksi', $search);
    }

    if ($tanggal_awal && $tanggal_akhir) {
        $this->db->where('t.tanggal >=', $tanggal_awal);
        $this->db->where('t.tanggal <=', $tanggal_akhir);
    }

    $this->db->order_by('t.tanggal', 'DESC');

    if ($limit != 99999) {
        $this->db->limit($limit, $offset);
    }

    return $this->db->get()->result_array();
}

public function count_filtered($search = '', $tanggal_awal = '', $tanggal_akhir = '')
{
    $this->db->from('pr_transaksi t');
    $this->db->join('pr_jenis_order jo', 'jo.id = t.jenis_order_id', 'left');

    if ($search) {
        $this->db->like('t.no_transaksi', $search);
    }

    if ($tanggal_awal && $tanggal_akhir) {
        $this->db->where('t.tanggal >=', $tanggal_awal);
        $this->db->where('t.tanggal <=', $tanggal_akhir);
    }

    return $this->db->count_all_results();
}
public function get_extra_by_detail_id($detail_id)
{
    return $this->db->select('pe.nama_extra, de.jumlah as qty')
        ->from('pr_detail_extra de')
        ->join('pr_produk_extra pe', 'pe.id = de.pr_produk_extra_id', 'left')
        ->where('de.detail_transaksi_id', $detail_id)
        ->get()->result();
}


public function get_extra_produk_by_detail($detail_id)
{
    return $this->db->select('pr_detail_extra.*, pr_produk_extra.nama_extra, pr_produk_extra.harga_extra')
        ->from('pr_detail_extra')
        ->join('pr_produk_extra', 'pr_produk_extra.id = pr_detail_extra.pr_produk_extra_id')
        ->where('pr_detail_extra.detail_transaksi_id', $detail_id)
        ->get()
        ->result_array();
}


/// void

public function get_laporan_void()
{
    $this->db->select('v.*, t.no_transaksi, t.tanggal as tanggal_order, t.nomor_meja, t.jenis_order_id, t.customer, p.nama_produk');
    $this->db->from('pr_void v');
    $this->db->join('pr_detail_transaksi dt', 'v.detail_transaksi_id = dt.id', 'left');
    $this->db->join('pr_transaksi t', 'v.pr_transaksi_id = t.id', 'left');
    $this->db->join('pr_produk p', 'dt.pr_produk_id = p.id', 'left');
    $this->db->order_by('v.created_at', 'DESC');
    return $this->db->get()->result();
}
public function filter_void($search = '', $tanggal_awal = '', $tanggal_akhir = '', $limit = 10, $offset = 0)
{
    $this->db->select('v.kode_void, t.no_transaksi, MAX(v.created_at) as created_at, SUM(v.harga * v.jumlah) as total_void, v.alasan, ap.nama as nama_pegawai');
    $this->db->from('pr_void v');
    $this->db->join('pr_transaksi t', 't.id = v.pr_transaksi_id', 'left');
    $this->db->join('abs_pegawai ap', 'ap.id = v.void_by', 'left');

    if ($search) {
        $this->db->group_start();
        $this->db->like('v.kode_void', $search);
        $this->db->or_like('t.no_transaksi', $search);
        $this->db->group_end();
    }

    if ($tanggal_awal && $tanggal_akhir) {
        $this->db->where('DATE(v.created_at) >=', $tanggal_awal);
        $this->db->where('DATE(v.created_at) <=', $tanggal_akhir);
    }

    $this->db->group_by('v.kode_void');
    $this->db->order_by('MIN(v.id)', 'ASC');
    $this->db->limit($limit, $offset);

    return $this->db->get()->result();
}


public function count_void($search = '', $tanggal_awal = '', $tanggal_akhir = '')
{
    $this->db->select('v.kode_void');
    $this->db->from('pr_void v');
    $this->db->join('pr_transaksi t', 't.id = v.pr_transaksi_id', 'left');

    if ($search) {
        $this->db->group_start();
        $this->db->like('v.kode_void', $search);
        $this->db->or_like('t.no_transaksi', $search);
        $this->db->group_end();
    }

    if ($tanggal_awal && $tanggal_akhir) {
        $this->db->where('DATE(v.created_at) >=', $tanggal_awal);
        $this->db->where('DATE(v.created_at) <=', $tanggal_akhir);
    }

    $this->db->group_by('v.kode_void');
    return $this->db->get()->num_rows();
}

public function get_void_by_kode($kode_void)
{
    // 1. AMBIL hanya produk utama (distinct detail_unit_id)
    $main_items = $this->db->query("
        SELECT 
            d.detail_unit_id, 
            p.nama_produk,
            SUM(d.jumlah) as total_jumlah,
            d.harga,
            SUM(d.jumlah * d.harga) as total_subtotal,
            v.catatan,
            ap.nama AS nama_pegawai,
            v.alasan,
            v.created_at
        FROM pr_void v
        LEFT JOIN pr_detail_transaksi d ON d.id = v.detail_transaksi_id
        LEFT JOIN pr_produk p ON p.id = d.pr_produk_id
        LEFT JOIN pr_transaksi t ON t.id = v.pr_transaksi_id
        LEFT JOIN abs_pegawai ap ON ap.id = v.void_by
        WHERE v.kode_void = ?
        GROUP BY d.detail_unit_id
        ORDER BY d.detail_unit_id ASC
    ", [$kode_void])->result();

    // 2. AMBIL daftar extra (group by detail_unit_id + extra)
    $extras = $this->db->query("
        SELECT 
            d.detail_unit_id,
            pe.nama_extra,
            SUM(de.jumlah) as jumlah,
            de.harga,
            SUM(de.subtotal) as subtotal
        FROM pr_void v
        LEFT JOIN pr_detail_extra de ON de.id = v.detail_extra_id
        LEFT JOIN pr_produk_extra pe ON pe.id = de.pr_produk_extra_id
        LEFT JOIN pr_detail_transaksi d ON de.detail_transaksi_id = d.id
        WHERE v.kode_void = ?
            AND de.id IS NOT NULL
        GROUP BY d.detail_unit_id, pe.nama_extra, de.harga
    ", [$kode_void])->result();

    // 3. Gabungkan extras berdasarkan detail_unit_id
    $grouped_extra = [];
    foreach ($extras as $x) {
        $grouped_extra[$x->detail_unit_id][] = $x;
    }

    return [
        'items' => $main_items,
        'extras' => $grouped_extra,
        'meta' => isset($main_items[0]) ? $main_items[0] : null
    ];
}


}