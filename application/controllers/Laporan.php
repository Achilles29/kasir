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

public function filter()
{
    $search = $this->input->get('search');
    $tanggal_awal = $this->input->get('tanggal_awal');
    $tanggal_akhir = $this->input->get('tanggal_akhir');
    $per_page = (int) $this->input->get('per_page') ?: 10;
    $page = (int) $this->input->get('page') ?: 1;
    $offset = ($page - 1) * $per_page;

    $total_data = $this->Laporan_model->count_filtered($search, $tanggal_awal, $tanggal_akhir);
    $transaksi = $this->Laporan_model->filter_transaksi($search, $tanggal_awal, $tanggal_akhir, $per_page, $offset);

    echo json_encode([
        'transaksi' => $transaksi,
        'total_data' => $total_data,
        'page' => $page,
        'per_page' => $per_page,
    ]);
}



public function detail($id)
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
    $this->load->view('laporan/detail', $data);
    $this->load->view('templates/footer');
}





//// VOID

public function void()
{
    $data['title'] = 'Laporan Void';
    $data['voids'] = $this->Laporan_model->get_laporan_void();

    $this->load->view('templates/header', $data);
    $this->load->view('laporan/void', $data);
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
public function detail_void($kode_void)
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
    $this->load->view('laporan/detail_void', $data);
    $this->load->view('templates/footer');
}


}