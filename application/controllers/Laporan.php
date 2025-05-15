<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Laporan_model');
  }

public function index()
{
  $today = date('Y-m-d');
  $data['title'] = "Laporan Penjualan";
  $data['tanggal_awal'] = $today;
  $data['tanggal_akhir'] = $today;

  $data['transaksi'] = $this->Laporan_model->filter_transaksi('', $today, $today);
  $this->load->view('templates/header', $data);
  $this->load->view('laporan/index', $data);
  $this->load->view('templates/footer');
}

public function laporan_penjualan()
{
  $today = date('Y-m-d');
  $data['title'] = "Laporan Penjualan";
  $data['tanggal_awal'] = $today;
  $data['tanggal_akhir'] = $today;

  $data['page'] = 1;
  $data['per_page'] = 10;
  $data['total_data'] = $this->Laporan_model->count_filtered('', $today, $today);
  $data['transaksi'] = $this->Laporan_model->filter_transaksi('', $today, $today, $data['per_page'], 0);
  $data['transaksi_ringkasan'] = $this->Laporan_model->filter_transaksi('', $today, $today, 99999, 0); // Tambahkan ini!
  
  $this->load->view('templates/header', $data);
  $this->load->view('laporan/laporan_penjualan', $data);
  $this->load->view('templates/footer');
}



public function filter()
{
    $search = $this->input->get('search');
    $tanggal_awal = $this->input->get('tanggal_awal');
    $tanggal_akhir = $this->input->get('tanggal_akhir');
    $per_page = (int) $this->input->get('per_page') ?: 10;
    $page = (int) $this->input->get('page') ?: 1;
    $offset = ($page - 1) * $per_page;

    $this->load->model('Laporan_model');
    $total_data = $this->Laporan_model->count_filtered($search, $tanggal_awal, $tanggal_akhir);

    // 1. Untuk Tabel Transaksi (terbatas per halaman)
    $data['transaksi'] = $this->Laporan_model->filter_transaksi($search, $tanggal_awal, $tanggal_akhir, $per_page, $offset);

    // 2. Untuk Ringkasan (semua data tanpa limit)
    $data['transaksi_ringkasan'] = $this->Laporan_model->filter_transaksi($search, $tanggal_awal, $tanggal_akhir, 99999, 0);

    $data['total_data'] = $total_data;
    $data['page'] = $page;
    $data['per_page'] = $per_page;



    // Return partial HTML yang berisi ringkasan + tabel
    $this->load->view('laporan/laporan_penjualan_tabel_transaksi', $data);
}




public function laporan_penjualan_detail($id)
{
    $data['title'] = "Detail Transaksi";

    // Transaksi utama
    $data['transaksi'] = $this->Laporan_model->get_transaksi($id);

    // Nama kasir order dan bayar
    $data['kasir_order'] = $this->db->get_where('users', ['id' => $data['transaksi']['kasir_order']])->row('nama') ?? '-';
    $data['kasir_bayar'] = $this->db->get_where('users', ['id' => $data['transaksi']['kasir_bayar']])->row('nama') ?? '-';

    // Detail produk
$data['detail'] = $this->Laporan_model->get_detail_produk($id);

// Kelompokkan berdasarkan detail_unit_id
$grouped = [];
foreach ($data['detail'] as $d) {
    $key = $d['detail_unit_id'];

    if (!isset($grouped[$key])) {
        $grouped[$key] = [
            'nama_produk' => $d['nama_produk'],
            'jumlah' => 0,
            'harga' => $d['harga'],
            'subtotal' => 0,
            'catatan' => '',
            'extra' => []
        ];
    }

    $grouped[$key]['jumlah'] += $d['jumlah'];
    $grouped[$key]['subtotal'] += $d['jumlah'] * $d['harga'];

    // Catatan hanya diambil jika belum ada
    if (empty($grouped[$key]['catatan']) && !empty($d['catatan'])) {
        $grouped[$key]['catatan'] = $d['catatan'];
    }

    // Ambil extra untuk setiap item
    $extra_list = $this->Laporan_model->get_extra_by_detail_id($d['id']);
    foreach ($extra_list as $e) {
        $extra_name = $e->nama_extra;
        $grouped[$key]['extra'][$extra_name] = ($grouped[$key]['extra'][$extra_name] ?? 0) + $e->qty;
    }
}

$data['detail_grouped'] = array_values($grouped);

    // 🟩 Tambahkan extra untuk setiap detail produk
    foreach ($data['detail'] as &$d) {
        $d['extra'] = $this->Laporan_model->get_extra_by_detail_id($d['id']);
    }


    // Pembayaran
    $data['pembayaran'] = $this->Laporan_model->get_pembayaran($id);

    // Refund
    $data['refund'] = $this->Laporan_model->get_refund($id);

    // Void
    $data['void'] = $this->Laporan_model->get_void($id);

    // Poin didapat dari transaksi ini
    $data['poin_didapat'] = $this->db->select_sum('jumlah_poin')
        ->where([
            'transaksi_id' => $id,
            'customer_id' => $data['transaksi']['customer_id'],
            'status' => 'aktif'
        ])->get('pr_customer_poin')->row()->jumlah_poin ?? 0;

    // Total poin aktif customer
    $data['total_poin'] = $this->db->select_sum('jumlah_poin')
        ->where([
            'customer_id' => $data['transaksi']['customer_id'],
            'status' => 'aktif'
        ])->get('pr_customer_poin')->row()->jumlah_poin ?? 0;

    $this->load->view('templates/header', $data);
    $this->load->view('laporan/laporan_penjualan_detail', $data);
    $this->load->view('templates/footer');
}


//// VOID

public function laporan_void()
{
    $data['title'] = 'Laporan Void';
    $data['voids'] = $this->Laporan_model->get_laporan_void();

    $this->load->view('templates/header', $data);
    $this->load->view('laporan/laporan_void', $data);
    $this->load->view('templates/footer');
}
public function ajax_void()
{
    $search = $this->input->get('search');
    $tanggal_awal = $this->input->get('tanggal_awal') ?: date('Y-m-01');
    $tanggal_akhir = $this->input->get('tanggal_akhir') ?: date('Y-m-t');
    $page = (int) $this->input->get('page') ?: 1;
    $per_page = (int) $this->input->get('per_page') ?: 10; // fix here!
    $offset = ($page - 1) * $per_page;

    $result = $this->Laporan_model->filter_void($search, $tanggal_awal, $tanggal_akhir, $per_page, $offset);
    $total = $this->Laporan_model->count_void($search, $tanggal_awal, $tanggal_akhir);

    echo json_encode([
        'data' => $result,
        'total' => $total,
        'page' => $page,
        'per_page' => $per_page
    ]);
}
public function laporan_void_detail($kode_void)
{
    $data['title'] = 'Detail Void';

    $data['voids'] = $this->Laporan_model->get_void_by_kode($kode_void);
    if (!$data['voids']) show_404();

    // ✅ TARUH DI SINI:
    $data['total_void'] = 0;
    foreach ($data['voids']['items'] as $item) {
        $data['total_void'] += $item->total_subtotal;

        if (!empty($data['voids']['extras'][$item->detail_unit_id])) {
            foreach ($data['voids']['extras'][$item->detail_unit_id] as $extra) {
                $data['total_void'] += $extra->subtotal;
            }
        }
    }

    $this->load->view('templates/header', $data);
    $this->load->view('laporan/laporan_void_detail', $data);
    $this->load->view('templates/footer');
}


/// REFUND 
public function laporan_refund()
{
    $data['title'] = "Laporan Refund";
    $this->load->view('templates/header', $data);
    $this->load->view('laporan/laporan_refund', $data);
    $this->load->view('templates/footer');
}

public function get_refund_data_ajax()
{
    $tanggal_awal = $this->input->get('tanggal_awal');
    $tanggal_akhir = $this->input->get('tanggal_akhir');
    $keyword = $this->input->get('keyword');

    if (!$tanggal_awal || !$tanggal_akhir) {
        $tanggal_awal = date('Y-m-d');
        $tanggal_akhir = date('Y-m-d');
    }

    $this->db->select('
        r.kode_refund, 
        r.no_transaksi, 
        t.customer, 
        t.nomor_meja, 
        MAX(r.waktu_refund) as waktu, 
        SUM(r.harga * r.jumlah) as total_refund, 
        mp.metode_pembayaran
    ');
    $this->db->from('pr_refund r');
    $this->db->join('pr_transaksi t', 't.id = r.pr_transaksi_id', 'left');
    $this->db->join('pr_metode_pembayaran mp', 'mp.id = r.metode_pembayaran_id', 'left');

    if ($keyword) {
        $this->db->group_start();
        $this->db->like('r.kode_refund', $keyword);
        $this->db->or_like('r.no_transaksi', $keyword);
        $this->db->or_like('t.customer', $keyword);
        $this->db->group_end();
    }

    $this->db->where('r.waktu_refund >=', $tanggal_awal . ' 00:00:00');
    $this->db->where('r.waktu_refund <=', $tanggal_akhir . ' 23:59:59');
    $this->db->group_by('r.kode_refund, r.no_transaksi, t.customer, t.nomor_meja, mp.metode_pembayaran');
    $this->db->order_by('waktu', 'DESC');

    $result = $this->db->get()->result();
    echo json_encode($result);
}

public function laporan_refund_modal_detail()
{
    $kode = $this->input->get('kode_refund');
    $this->load->model('Refund_model');
    $data['refund'] = $this->Refund_model->get_by_kode($kode);

    if (!$data['refund']) {
        echo '<div class="text-danger">Data refund tidak ditemukan.</div>';
        return;
    }

    $this->load->view('laporan/laporan_refund_modal_detail', $data);
}



// LAPORAN METODE

public function laporan_metode_pembayaran($tanggal_awal, $tanggal_akhir)
{
    $this->db->select('
        mp.metode_pembayaran,
        mp.id as metode_id,
        COUNT(p.id) as jumlah_transaksi,
        SUM(p.jumlah) as total_pembayaran
    ');
    $this->db->from('pr_pembayaran p');
    $this->db->join('pr_metode_pembayaran mp', 'mp.id = p.metode_id', 'left');
    $this->db->where('DATE(p.waktu_bayar) >=', $tanggal_awal);
    $this->db->where('DATE(p.waktu_bayar) <=', $tanggal_akhir);
    $this->db->group_by('p.metode_id');
    $this->db->order_by('total_pembayaran', 'DESC');

    return $this->db->get()->result();
}

public function laporan_produk()
{
    $this->load->model('Laporan_model');
    $this->load->model('Kategori_model');
    $this->load->model('Divisi_model');

    $tanggal_awal = $this->input->get('tanggal_awal') ?? date('Y-m-d');
    $tanggal_akhir = $this->input->get('tanggal_akhir') ?? date('Y-m-d');
    $kategori_id = $this->input->get('kategori_id');
    $divisi_id = $this->input->get('divisi_id');
    $search = $this->input->get('search');

    $page = (int) ($this->input->get('page') ?? 1);
    $limit = (int) ($this->input->get('limit') ?? 10);
    $offset = ($page - 1) * $limit;
    if ($limit === 9999) {
        $offset = 0;
        $page = 1;
    }

    $ringkasan = $this->Laporan_model->get_total_ringkasan($tanggal_awal, $tanggal_akhir, $kategori_id, $divisi_id, $search);
    $produk = $this->Laporan_model->get_laporan_produk($tanggal_awal, $tanggal_akhir, $kategori_id, $divisi_id, $search, $limit, $offset);
    $total = $this->Laporan_model->count_laporan_produk($tanggal_awal, $tanggal_akhir, $kategori_id, $divisi_id, $search);

    $data = [
        'title' => 'Laporan Penjualan Produk',
        'produk' => $produk,
        'total' => $total,
        'page' => $page,
        'limit' => $limit,
        'tanggal_awal' => $tanggal_awal,
        'tanggal_akhir' => $tanggal_akhir,
        'kategori' => $this->Kategori_model->get_all_kategori(),
        'divisi' => $this->Divisi_model->get_all(),
        'kategori_id' => $kategori_id,
        'divisi_id' => $divisi_id,
        'search' => $search,
        'ringkasan' => $ringkasan
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('laporan/laporan_produk', $data);
    $this->load->view('templates/footer');
}





}