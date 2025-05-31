<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//require_once FCPATH . 'vendor/autoload.php'; // Pastikan autoload dimuat sebelum library

class Kasir extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Produk_model');
        $this->load->model('Pegawai_model');
        $this->load->model('Printer_model');
        $this->load->model('Kasir_model');
        $this->load->model('Setting_model');
        $this->load->model('Refund_model');
        $this->load->model('KasirShift_model');

        log_message('debug', 'Kasir Controller Initialized');
        // Periksa apakah pengguna sudah login
        if (!$this->session->userdata('username')) {
            redirect('auth'); // Redirect ke halaman login jika belum login
        }
    }

    // public function index() {

    //     $data['title'] = 'POS Namua Coffee & Eatery';
    //     $data['jenis_order'] = $this->db->get('pr_jenis_order')->result_array();
    //     $data['metode_pembayaran'] = $this->db->get('pr_metode_pembayaran')->result_array();
    //     $data['kategori'] = $this->Produk_model->get_kategori_pos(); // Kategori untuk tab
    //     $data['produk'] = $this->Produk_model->search_produk_pos('', ''); // Semua produk saat awal load
    //     $data['printer'] = $this->Printer_model->get_all_with_divisi();
    //     $data['divisi'] = $this->db->get('pr_divisi')->result_array();


    //     $this->load->view('kasir/index', $data);
    //         }
    
public function index() {
    // Ambil ID kasir dari session
    $kasir_id = $this->session->userdata('pegawai_id');

    // Load model shift
    $this->load->model('KasirShift_model');

    // Cek apakah ada shift yang masih open
    $shift = $this->KasirShift_model->get_open_shift($kasir_id);

    // Data untuk dikirim ke view
    $data = [
        'title' => 'POS Namua Coffee & Eatery',
        'jenis_order' => $this->db->get('pr_jenis_order')->result_array(),
        'metode_pembayaran' => $this->db->get('pr_metode_pembayaran')->result_array(),
        'kategori' => $this->Produk_model->get_kategori_pos(),
        'produk' => $this->Produk_model->search_produk_pos('', ''),
        'printer' => $this->Printer_model->get_all_with_divisi(),
        'divisi' => $this->db->get('pr_divisi')->result_array(),
        'show_modal_awal' => ($shift === null) ? true : false, // << modal ditentukan disini
    ];
    
    $data['shift_id_terakhir'] = $this->session->flashdata('shift_id_terakhir');
    $this->load->view('kasir/index', $data);
}

public function start_shift()
{
    $kasir_id = $this->session->userdata('pegawai_id');
    $modal_awal = $this->input->post('modal_awal');
    $keterangan = $this->input->post('keterangan');

    $this->load->model('KasirShift_model');
    $shift_id = $this->KasirShift_model->start_shift($kasir_id, $modal_awal, $keterangan);

    if ($shift_id) {
        // ⬇️ Ambil data shift untuk dikirim ke VPS
        $shift_data = $this->db->get_where('pr_kasir_shift', ['id' => $shift_id])->row_array();

        // ⬇️ Kirim ke VPS
        $this->load->model('Api_model');
        $this->Api_model->kirim_data('pr_kasir_shift', $shift_data);
        
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mulai shift']);
    }
}



public function get_shift_aktif($kasir_id) {
    $this->db->where('kasir_id', $kasir_id);
    $this->db->where('status', 'OPEN');
    return $this->db->get('pr_kasir_shift')->row_array();
}

public function cek_shift()
{
    $kasir_id = $this->session->userdata('pegawai_id');
    $this->load->model('KasirShift_model');
    $this->load->model('Pegawai_model');

    $shift = $this->KasirShift_model->get_open_shift($kasir_id);

    if (!$shift) {
        echo json_encode(['status' => 'error', 'message' => 'Tidak ada shift yang aktif.']);
        return;
    }

    // Data kasir
    $kasir = $this->Pegawai_model->get_pegawai_by_id($kasir_id);

    // Hitung total penjualan (hanya LUNAS)
    $this->db->select_sum('total_penjualan');
    $this->db->where('kasir_order', $kasir_id);
    $this->db->where('DATE(waktu_order)', date('Y-m-d'));
    $this->db->where('status_pembayaran', 'LUNAS');
    $total_penjualan = $this->db->get('pr_transaksi')->row()->total_penjualan ?? 0;

    // Hitung total pending (belum lunas)
    $this->db->select_sum('sisa_pembayaran');
    $this->db->where('kasir_order', $kasir_id);
    $this->db->where('DATE(waktu_order)', date('Y-m-d'));
    $this->db->where('status_pembayaran !=', 'LUNAS');
    $total_pending = $this->db->get('pr_transaksi')->row()->sisa_pembayaran ?? 0;

    // Hitung transaksi selesai
    $this->db->where('kasir_order', $kasir_id);
    $this->db->where('DATE(waktu_order)', date('Y-m-d'));
    $this->db->where('status_pembayaran', 'LUNAS');
    $transaksi_selesai = $this->db->count_all_results('pr_transaksi');

    // Hitung transaksi pending
    $this->db->where('kasir_order', $kasir_id);
    $this->db->where('DATE(waktu_order)', date('Y-m-d'));
    $this->db->where('status_pembayaran !=', 'LUNAS');
    $transaksi_pending = $this->db->count_all_results('pr_transaksi');

    // Ambil rincian pembayaran dari transaksi yang LUNAS
    $metode_pembayaran = $this->db->select('pr_metode_pembayaran.metode_pembayaran, SUM(pr_pembayaran.jumlah) as total')
        ->from('pr_pembayaran')
        ->join('pr_metode_pembayaran', 'pr_metode_pembayaran.id = pr_pembayaran.metode_id', 'left')
        ->join('pr_transaksi', 'pr_transaksi.id = pr_pembayaran.transaksi_id', 'left')
        ->where('pr_pembayaran.kasir_id', $kasir_id)
        ->where('DATE(pr_pembayaran.waktu_bayar)', date('Y-m-d'))
        ->where('pr_transaksi.status_pembayaran', 'LUNAS')  // ✅ Pastikan hanya LUNAS
        ->group_by('pr_pembayaran.metode_id')
        ->get()
        ->result_array();

    // Ambil refund per metode
    $refunds = $this->db->select('mp.metode_pembayaran, SUM(rf.harga * rf.jumlah) as total')
        ->from('pr_refund rf')
        ->join('pr_metode_pembayaran mp', 'mp.id = rf.metode_pembayaran_id', 'left')
        ->where('rf.refund_by', $kasir_id)
        ->where('DATE(rf.waktu_refund)', date('Y-m-d'))
        ->group_by('rf.metode_pembayaran_id')
        ->get()->result_array();

    $refund_per_rekening = $this->db->select('rk.id as rekening_id, rk.nama_rekening, SUM(rf.harga * rf.jumlah) as total_refund')
        ->from('pr_refund rf')
        ->join('pr_metode_pembayaran mp', 'mp.id = rf.metode_pembayaran_id')
        ->join('bl_rekening rk', 'rk.id = mp.bl_rekening_id')
        ->where('rf.refund_by', $kasir_id)
        ->where('DATE(rf.waktu_refund)', date('Y-m-d'))
        ->group_by('rk.id')
        ->get()->result_array();
    

    // Ambil penerimaan kasir per rekening
    $penerimaan_per_rekening = $this->db->select('rk.id as rekening_id, rk.nama_rekening, SUM(pb.jumlah) as total')
    ->from('pr_pembayaran pb')
    ->join('pr_metode_pembayaran mp', 'mp.id = pb.metode_id')
    ->join('bl_rekening rk', 'rk.id = mp.bl_rekening_id')
    ->where('pb.kasir_id', $kasir_id)
    ->where('DATE(pb.waktu_bayar)', date('Y-m-d'))
    ->group_by('rk.id')
    ->get()->result_array();

    $refund_map = [];
    foreach ($refund_per_rekening as $r) {
        $refund_map[$r['rekening_id']] = $r['total_refund'];
    }
    
    foreach ($penerimaan_per_rekening as &$p) {
        $refund = $refund_map[$p['rekening_id']] ?? 0;
        $p['total'] = $p['total'] - $refund;
    }
    unset($p);
    

    // Hitung total penerimaan kasir
    $total_penerimaan = array_sum(array_column($metode_pembayaran, 'total'));


    // Saldo akhir
    $modal_akhir = $shift->modal_awal + $total_penerimaan;

    echo json_encode([
        'status' => 'success',
        'nama_kasir' => $kasir->nama,
        'waktu_buka' => $shift->waktu_mulai,
        'waktu_tutup' => date('Y-m-d H:i:s'),
        'modal_awal' => (float) $shift->modal_awal,
        'total_penjualan' => (float) $total_penjualan,
        'total_pending' => (float) $total_pending,
        'total_penerimaan' => (float) $total_penerimaan,
        'modal_akhir' => (float) $modal_akhir,
        'transaksi_selesai' => (int) $transaksi_selesai,
        'transaksi_pending' => (int) $transaksi_pending,
        'refund_per_metode' => $refunds,
        'penerimaan_per_rekening' => $penerimaan_per_rekening,
        'metode_pembayaran' => $metode_pembayaran
    ]);
}



public function tutup_shift()
{
    $kasir_id = $this->session->userdata('pegawai_id');
    $this->load->model('KasirShift_model');
    $this->load->model('Api_model'); // pastikan sudah diload

    $shift = $this->KasirShift_model->get_open_shift($kasir_id);

    if (!$shift) {
        echo json_encode(['status' => 'error', 'message' => 'Tidak ada shift yang aktif.']);
        return;
    }

    // =============== PERHITUNGAN ===============
    $today = date('Y-m-d');

    // Total penjualan LUNAS
    $this->db->select_sum('total_penjualan');
    $this->db->where('kasir_order', $kasir_id);
    $this->db->where('DATE(waktu_order)', $today);
    $this->db->where('status_pembayaran', 'LUNAS');
    $total_penjualan = $this->db->get('pr_transaksi')->row()->total_penjualan ?? 0;

    // Total pending
    $this->db->select_sum('sisa_pembayaran');
    $this->db->where('kasir_order', $kasir_id);
    $this->db->where('DATE(waktu_order)', $today);
    $this->db->where('status_pembayaran !=', 'LUNAS');
    $total_pending = $this->db->get('pr_transaksi')->row()->sisa_pembayaran ?? 0;

    // Total pembayaran masuk
    $this->db->select_sum('jumlah');
    $this->db->where('kasir_id', $kasir_id);
    $this->db->where('DATE(waktu_bayar)', $today);
    $total_bayar_masuk = $this->db->get('pr_pembayaran')->row()->jumlah ?? 0;

    // Transaksi selesai
    $this->db->where('kasir_order', $kasir_id);
    $this->db->where('DATE(waktu_order)', $today);
    $this->db->where('status_pembayaran', 'LUNAS');
    $transaksi_selesai = $this->db->count_all_results('pr_transaksi');

    // Transaksi pending
    $this->db->where('kasir_order', $kasir_id);
    $this->db->where('DATE(waktu_order)', $today);
    $this->db->where('status_pembayaran !=', 'LUNAS');
    $transaksi_pending = $this->db->count_all_results('pr_transaksi');

    // Modal akhir
    $modal_akhir = $shift->modal_awal + $total_bayar_masuk;

    // =============== UPDATE SHIFT ===============
    $this->db->where('id', $shift->id);
    $this->db->update('pr_kasir_shift', [
        'total_penjualan' => $total_penjualan,
        'total_pending' => $total_pending,
        'modal_akhir' => $modal_akhir,
        'selisih' => 0,
        'waktu_tutup' => date('Y-m-d H:i:s'),
        'status' => 'CLOSE',
        'transaksi_selesai' => $transaksi_selesai,
        'transaksi_pending' => $transaksi_pending
    ]);

    // =============== INSERT KE LOG ===============
    // 1. Ambil data penjualan per metode
    $penjualan = $this->db->select('pb.metode_id, mp.bl_rekening_id, mp.metode_pembayaran, SUM(pb.jumlah) as total')
        ->from('pr_pembayaran pb')
        ->join('pr_metode_pembayaran mp', 'mp.id = pb.metode_id', 'left')
        ->join('pr_transaksi t', 't.id = pb.transaksi_id')
        ->where('pb.kasir_id', $kasir_id)
        ->where('DATE(pb.waktu_bayar)', $today)
        ->where('t.status_pembayaran', 'LUNAS')
        ->group_by('pb.metode_id')
        ->get()->result_array();

    // 2. Ambil data refund per metode
    $refunds = $this->db->select('rf.metode_pembayaran_id as metode_id, mp.bl_rekening_id, mp.metode_pembayaran, SUM(rf.harga * rf.jumlah) as total')
        ->from('pr_refund rf')
        ->join('pr_metode_pembayaran mp', 'mp.id = rf.metode_pembayaran_id')
        ->where('rf.refund_by', $kasir_id)
        ->where('DATE(rf.waktu_refund)', $today)
        ->group_by('rf.metode_pembayaran_id')
        ->get()->result_array();

    $log_data = [];

    foreach ($penjualan as $p) {
        $log_data[] = [
            'shift_id' => $shift->id,
            'tipe' => 'penjualan',
            'metode_id' => $p['metode_id'],
            'rekening_id' => $p['bl_rekening_id'],
            'nama' => $p['metode_pembayaran'],
            'nominal' => $p['total'],
            'created_at' => date('Y-m-d H:i:s')
        ];
    }

    foreach ($refunds as $r) {
        $log_data[] = [
            'shift_id' => $shift->id,
            'tipe' => 'refund',
            'metode_id' => $r['metode_id'],
            'rekening_id' => $r['bl_rekening_id'],
            'nama' => $r['metode_pembayaran'],
            'nominal' => $r['total'],
            'created_at' => date('Y-m-d H:i:s')
        ];
    }

    if (!empty($log_data)) {
        $this->db->insert_batch('pr_kasir_shift_log', $log_data);
        $this->Api_model->kirim_data('pr_kasir_shift_log', $log_data);
    }

    // Sinkronisasi shift ke VPS
    $shift_data = $this->db->get_where('pr_kasir_shift', ['id' => $shift->id])->row_array();
    $this->Api_model->kirim_data('pr_kasir_shift', $shift_data);
    
    $this->session->set_flashdata('shift_id_terakhir', $shift->id);

    // redirect('kasir/cetak_laporan_shift/' . $shift->id);

    echo json_encode([
        'status' => 'success',
        'message' => 'Shift berhasil ditutup.',
        'shift_id' => $shift->id
    ]);
    
}

public function riwayat_shift()
{
    $this->load->model('KasirShift_model');

    $tanggal_awal = $this->input->get('tanggal_awal') ?? date('Y-m-01');
    $tanggal_akhir = $this->input->get('tanggal_akhir') ?? date('Y-m-t');

    $data['title'] = "Riwayat Shift Kasir";
    $data['tanggal_awal'] = $tanggal_awal;
    $data['tanggal_akhir'] = $tanggal_akhir;

    $data['shifts'] = $this->db
        ->select('s.*, p.nama as nama_kasir')
        ->from('pr_kasir_shift s')
        ->join('abs_pegawai p', 'p.id = s.kasir_id', 'left')
        ->where('s.status', 'CLOSE')
        ->where('DATE(s.waktu_tutup) >=', $tanggal_awal)
        ->where('DATE(s.waktu_tutup) <=', $tanggal_akhir)
        ->order_by('s.waktu_tutup', 'DESC')
        ->get()->result_array();

    $this->load->view('templates/header', $data);
    $this->load->view('kasir/riwayat_shift', $data);
    $this->load->view('templates/footer');
}



public function cetak_laporan_shift($shift_id)
{
    $this->load->model('KasirShift_model');
    $this->load->model('Printer_model');

    // Ambil data shift & kasir
    $shift = $this->db
        ->select('s.*, p.nama as nama_kasir')
        ->from('pr_kasir_shift s')
        ->join('abs_pegawai p', 'p.id = s.kasir_id', 'left')
        ->where('s.id', $shift_id)
        ->get()->row_array();

    if (!$shift) {
        show_error('Shift tidak ditemukan.');
    }

    // Ambil log penjualan & refund
    $log = $this->db
        ->where('shift_id', $shift_id)
        ->order_by('tipe')
        ->get('pr_kasir_shift_log')
        ->result_array();

    // Ambil daftar rekening (untuk mapping id -> nama_rekening)
    $rekening_map_nama = [];
    $rekening_data = $this->db->get('bl_rekening')->result_array();
    foreach ($rekening_data as $r) {
        $rekening_map_nama[$r['id']] = strtoupper($r['nama_rekening']);
    }

    // Hitung total penjualan dan refund
    $total_penjualan = 0;
    $total_refund = 0;

    // Kelompokkan
    $penjualan = [];
    $refund = [];

    foreach ($log as $item) {
        if ($item['tipe'] === 'penjualan') {
            $penjualan[] = $item;
            $total_penjualan += $item['nominal'];
        } elseif ($item['tipe'] === 'refund') {
            $refund[] = $item;
            $total_refund += $item['nominal'];
        }
    }

    // Hitung total penerimaan kasir
    $total_penerimaan = $total_penjualan - $total_refund;

    // Hitung penerimaan per rekening
    $penerimaan_per_rekening = [];
    foreach ($penjualan as $item) {
        $rid = $item['rekening_id'];
        $penerimaan_per_rekening[$rid] = ($penerimaan_per_rekening[$rid] ?? 0) + $item['nominal'];
    }
    foreach ($refund as $item) {
        $rid = $item['rekening_id'];
        $penerimaan_per_rekening[$rid] = ($penerimaan_per_rekening[$rid] ?? 0) - $item['nominal'];
    }

    // Ambil printer kasir default
    $printer = $this->Printer_model->get_by_lokasi('KASIR');
    if (!$printer) {
        show_error('Printer kasir tidak ditemukan.');
    }

    // ========== FORMAT STRUK ==========
    $str = "== LAPORAN TUTUP SHIFT ==\n";
    $str .= "KASIR           : " . strtoupper($shift['nama_kasir']) . "\n";
    $str .= "WAKTU BUKA      : " . date('d/m/Y, H:i', strtotime($shift['waktu_mulai'])) . "\n";
    $str .= "WAKTU TUTUP     : " . date('d/m/Y, H:i', strtotime($shift['waktu_tutup'])) . "\n";
    $str .= "------------------------------\n";
    $str .= "MODAL AWAL      : Rp " . number_format($shift['modal_awal'], 0, ',', '.') . "\n\n";

    $str .= "[ RINCIAN PENJUALAN ]\n";
    foreach ($penjualan as $item) {
        $str .= "- " . str_pad(strtoupper($item['nama']), 20) . "Rp " . str_pad(number_format($item['nominal'], 0, ',', '.'), 10, ' ', STR_PAD_LEFT) . "\n";
    }
    $str .= "TOTAL PENJUALAN : Rp " . number_format($total_penjualan, 0, ',', '.') . "\n\n";

    $str .= "[ RINCIAN REFUND ]\n";
    foreach ($refund as $item) {
        $str .= "- " . str_pad(strtoupper($item['nama']), 20) . "-Rp " . str_pad(number_format($item['nominal'], 0, ',', '.'), 9, ' ', STR_PAD_LEFT) . "\n";
    }
    $str .= "TOTAL REFUND    : -Rp " . number_format($total_refund, 0, ',', '.') . "\n\n";

    $str .= "TOTAL PENERIMAAN: Rp " . number_format($total_penerimaan, 0, ',', '.') . "\n\n";

    $str .= "[ PENERIMAAN PER REKENING ]\n";
    foreach ($penerimaan_per_rekening as $rid => $total) {
        $nama = $rekening_map_nama[$rid] ?? 'REKENING';
        $str .= "- " . str_pad($nama, 20) . "Rp " . str_pad(number_format($total, 0, ',', '.'), 10, ' ', STR_PAD_LEFT) . "\n";
    }

    $str .= "------------------------------\n";
    $str .= "SALDO AKHIR     : Rp " . number_format($shift['modal_akhir'], 0, ',', '.') . "\n\n";
    $str .= "TRANSAKSI SELESAI: {$shift['transaksi_selesai']} transaksi\n";
    $str .= "BELUM TERBAYAR  : {$shift['transaksi_pending']} transaksi\n";
    $str .= "NOMINAL PENDING : Rp " . number_format($shift['total_pending'] ?? 0, 0, ',', '.') . "\n";
    $str .= "==============================\n\n";

    // Kirim ke printer via Python
    $this->send_to_python_service('KASIR', $str);
    redirect('kasir/riwayat_shift');
}



public function get_printer_list() {
    $data = $this->Printer_model->get_all_printers();
    echo json_encode($data);
}


        //DI KOMEN UNTUK TES PRODUK PAKET

    // Load Produk AJAX untuk pencarian & kategori
// public function load_produk() {
//     $divisi = $this->input->get('divisi');
//     $search = $this->input->get('search');

//     $this->db->select('
//         pr_produk.id,
//         pr_produk.nama_produk,
//         FLOOR(pr_produk.harga_jual) AS harga_jual,
//         pr_produk.foto,
//         pr_kategori.urutan AS urutan_kategori,
//         pr_kategori.pr_divisi_id
//     ');
//     $this->db->from('pr_produk');
//     $this->db->join('pr_kategori', 'pr_produk.kategori_id = pr_kategori.id', 'left');
//     $this->db->where('pr_produk.tampil', 1); // hanya produk yang ditampilkan

//     // ✅ Filter berdasarkan divisi (dari pr_kategori.pr_divisi_id)
//     if (!empty($divisi)) {
//         $this->db->where('pr_kategori.pr_divisi_id', $divisi);
//     }

//     // ✅ Filter pencarian nama produk
//     if (!empty($search)) {
//         $this->db->like('pr_produk.nama_produk', $search);
//     }

//     $this->db->order_by('pr_kategori.urutan', 'ASC');
//     $this->db->order_by('pr_produk.id', 'ASC');

//     $query = $this->db->get();
//     echo json_encode($query->result_array());
// }

public function load_produk() {
    $divisi = $this->input->get('divisi');
    $search = $this->input->get('search');

    // Jika divisi adalah 4 (PAKET)
    if ($divisi == 4) {
        $this->db->select("
            pr_produk_paket.id,
            pr_produk_paket.nama_paket AS nama_produk,
            FLOOR(pr_produk_paket.harga_paket) AS harga_jual,
            'default.png' AS foto,
            999 AS urutan_kategori,
            4 AS pr_divisi_id
        ");
        $this->db->from('pr_produk_paket');
        $this->db->where('pr_produk_paket.status', 1);

        if (!empty($search)) {
            $this->db->like('pr_produk_paket.nama_paket', $search);
        }

        $this->db->order_by('pr_produk_paket.id', 'ASC');
        $result = $this->db->get()->result_array();
    } else {
        // Default: produk biasa
        $this->db->select('
            pr_produk.id,
            pr_produk.nama_produk,
            FLOOR(pr_produk.harga_jual) AS harga_jual,
            pr_produk.foto,
            pr_kategori.urutan AS urutan_kategori,
            pr_kategori.pr_divisi_id
        ');
        $this->db->from('pr_produk');
        $this->db->join('pr_kategori', 'pr_produk.kategori_id = pr_kategori.id', 'left');
        $this->db->where('pr_produk.tampil', 1);

        if (!empty($divisi)) {
            $this->db->where('pr_kategori.pr_divisi_id', $divisi);
        }

        if (!empty($search)) {
            $this->db->like('pr_produk.nama_produk', $search);
        }

        $this->db->order_by('pr_kategori.urutan', 'ASC');
        $this->db->order_by('pr_produk.id', 'ASC');
        $result = $this->db->get()->result_array();
    }

    echo json_encode($result);
}


    // Fungsi untuk menghapus item dari transaksi
    public function hapus_item() {
        $item_id = $this->input->post('item_id');
        $this->session->unset_userdata("cart_$item_id");
        echo json_encode(['status' => 'success', 'message' => 'Item berhasil dihapus']);
    }

    // Fungsi untuk mendapatkan total transaksi dalam sesi
    public function get_total_transaksi() {
        $total = 0;
        foreach ($this->session->userdata() as $key => $value) {
            if (strpos($key, 'cart_') === 0) {
                $total += $value['total'];
            }
        }
        echo json_encode(['total' => $total]);
    }
    public function manage_kasir() {
        $data['title'] = "Manajemen Kasir";
        $data['kasir'] = $this->Pegawai_model->get_all_kasir();
        $data['pegawai'] = $this->Pegawai_model->get_all_pegawai_non_kasir();
        
        $this->load->view("templates/header", $data);
        $this->load->view("kasir/manage_kasir", $data);
        $this->load->view("templates/footer");
    }

    public function tambah_kasir() {
        $id = $this->input->post('id');
        $this->Pegawai_model->set_kasir($id);
        echo json_encode(["status" => "success"]);
    }

    public function hapus_kasir() {
        $id = $this->input->post('id');
        $this->Pegawai_model->unset_kasir($id);
        echo json_encode(["status" => "success"]);
    }
public function search_customer() {
    $search = $this->input->get('search');
    $this->db->select('id, nama, kode_pelanggan, telepon');
    $this->db->from('pr_customer');
    $this->db->like('nama', $search);
    $this->db->limit(10);
    $query = $this->db->get();

    echo json_encode($query->result_array());
}
    private function generate_no_transaksi() {
        $date = date("ymd"); // Format YYMMDD
        $this->db->select("MAX(RIGHT(no_transaksi, 4)) AS max_id");
        $this->db->from("pr_transaksi");
        $this->db->where("tanggal", date("Y-m-d"));
        $query = $this->db->get()->row();

        $new_id = isset($query->max_id) ? $query->max_id + 1 : 1;
        $new_id = str_pad($new_id, 4, "0", STR_PAD_LEFT);
        return "CS/63/" . $date . "/" . $new_id;
    }


    
private function send_to_python_service($lokasi_printer, $text) {
    $printer = $this->Printer_model->get_by_lokasi($lokasi_printer);

    if (!$printer) return "Printer tidak ditemukan di database.";

    // Default: jika tidak dikenali
    $portMap = [
        'KASIR'   => 3000,
        'BAR'     => 3001,
        'KITCHEN' => 3002,
        'CHECKER'  => 3003
    ];

    $upper = strtoupper($printer['lokasi_printer']);
    $port = isset($portMap[$upper]) ? $portMap[$upper] : 3000;

    $url = "http://localhost:{$port}/cetak";

    $payload = [
        'text' => $text
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_CONNECTTIMEOUT => 2, // ⏱️ max waktu tunggu koneksi 2 detik
        CURLOPT_TIMEOUT => 5        // ⏱️ max keseluruhan waktu request 5 detik
    ]);

    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) return "Tidak terhubung ke printer ($url): $error";

    $res = json_decode($result, true);
    return (isset($res['status']) && $res['status'] === 'success') ? true : ($res['message'] ?? 'Gagal cetak');
}


public function cetak_pending_printer()
{
    $transaksi_id = $this->input->post('transaksi_id');
    $lokasi_printer = $this->input->post('lokasi_printer');

    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);
    if (!$transaksi) {
        $this->session->set_flashdata('error', 'Transaksi tidak ditemukan.');
        redirect('kasir');
    }

    $this->load->model('Setting_model');
    $this->load->model('Api_model');

    $printer = $this->Printer_model->get_by_lokasi($lokasi_printer);
    if (!$printer) {
        $this->session->set_flashdata('error', 'Printer tidak ditemukan.');
        redirect('kasir');
    }

    $produk = $this->Kasir_model->get_detail_transaksi($transaksi_id);
    $produk_grouped = $this->Kasir_model->group_items($produk);
    $extra_grouped = $this->Kasir_model->get_detail_extra_grouped($transaksi_id);

    // Inject extra ke masing-masing item grouped
    foreach ($produk_grouped as &$p) {
        $p['extra'] = [];

        foreach ($extra_grouped as $ex) {
            if (isset($p['detail_unit_id']) && $ex['detail_unit_id'] == $p['detail_unit_id']) {
                $p['extra'][] = [
                    'id' => $ex['pr_produk_extra_id'],
                    'harga' => $ex['harga'],
                    'satuan' => $ex['satuan'],
                    'jumlah' => $ex['jumlah_extra'],
                    'nama_extra' => $ex['nama_extra'] ?? 'Extra'
                ];
            }
        }
    }
    unset($p);

    // Filter produk hanya untuk divisi printer tersebut
    $produk_cetak = [];
    foreach ($produk_grouped as $item) {
        $produk = $this->db
            ->select('k.pr_divisi_id')
            ->from('pr_produk p')
            ->join('pr_kategori k', 'p.kategori_id = k.id', 'left')
            ->where('p.id', $item['pr_produk_id'])
            ->get()
            ->row_array();

        if (strtoupper($lokasi_printer) === 'CHECKER' || empty($printer['divisi']) || ($produk && $produk['pr_divisi_id'] == $printer['divisi'])) {
            $produk_cetak[] = $item;
        }
    }

    if (empty($produk_cetak)) {
        $this->session->set_flashdata('info', 'Tidak ada produk yang perlu dicetak untuk printer ini.');
        redirect('kasir');
    }

    // Susun struk
    $transaksi['items'] = $produk_cetak;
    $tampilan = $this->Setting_model->get_tampilan_struk($printer['id']);
    $struk_data = $this->Setting_model->get_data_struk();
    $struk = $this->Kasir_model->generate_struk_full_by_setting($transaksi, $printer, $struk_data, $tampilan);

    $res = $this->send_to_python_service($lokasi_printer, $struk);

    if ($res === true) {
        $this->session->set_flashdata('success', 'Struk berhasil dicetak.');
    } else {
        $this->session->set_flashdata('error', 'Gagal mencetak. ' . $res);
    }

    redirect('kasir');
}



public function cetak_pesanan_baru() {
    $transaksi_id = $this->input->post('transaksi_id');
    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);

    if (!$transaksi) {
        echo json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan']);
        return;
    }

    // Cek apakah ada item yang belum dicetak
    $ada_yang_belum_diprint = false;
    foreach ($transaksi['items'] as $item) {
        if (empty($item['is_printed'])) {
            $ada_yang_belum_diprint = true;
            break;
        }
    }

    if (!$ada_yang_belum_diprint) {
        echo json_encode([
            'status' => 'info',
            'message' => '✅ Semua item dalam transaksi ini sudah dicetak sebelumnya.'
        ]);
        return;
    }

    $isSudahBayar = !empty($transaksi['waktu_bayar']);
    $this->load->model('Setting_model');
    $struk_data = $this->Setting_model->get_data_struk();
    $printers = $this->Printer_model->get_all();

    $hasil = [];

    foreach ($printers as $printer) {
        $lokasi = strtoupper($printer['lokasi_printer']);

        // Jika transaksi belum dibayar, hanya cetak ke printer divisi produksi
        if (!$isSudahBayar && !in_array($lokasi, ['BAR', 'KITCHEN', 'CHECKER'])) {
            continue;
        }

        // Ambil setting tampilan struk berdasarkan printer
        $tampilan = $this->Setting_model->get_tampilan_struk($printer['id']);

        // Gunakan fungsi generate yang sudah sinkron dengan preview
        $struk = $this->Kasir_model->generate_struk_full_by_setting($transaksi, $printer, $struk_data, $tampilan);

        // Lewati printer jika tidak ada item untuk divisi tersebut
        if (trim($struk) === '' || strlen(trim($struk)) < 5) {
            $hasil[] = "ℹ️ Tidak ada produk untuk $lokasi, dilewati";
            continue;
        }

        // Kirim ke service Python
        $res = $this->send_to_python_service($lokasi, $struk);

        if ($res === true) {
            $hasil[] = "✅ Dicetak ke $lokasi";
        } else {
            $hasil[] = "❌ Gagal cetak $lokasi: $res";
        }
    }

    // Update status is_printed di detail transaksi
    $this->db->where('pr_transaksi_id', $transaksi_id);
    $this->db->where('is_printed', 0); // ubah dari IS NULL ke = 0
    $this->db->update('pr_detail_transaksi', ['is_printed' => 1]);


    // 🔄 Kirim data is_printed ke VPS
    $this->load->model('Api_model');
    $updated_detail = $this->db
        ->where('pr_transaksi_id', $transaksi_id)
        ->get('pr_detail_transaksi')
        ->result_array();

    if (!empty($updated_detail)) {
        $this->Api_model->kirim_data('pr_detail_transaksi', $updated_detail);
    }

    echo json_encode([
        'status' => 'success',
        'message' => "🖨️ Hasil cetak pesanan:\n" . implode("\n", $hasil)
    ]);
}

// private function cetak_pesanan_baru_internal($transaksi_id)
// {
//     $this->load->model('Setting_model');
//     $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);
//     if (!$transaksi) {
//         log_message('error', "Cetak pesanan baru: Transaksi ID $transaksi_id tidak ditemukan.");
//         return;
//     }

//     $struk_data = $this->Setting_model->get_data_struk();
//     $printers = $this->Printer_model->get_all();

//     $produk = $this->Kasir_model->get_detail_transaksi($transaksi_id);
    

//     $produk_belum_print = array_filter($produk, function($p) {
//         return (empty($p['is_printed']) && (is_null($p['status']) || strtolower($p['status']) == 'null'));
//     });

//     if (empty($produk_belum_print)) {
//         log_message('info', "ℹ️ Tidak ada produk baru untuk dicetak.");
//         return;
//     }

//     // ✅ Group produk
//     $produk_grouped = $this->Kasir_model->group_items($produk_belum_print);

//     // Ambil extra dari database
//     $extra_grouped = $this->Kasir_model->get_detail_extra_grouped($transaksi_id);

//     // Mapping ulang extra ke produk_grouped
//     foreach ($produk_grouped as &$p) {
//         $p['extra'] = [];

//         foreach ($extra_grouped as $ex) {
//             if (isset($p['detail_unit_id']) && $ex['detail_unit_id'] == $p['detail_unit_id']) {
//                 $p['extra'][] = [
//                     'id' => $ex['pr_produk_extra_id'],
//                     'harga' => $ex['harga'],
//                     'satuan' => $ex['satuan'],
//                     'jumlah' => $ex['jumlah_extra'],
//                     'nama_extra' => $ex['nama_extra'] ?? 'Extra' // <-- tambahkan ini
//                 ];
//             }
//         }
//     }

//     unset($p); // penting!


    

//     // ✅ Cetak ke printer masing-masing
//     foreach ($printers as $printer) {
//         $lokasi = strtoupper($printer['lokasi_printer']);

//         if (!in_array($lokasi, ['BAR', 'KITCHEN', 'CHECKER'])) {
//             continue;
//         }

//         $tampilan = $this->Setting_model->get_tampilan_struk($printer['id']);

//         $produk_cetak = [];

//         foreach ($produk_grouped as $item) {
//             $produk = $this->db
//                 ->select('k.pr_divisi_id')
//                 ->from('pr_produk p')
//                 ->join('pr_kategori k', 'p.kategori_id = k.id', 'left')
//                 ->where('p.id', $item['pr_produk_id'])
//                 ->get()
//                 ->row_array();

//             if ($lokasi == 'CHECKER' || empty($printer['divisi']) || ($produk && $produk['pr_divisi_id'] == $printer['divisi'])) {
//                 $produk_cetak[] = $item;
//             }
//         }

//         if (empty($produk_cetak)) {
//             log_message('info', "ℹ️ Tidak ada produk baru untuk printer $lokasi, dilewati.");
//             continue;
//         }

//         $transaksi_cetak = $transaksi;
//         $transaksi_cetak['items'] = $produk_cetak;

//         $struk = $this->Kasir_model->generate_struk_full_by_setting($transaksi_cetak, $printer, $struk_data, $tampilan);

//         if (trim($struk) === '' || strlen(trim($struk)) < 5) {
//             log_message('info', "ℹ️ Struk kosong untuk printer $lokasi, dilewati.");
//             continue;
//         }

//         $res = $this->send_to_python_service($lokasi, $struk);

//         if ($res === true) {
//             log_message('info', "✅ Pesanan baru dicetak ke $lokasi.");
//         } else {
//             log_message('error', "❌ Gagal cetak pesanan baru ke $lokasi: " . print_r($res, true));
//         }
//     }

//     // ✅ Update is_printed
//     $this->db->where('pr_transaksi_id', $transaksi_id);
//     $this->db->where('(is_printed = 0 OR is_printed IS NULL)', null, false);
//     $this->db->update('pr_detail_transaksi', ['is_printed' => 1]);
//     // 🔄 Kirim data is_printed ke VPS
//     $this->load->model('Api_model');
//     $updated_detail = $this->db
//         ->select('id, is_printed')
//         ->where('pr_transaksi_id', $transaksi_id)
//         ->get('pr_detail_transaksi')
//         ->result_array();

//     if (!empty($updated_detail)) {
//         $this->Api_model->kirim_data('pr_detail_transaksi', $updated_detail);
//     }
// }


private function cetak_pesanan_baru_internal($transaksi_id)
{
    $this->load->model(['Setting_model', 'Api_model']);

    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);
    if (!$transaksi) {
        log_message('error', "Cetak pesanan baru: Transaksi ID $transaksi_id tidak ditemukan.");
        return;
    }

    $struk_data = $this->Setting_model->get_data_struk();
    $printers = $this->Printer_model->get_all();

    $produk = $this->Kasir_model->get_detail_transaksi($transaksi_id);
    $produk_belum_print = array_filter($produk, function($p) {
        return (empty($p['is_printed']) && (is_null($p['status']) || strtolower($p['status']) == 'null'));
    });

    if (empty($produk_belum_print)) {
        log_message('info', "ℹ️ Tidak ada produk baru untuk dicetak.");
        return;
    }

    // ✅ Group produk
    $produk_grouped = $this->Kasir_model->group_items($produk_belum_print);

    // Ambil extra dari database
    $extra_grouped = $this->Kasir_model->get_detail_extra_grouped($transaksi_id);

    // Mapping ulang extra ke produk_grouped
    foreach ($produk_grouped as &$p) {
        $p['extra'] = [];
        foreach ($extra_grouped as $ex) {
            if (isset($p['detail_unit_id']) && $ex['detail_unit_id'] == $p['detail_unit_id']) {
                $p['extra'][] = [
                    'id' => $ex['pr_produk_extra_id'],
                    'harga' => $ex['harga'],
                    'satuan' => $ex['satuan'],
                    'jumlah' => $ex['jumlah_extra'],
                    'nama_extra' => $ex['nama_extra'] ?? 'Extra'
                ];
            }
        }
    }
    unset($p);

    $paket_ids_tercetak = []; // Untuk update terakhir (union semua printer)

    foreach ($printers as $printer) {
        $lokasi = strtoupper($printer['lokasi_printer']);
        if (!in_array($lokasi, ['BAR', 'KITCHEN', 'CHECKER'])) continue;

        $tampilan = $this->Setting_model->get_tampilan_struk($printer['id']);
        $produk_cetak = [];

        foreach ($produk_grouped as $item) {
            $produk = $this->db
                ->select('k.pr_divisi_id')
                ->from('pr_produk p')
                ->join('pr_kategori k', 'p.kategori_id = k.id', 'left')
                ->where('p.id', $item['pr_produk_id'])
                ->get()
                ->row_array();

            if ($lokasi == 'CHECKER' || empty($printer['divisi']) || ($produk && $produk['pr_divisi_id'] == $printer['divisi'])) {
                $produk_cetak[] = $item;
            }
        }

        if (empty($produk_cetak)) {
            log_message('info', "ℹ️ Tidak ada produk baru untuk printer $lokasi, dilewati.");
            continue;
        }

        $transaksi_cetak = $transaksi;
        $transaksi_cetak['items'] = $produk_cetak;

        // Reset per printer
        $paket_printed = [];
        $paket_ids = [];

        $struk = $this->Kasir_model->generate_struk_full_by_setting($transaksi_cetak, $printer, $struk_data, $tampilan, $paket_printed, $paket_ids);

        if (trim($struk) === '' || strlen(trim($struk)) < 5) {
            log_message('info', "ℹ️ Struk kosong untuk printer $lokasi, dilewati.");
            continue;
        }

        $res = $this->send_to_python_service($lokasi, $struk);

        if ($res === true) {
            log_message('info', "✅ Pesanan baru dicetak ke $lokasi.");
        } else {
            log_message('error', "❌ Gagal cetak pesanan baru ke $lokasi: " . print_r($res, true));
        }

        $paket_ids_tercetak = array_merge($paket_ids_tercetak, $paket_ids);
    }

    // ✅ Update is_printed produk reguler
    $this->db->where('pr_transaksi_id', $transaksi_id);
    $this->db->where('(is_printed = 0 OR is_printed IS NULL)', null, false);
    $this->db->update('pr_detail_transaksi', ['is_printed' => 1]);

    // 🔄 Sync pr_detail_transaksi
    $updated_detail = $this->db->select('id, is_printed')->where('pr_transaksi_id', $transaksi_id)->get('pr_detail_transaksi')->result_array();
    if (!empty($updated_detail)) {
        $this->Api_model->kirim_data('pr_detail_transaksi', $updated_detail);
    }

    // ✅ Update is_printed paket
    $paket_ids_tercetak = array_unique($paket_ids_tercetak);
    if (!empty($paket_ids_tercetak)) {
        $this->db->where_in('id', $paket_ids_tercetak)->update('pr_detail_transaksi_paket', ['is_printed' => 1]);
        $this->Api_model->kirim_data('pr_detail_transaksi_paket', array_map(function($id) {
            return ['id' => $id, 'is_printed' => 1];
        }, $paket_ids_tercetak));
    }
}



public function cetak_void_internal()
{
    $void_ids = $this->input->post('void_ids'); // array void_id dari frontend
    // pastikan array
    if (is_string($void_ids)) {
        $void_ids = json_decode($void_ids, true);
    }
    $this->load->model('Setting_model');
    $this->load->model('Printer_model');

    if (empty($void_ids)) {
        echo json_encode(['status' => 'error', 'message' => 'Tidak ada produk void untuk dicetak.']);
        return;
    }

    // ⛔ Yang benar: AMBIL voids dulu, baru transaksi!
    $voids = $this->db
        ->select('v.*, dt.pr_produk_id, p.kategori_id, k.pr_divisi_id, t.no_transaksi, t.customer, t.nomor_meja, pg.nama as kasir_order')
        ->from('pr_void v')
        ->join('pr_detail_transaksi dt', 'dt.id = v.detail_transaksi_id', 'left')
        ->join('pr_produk p', 'p.id = dt.pr_produk_id', 'left')
        ->join('pr_kategori k', 'k.id = p.kategori_id', 'left')
        ->join('pr_transaksi t', 't.id = v.pr_transaksi_id', 'left')
        ->join('abs_pegawai pg', 'pg.id = t.kasir_order', 'left')
        ->where_in('v.id', $void_ids)
        ->get()
        ->result_array();

    if (empty($voids)) {
        echo json_encode(['status' => 'error', 'message' => 'Data void tidak ditemukan.']);
        return;
    }

    // ✅ Setelah voids ada, baru ambil transaksi
    $transaksi_id = $voids[0]['pr_transaksi_id'] ?? null;
    if (!$transaksi_id) {
        echo json_encode(['status' => 'error', 'message' => 'Transaksi ID tidak ditemukan.']);
        return;
    }

    $transaksi = $this->db
        ->select('t.no_transaksi, t.customer, t.nomor_meja, p.nama as kasir_order')
        ->from('pr_transaksi t')
        ->join('abs_pegawai p', 'p.id = t.kasir_order', 'left')
        ->where('t.id', $transaksi_id)
        ->get()
        ->row_array();

    if (empty($transaksi)) {
        echo json_encode(['status' => 'error', 'message' => 'Data transaksi tidak ditemukan.']);
        return;
    }

    $struk_data = $this->Setting_model->get_data_struk();

    // Group berdasarkan divisi
    $void_per_divisi = [];
    foreach ($voids as $v) {
        $divisi_id = $v['pr_divisi_id'] ?: 0;
        $void_per_divisi[$divisi_id][] = $v;
    }

    // Cetak BAR/KITCHEN
    foreach ($void_per_divisi as $divisi_id => $list_void) {
        $printer = $this->Printer_model->get_by_divisi($divisi_id);
        if (!$printer) continue;

        $lokasi = strtoupper($printer['lokasi_printer']);
        if (!in_array($lokasi, ['BAR', 'KITCHEN'])) continue;

        $tampilan = $this->Setting_model->get_tampilan_struk($printer['id']);
        $struk_text = $this->Kasir_model->generate_void_struk($transaksi, $list_void, $printer, $struk_data, $lokasi);
        

        $this->send_to_python_service($lokasi, $struk_text);
    }

    // Cetak ke CHECKER
    $printer_checker = $this->Printer_model->get_by_lokasi('CHECKER');
    if ($printer_checker) {
        $tampilan_checker = $this->Setting_model->get_tampilan_struk($printer_checker['id']);
        $struk_checker = $this->Kasir_model->generate_void_struk($transaksi, $voids, $printer_checker, $struk_data, 'CHECKER');

        $this->send_to_python_service('CHECKER', $struk_checker);
    }

    // Update void menjadi printed
    $this->db->where_in('id', $void_ids);
    $this->db->update('pr_void', ['is_printed' => 1]);

    // 🔥 Sinkronisasi ke VPS
    $this->load->model('Api_model');
    $updated_voids = $this->db->where_in('id', $void_ids)->get('pr_void')->result_array();
    if (!empty($updated_voids)) {
        $this->Api_model->kirim_data('pr_void', $updated_voids);
    }

    echo json_encode(['status' => 'success', 'message' => 'Void berhasil dicetak']);
}



public function cetak_pesanan_dibayar()
{
    $transaksi_id = $this->input->post('transaksi_id');
    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);

    if (!$transaksi) {
        echo json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan']);
        return;
    }

    $this->load->model('Setting_model');
    $struk_data = $this->Setting_model->get_data_struk();
    $printers = $this->Printer_model->get_all();

    $hasil = [];

    foreach ($printers as $printer) {
        $lokasi = strtoupper($printer['lokasi_printer']);

        // ❗ Hanya cetak ke printer KASIR setelah pembayaran
        if ($lokasi != 'KASIR') {
            continue;
        }

        // Ambil setting tampilan struk kasir
        $tampilan = $this->Setting_model->get_tampilan_struk($printer['id']);

        // Generate struk berdasarkan setting
        $struk = $this->Kasir_model->generate_struk_full_by_setting($transaksi, $printer, $struk_data, $tampilan);

        // Lewati jika struk kosong
        if (trim($struk) === '' || strlen(trim($struk)) < 5) {
            $hasil[] = "ℹ️ Tidak ada isi struk untuk printer $lokasi, dilewati.";
            continue;
        }

        // Kirim ke Python server
        $res = $this->send_to_python_service($lokasi, $struk);

        if ($res === true) {
            $hasil[] = "✅ Struk berhasil dicetak ke $lokasi.";
        } else {
            $hasil[] = "❌ Gagal cetak ke $lokasi: $res";
        }
    }

    echo json_encode([
        'status' => 'success',
        'message' => "🖨️ Hasil cetak setelah bayar:\n" . implode("\n", $hasil)
    ]);
}

public function preview_struk_printer() {
    $transaksi_id = $this->input->post('transaksi_id');
    $lokasi_printer = $this->input->post('lokasi_printer');

    $this->load->model('Setting_model');
    $this->load->model('Printer_model');
    $this->load->model('Kasir_model');

    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);
    $printer = $this->Printer_model->get_by_lokasi($lokasi_printer);

    if (!$transaksi || !$printer) {
        show_error('Data transaksi atau printer tidak ditemukan.');
        return;
    }

    // Ambil data struk + tampilan
    $struk_data = $this->Setting_model->get_data_struk();
    $tampilan = $this->Setting_model->get_tampilan_struk($printer['id']);

    // Ambil detail produk (semua)
    $produk = $this->Kasir_model->get_detail_transaksi($transaksi_id);

    // Group produk
    $produk_grouped = $this->Kasir_model->group_items($produk);

    // Ambil dan mapkan extra
    $extra_grouped = $this->Kasir_model->get_detail_extra_grouped($transaksi_id);

    foreach ($produk_grouped as &$p) {
        $p['extra'] = [];

        foreach ($extra_grouped as $ex) {
            if (isset($p['detail_unit_id']) && $ex['detail_unit_id'] == $p['detail_unit_id']) {
                $p['extra'][] = [
                    'id' => $ex['pr_produk_extra_id'],
                    'harga' => $ex['harga'],
                    'satuan' => $ex['satuan'],
                    'jumlah' => $ex['jumlah_extra'],
                    'nama_extra' => $ex['nama_extra'] ?? 'Extra'
                ];
            }
        }
    }
    unset($p);

    // Simpan item grouped ke transaksi
    $transaksi['items'] = $produk_grouped;

    // Preview struk hasil render grouped
    $preview_struk = $this->Kasir_model->generate_struk_full_by_setting($transaksi, $printer, $struk_data, $tampilan);

    $data = [
        'transaksi' => $transaksi,
        'printer' => $printer,
        'struk_data' => $struk_data,
        'tampilan' => $tampilan,
        'preview_struk' => $preview_struk
    ];

    $this->load->view('kasir/preview_struk_printer', $data);
}


public function get_metode_pembayaran() {
    $metode = $this->db->get('pr_metode_pembayaran')->result();
    echo json_encode($metode);
}


private function cetak_pesanan_dibayar_internal($transaksi_id)
{
    $this->load->model('Setting_model');
    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);

    if (!$transaksi) {
        log_message('error', "Cetak internal: Transaksi ID $transaksi_id tidak ditemukan.");
        return;
    }

    $struk_data = $this->Setting_model->get_data_struk();
    $printers = $this->Printer_model->get_all();

    // ➡️ Ambil produk detail aktif
        $produk = $this->Kasir_model->get_detail_transaksi($transaksi_id, 'BERHASIL');
        

        // ✅ Langsung group item dulu
        $produk_grouped = $this->Kasir_model->group_items($produk);

        // ✅ Baru ambil semua extra grouped berdasarkan detail_unit_id
        $extra_grouped = $this->Kasir_model->get_detail_extra_grouped($transaksi_id);

        // ✅ Inject extra ke masing-masing produk_grouped
        foreach ($produk_grouped as &$p) {
            $p['extra'] = [];

            foreach ($extra_grouped as $ex) {
                if (isset($p['detail_unit_id']) && $ex['detail_unit_id'] == $p['detail_unit_id']) {
                    $p['extra'][] = [
                        'id' => $ex['pr_produk_extra_id'],
                        'harga' => $ex['harga'],
                        'satuan' => $ex['satuan'],
                        'jumlah' => $ex['jumlah_extra'],
                        'nama_extra' => $ex['nama_extra'] ?? 'Extra' // <-- tambahkan ini
                    ];


                    
                }
            }
        }
        unset($p);

        // 🔥 Update ke transaksi
        $produk_cetak = [];

        foreach ($produk_grouped as $item) {
            $produk = $this->db
                ->select('k.pr_divisi_id')
                ->from('pr_produk p')
                ->join('pr_kategori k', 'p.kategori_id = k.id', 'left')
                ->where('p.id', $item['pr_produk_id'])
                ->get()
                ->row_array();

            if (empty($printer['divisi']) || ($produk && $produk['pr_divisi_id'] == $printer['divisi'])) {
                $produk_cetak[] = $item;
            }
        }

        // 🔥 Update ke transaksi
        $transaksi['items'] = $produk_cetak;
        log_message('error', 'CETAK DIBAYAR: ' . json_encode($transaksi['items']));


    // ➡️ Cetak ke printer KASIR
    foreach ($printers as $printer) {
        $lokasi = strtoupper($printer['lokasi_printer']);

        if ($lokasi !== 'KASIR') continue;

        $tampilan = $this->Setting_model->get_tampilan_struk($printer['id']);
        $voucher_auto_list = $this->db->get_where('pr_voucher', [
            'pr_transaksi_id' => $transaksi_id,
            'status' => 'aktif'
        ])->result_array();
        
        if (!empty($voucher_auto_list)) {
            $transaksi['voucher_otomatis'] = $voucher_auto_list;
        }
        
    
        $struk = $this->Kasir_model->generate_struk_full_by_setting($transaksi, $printer, $struk_data, $tampilan);

        if (trim($struk) === '' || strlen(trim($struk)) < 5) {
            log_message('info', "Cetak internal: Struk kosong untuk printer KASIR");
            continue;
        }

        $res = $this->send_to_python_service('KASIR', $struk);

        if ($res === true) {
            log_message('info', "✅ Struk kasir berhasil dicetak otomatis (Transaksi ID: $transaksi_id)");
        } else {
            log_message('error', "❌ Gagal cetak kasir otomatis: " . print_r($res, true));
        }
    }
}

    // Fungsi untuk menyimpan transaksi
public function simpan_transaksi()
{
    $order_data = json_decode($this->input->post('order_data'), true);
 
    if (empty($order_data['items'])) {
        echo json_encode(['status' => 'error', 'message' => 'Tidak ada item dalam pesanan']);
        return;
    }

    $kasir_id = $this->session->userdata('pegawai_id');
    if (!$kasir_id) {
        echo json_encode(['status' => 'error', 'message' => 'Session kasir tidak ditemukan']);
        return;
    }

    $is_edit = isset($order_data['transaksi_id']) && intval($order_data['transaksi_id']) > 0;
    $transaksi_id = $is_edit ? intval($order_data['transaksi_id']) : null;


    if ($order_data['customer_type'] === 'walkin') {
        $customer_id = null;
        $customer_nama = $order_data['customer']; // ini diisi dari walkin customer input
    } else {
        $customer_id = !empty($order_data['customer_id']) ? intval($order_data['customer_id']) : null;
        $customer_nama = $order_data['customer']; // diisi dari pencarian customer
    }


    $total_penjualan = 0;
    foreach ($order_data['items'] as $item) {
        if (!empty($item['is_paket'])) {
            $total_penjualan += $item['harga'] * $item['jumlah'];
        } else {
            $jumlah_produk = $item['jumlah'];
            $subtotal_produk = $item['harga'] * $jumlah_produk;
            $subtotal_extra = 0;
    
            if (!empty($item['extra'])) {
                foreach ($item['extra'] as $extra) {
                    $subtotal_extra += $extra['harga'] * $jumlah_produk;
                }
            }
    
            $total_penjualan += $subtotal_produk + $subtotal_extra;
        }
    }
    
    $this->db->trans_start();

    if ($is_edit) {
        $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);
        if (!$transaksi || !empty($transaksi['waktu_bayar'])) {
            echo json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan atau sudah dibayar']);
            return;
        }

        $update_data = [
            'jenis_order_id' => $order_data['jenis_order_id'],
            'customer_id' => $customer_id,
            'customer' => $customer_nama,
            'nomor_meja' => $order_data['nomor_meja'] ?? null,
            'total_penjualan' => $total_penjualan,
            'updated_at' => date('Y-m-d H:i:s')
            // ❗ Jangan update total_pembayaran, sisa_pembayaran di edit
        ];

        $this->db->where('id', $transaksi_id)->update('pr_transaksi', $update_data);
        $this->Kasir_model->update_detail_transaksi($transaksi_id, $order_data['items'], $transaksi);
            // Tambahkan ini!
        $this->Kasir_model->update_total_transaksi($transaksi_id);
        

    } else {
        $data_transaksi = [
            'tanggal' => date('Y-m-d'),
            'no_transaksi' => $this->generate_no_transaksi(),
            'waktu_order' => date('Y-m-d H:i:s'),
            'jenis_order_id' => $order_data['jenis_order_id'],
            'customer_id' => $customer_id,
            'customer' => $customer_nama,
            'nomor_meja' => $order_data['nomor_meja'] ?? null,
            'total_penjualan' => $total_penjualan,
            'total_pembayaran' => 0, // ✅ 0
            'sisa_pembayaran' => $total_penjualan, // ✅ sisa = total
            'status_pembayaran' => 'BELUM_LUNAS', // ✅ Set BELUM_LUNAS
            'kasir_order' => $kasir_id,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $transaksi_id = $this->Kasir_model->simpan_transaksi($data_transaksi, $order_data['items']);
    }

    $this->db->trans_complete();

    // Kirim ke VPS
    $this->load->model('Api_model');

    $transaksi_data = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row_array();
    $detail_data    = $this->db->get_where('pr_detail_transaksi', ['pr_transaksi_id' => $transaksi_id])->result_array();

    $extras = [];
    foreach ($detail_data as $dt) {
        $extra = $this->db->get_where('pr_detail_extra', ['detail_transaksi_id' => $dt['id']])->result_array();
        $extras = array_merge($extras, $extra);
    }

    $paket_data = $this->db->get_where('pr_detail_transaksi_paket', ['pr_transaksi_id' => $transaksi_id])->result_array();

    $this->Api_model->kirim_data('pr_transaksi', $transaksi_data);
    $this->Api_model->insert_log_sync('pr_transaksi', $transaksi_data);
    
    $this->Api_model->kirim_data('pr_detail_transaksi', $detail_data);
    $this->Api_model->insert_log_sync('pr_detail_transaksi', $detail_data);
    
    if (!empty($extras)) {
        $this->Api_model->kirim_data('pr_detail_extra', $extras);
        $this->Api_model->insert_log_sync('pr_detail_extra', $extras);
    }
    
    if (!empty($paket_data)) {
        $this->Api_model->kirim_data('pr_detail_transaksi_paket', $paket_data);
        $this->Api_model->insert_log_sync('pr_detail_transaksi_paket', $paket_data);
    }
    
    // Cetak dan respon
    if ($this->db->trans_status()) {
        $this->cetak_pesanan_baru_internal($transaksi_id);
        echo json_encode([
            'status' => 'success',
            'message' => $is_edit ? 'Pesanan berhasil diperbarui' : 'Pesanan berhasil disimpan dan dicetak'
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan transaksi']);
    }

}


public function load_pending_orders() {
    $search = $this->input->get('search'); // Ambil parameter pencarian

    $this->db->select('id, no_transaksi, customer, nomor_meja, total_penjualan');
    $this->db->from('pr_transaksi');
    $this->db->where_not_in('status_pembayaran', ['LUNAS', 'REFUND', 'BATAL']);

    // Jika ada keyword pencarian, filter berdasarkan customer atau nomor_meja
    if (!empty($search)) {
        $this->db->group_start();
        $this->db->like('customer', $search);
        $this->db->or_like('nomor_meja', $search);
        $this->db->group_end();
    }

    $this->db->order_by('waktu_order', 'DESC');
    $result = $this->db->get()->result_array();

    echo json_encode($result);
}

// public function get_detail_transaksi()
// {
//     $transaksi_id = $this->input->post('transaksi_id');
//     $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);

//     if ($transaksi) {
//         // Tambahkan extra ke setiap item
//         foreach ($transaksi['items'] as &$item) {
//             $extras = $this->db
//                 ->select('
//                     ex.id, 
//                     ex.pr_produk_extra_id, 
//                     ex.jumlah, 
//                     ex.harga, 
//                     ex.status, 
//                     pe.nama_extra
//                 ')
//                 ->from('pr_detail_extra ex')
//                 ->join('pr_produk_extra pe', 'pe.id = ex.pr_produk_extra_id', 'left')
//                 ->where('ex.detail_transaksi_id', $item['id'])
//                 ->get()
//                 ->result_array();

//             foreach ($extras as &$ex) {
//                 $ex['nama'] = $ex['nama_extra'] ?? 'Extra';
//                 $ex['status'] = $ex['status'] ?? NULL;
//             }

//             $item['extra'] = $extras;
//         }

//         // 🔥 Ambil semua pembayaran
//         $pembayaran = $this->db
//             ->select('pb.id, pb.metode_id, pb.jumlah, mp.metode_pembayaran as metode_nama')
//             ->from('pr_pembayaran pb')
//             ->join('pr_metode_pembayaran mp', 'mp.id = pb.metode_id', 'left')
//             ->where('pb.transaksi_id', $transaksi_id)
//             ->get()
//             ->result_array();

//         // 🔥 Tambahkan pembayaran ke data transaksi, tanpa menghilangkan field lama
//         $transaksi['pembayaran'] = $pembayaran;

//         // Langsung kirim transaksi lengkap
//         echo json_encode($transaksi);
//     } else {
//         echo json_encode(['error' => 'Transaksi tidak ditemukan']);
//     }
// }
public function get_detail_transaksi()
{
    $transaksi_id = $this->input->post('transaksi_id');
    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);

    if ($transaksi) {
        // Ambil semua data paket
        $paket_data = $this->db
            ->select('dp.id as paket_id, dp.jumlah as jumlah_paket, dp.harga as harga_paket, pp.nama_paket, dp.detail_unit_paket_id')
            ->from('pr_detail_transaksi_paket dp')
            ->join('pr_produk_paket pp', 'pp.id = dp.pr_produk_paket_id', 'left')
            ->where('dp.pr_transaksi_id', $transaksi_id)
            ->where('(dp.status IS NULL OR dp.status = "")', null, false)
            ->get()->result_array();

        // Group by detail_unit_paket_id
        $paket_group = [];
        foreach ($paket_data as $p) {
            $key = $p['detail_unit_paket_id'];
            if (!isset($paket_group[$key])) {
                $paket_group[$key] = [
                    'detail_unit_paket_id' => $key,
                    'nama_paket' => $p['nama_paket'],
                    'harga_paket' => $p['harga_paket'],
                    'jumlah_paket' => 0,
                    'paket_ids' => [],
                ];
            }
            $paket_group[$key]['jumlah_paket'] += $p['jumlah_paket'];
            $paket_group[$key]['paket_ids'][] = $p['paket_id'];
        }

        // Loop semua detail transaksi dan tambahkan informasi extra dan paket
        foreach ($transaksi['items'] as &$item) {
            // Tambahkan extra
            $extras = $this->db
                ->select('ex.id, ex.pr_produk_extra_id, ex.jumlah, ex.harga, ex.status, pe.nama_extra')
                ->from('pr_detail_extra ex')
                ->join('pr_produk_extra pe', 'pe.id = ex.pr_produk_extra_id', 'left')
                ->where('ex.detail_transaksi_id', $item['id'])
                ->get()
                ->result_array();

            foreach ($extras as &$ex) {
                $ex['nama'] = $ex['nama_extra'] ?? 'Extra';
                $ex['status'] = $ex['status'] ?? null;
            }

            $item['extra'] = $extras;

            // Jika item adalah bagian dari paket
            if (!empty($item['pr_detail_transaksi_paket_id'])) {
                foreach ($paket_group as $g) {
                    if (in_array($item['pr_detail_transaksi_paket_id'], $g['paket_ids'])) {
                        $item['is_paket'] = 1;
                        $item['nama_paket'] = $g['nama_paket'];
                        $item['harga_paket'] = $g['harga_paket'];
                        $item['jumlah_paket'] = $g['jumlah_paket'];
                        $item['detail_unit_paket_id'] = $g['detail_unit_paket_id'];
                        break;
                    }
                }
            } else {
                $item['is_paket'] = 0;
            }
        }

        // Ambil pembayaran
        $pembayaran = $this->db
            ->select('pb.id, pb.metode_id, pb.jumlah, mp.metode_pembayaran as metode_nama')
            ->from('pr_pembayaran pb')
            ->join('pr_metode_pembayaran mp', 'mp.id = pb.metode_id', 'left')
            ->where('pb.transaksi_id', $transaksi_id)
            ->get()
            ->result_array();

        $transaksi['pembayaran'] = $pembayaran;

        echo json_encode($transaksi);
    } else {
        echo json_encode(['error' => 'Transaksi tidak ditemukan']);
    }
}


public function get_detail_transaksi_aktif()
{
    $transaksi_id = $this->input->post('transaksi_id');
    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);

    if ($transaksi) {
        // 🔥 Ambil hanya item aktif
        $aktif_items = [];
        foreach ($transaksi['items'] as $item) {
            if ($item['status'] === null) {
                $aktif_items[] = $item;
            }
        }

        // 🔥 Load detail extra hanya untuk item aktif
        foreach ($aktif_items as &$item) {
            $extras = $this->db
                ->select('
                    ex.id, 
                    ex.pr_produk_extra_id, 
                    ex.jumlah, 
                    ex.harga, 
                    ex.status, 
                    pe.nama_extra
                ')
                ->from('pr_detail_extra ex')
                ->join('pr_produk_extra pe', 'pe.id = ex.pr_produk_extra_id', 'left')
                ->where('ex.detail_transaksi_id', $item['id'])
                ->group_start()
                    ->where('ex.status IS NULL')
                    ->or_where('ex.status', '')
                ->group_end()
                ->get()
                ->result_array();

            foreach ($extras as &$ex) {
                $ex['nama'] = $ex['nama_extra'] ?? 'Extra';
            }

            $item['extra'] = $extras;
        }


        // 🔥 Ambil pembayaran
        $pembayaran = $this->db
            ->select('pb.id, pb.metode_id, pb.jumlah, mp.metode_pembayaran as metode_nama')
            ->from('pr_pembayaran pb')
            ->join('pr_metode_pembayaran mp', 'mp.id = pb.metode_id', 'left')
            ->where('pb.transaksi_id', $transaksi_id)
            ->get()
            ->result_array();

        // 🔥 Response: kirim SEMUA data penting
        $response = [
            'id' => $transaksi['id'],
            'no_transaksi' => $transaksi['no_transaksi'],
            'customer' => $transaksi['customer'],
            'jenis_order' => $transaksi['jenis_order'],
            'nomor_meja' => $transaksi['nomor_meja'],
            'kode_voucher' => $transaksi['kode_voucher'],
            'total_penjualan' => $transaksi['total_penjualan'],
            'diskon' => $transaksi['diskon'] ?? 0,
            'total_pembayaran' => $transaksi['total_pembayaran'] ?? 0,
            'items' => $aktif_items,
            'pembayaran' => $pembayaran
        ];

        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Transaksi tidak ditemukan']);
    }
}


// public function get_detail_transaksi_aktif()
// {
//     $transaksi_id = $this->input->post('transaksi_id');
//     $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);

//     if ($transaksi) {
//         $aktif_items = [];
//         $paket_items_group = [];

//         // Pisahkan produk reguler dan produk paket
//         foreach ($transaksi['items'] as $item) {
//             if ($item['status'] === null) {
//                 if (!empty($item['pr_detail_transaksi_paket_id'])) {
//                     // Kumpulkan isi produk dari paket berdasarkan paket_id
//                     $paket_items_group[$item['pr_detail_transaksi_paket_id']][] = $item;
//                 } else {
//                     // Produk reguler
//                     $extra = $this->db
//                         ->select('ex.id, ex.pr_produk_extra_id, ex.jumlah, ex.harga, pe.nama_extra')
//                         ->from('pr_detail_extra ex')
//                         ->join('pr_produk_extra pe', 'pe.id = ex.pr_produk_extra_id', 'left')
//                         ->where('ex.detail_transaksi_id', $item['id'])
//                         ->where('(ex.status IS NULL OR ex.status = "")', null, false)
//                         ->get()->result_array();

//                     foreach ($extra as &$ex) {
//                         $ex['nama'] = $ex['nama_extra'] ?? 'Extra';
//                     }

//                     $aktif_items[] = [
//                         'id' => $item['id'],
//                         'type' => 'produk',
//                         'nama_produk' => $item['nama_produk'],
//                         'jumlah' => $item['jumlah'],
//                         'harga' => $item['harga'],
//                         'status' => $item['status'],
//                         'extra' => $extra
//                     ];
//                 }
//             }
//         }

//         // Ambil info detail paket (paket utama)
//         if (!empty($paket_items_group)) {
//             $paket_ids = array_keys($paket_items_group);
//             $paket_data = $this->db
//                 ->select('dp.id, dp.jumlah, dp.harga, dp.detail_unit_paket_id, dp.pr_produk_paket_id, pp.nama_paket')
//                 ->from('pr_detail_transaksi_paket dp')
//                 ->join('pr_produk_paket pp', 'pp.id = dp.pr_produk_paket_id', 'left')
//                 ->where_in('dp.id', $paket_ids)
//                 ->get()->result_array();

//                 foreach ($paket_data as $paket) {
//                     $isi = [];
//                     foreach ($paket_items_group[$paket['id']] as $child) {
//                         $isi[] = [
//                             'id' => $child['id'],
//                             'nama_produk' => $child['nama_produk'],
//                             'jumlah' => $child['jumlah']
//                         ];
//                     }
                
//                     $aktif_items[] = [
//                         'id' => $paket['id'],
//                         'type' => 'paket',
//                         'is_paket' => 1,
//                         'nama_produk' => $paket['nama_paket'], // ← pastikan gunakan ini
//                         'nama_paket' => $paket['nama_paket'],
//                         'jumlah' => $paket['jumlah'],
//                         'harga' => $paket['harga'],
//                         'detail_unit_paket_id' => $paket['detail_unit_paket_id'],
//                         'pr_produk_paket_id' => $paket['pr_produk_paket_id'],
//                         'status' => null,
//                         'produk_dalam' => $isi
//                     ];
//                 }
                
//         }

//         // 🔥 Ambil pembayaran
//         $pembayaran = $this->db
//             ->select('pb.id, pb.metode_id, pb.jumlah, mp.metode_pembayaran as metode_nama')
//             ->from('pr_pembayaran pb')
//             ->join('pr_metode_pembayaran mp', 'mp.id = pb.metode_id', 'left')
//             ->where('pb.transaksi_id', $transaksi_id)
//             ->get()->result_array();

//         echo json_encode([
//             'id' => $transaksi['id'],
//             'no_transaksi' => $transaksi['no_transaksi'],
//             'customer' => $transaksi['customer'],
//             'jenis_order' => $transaksi['jenis_order'],
//             'nomor_meja' => $transaksi['nomor_meja'],
//             'kode_voucher' => $transaksi['kode_voucher'],
//             'total_penjualan' => $transaksi['total_penjualan'],
//             'diskon' => $transaksi['diskon'] ?? 0,
//             'total_pembayaran' => $transaksi['total_pembayaran'] ?? 0,
//             'items' => $aktif_items,
//             'pembayaran' => $pembayaran
//         ]);
//     } else {
//         echo json_encode(['error' => 'Transaksi tidak ditemukan']);
//     }
// }


// public function get_detail_transaksi_aktif()
// {
//     $transaksi_id = $this->input->post('transaksi_id');
//     $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);

//     if ($transaksi) {
//         // 🔥 Ambil hanya item aktif
//         $detail_unit_ids = [];
//         $aktif_items = [];
//         foreach ($transaksi['items'] as $item) {
//             if ($item['status'] === null) {
//                 $aktif_items[] = $item;
//                 $detail_unit_ids[] = $item['detail_unit_id'];
//             }
//         }

//         // 🔥 Ambil informasi paket berdasarkan detail_unit_id
//         $paket_map = [];
//         $paket_ids = array_column($aktif_items, 'pr_detail_transaksi_paket_id');

//         if (!empty($paket_ids)) {
//             $this->db->select('
//                 dp.id as pr_detail_transaksi_paket_id,
//                 dp.harga as harga_paket,
//                 dp.jumlah,
//                 pp.nama_paket
//             ');
//             $this->db->from('pr_detail_transaksi_paket dp');
//             $this->db->join('pr_produk_paket pp', 'dp.pr_produk_paket_id = pp.id', 'left');
//             $this->db->where_in('dp.id', $paket_ids);
//             $result = $this->db->get()->result_array();

//             foreach ($result as $row) {
//                 $paket_map[$row['pr_detail_transaksi_paket_id']] = $row;
//             }
//         }

//         // 🔥 Load detail extra hanya untuk item aktif
//         foreach ($aktif_items as &$item) {
//             // Ambil extra seperti sebelumnya
//             $extras = $this->db
//                 ->select('ex.id, ex.pr_produk_extra_id, ex.jumlah, ex.harga, ex.status, pe.nama_extra')
//                 ->from('pr_detail_extra ex')
//                 ->join('pr_produk_extra pe', 'pe.id = ex.pr_produk_extra_id', 'left')
//                 ->where('ex.detail_transaksi_id', $item['id'])
//                 ->group_start()
//                     ->where('ex.status IS NULL')
//                     ->or_where('ex.status', '')
//                 ->group_end()
//                 ->get()
//                 ->result_array();
        
//             foreach ($extras as &$ex) {
//                 $ex['nama'] = $ex['nama_extra'] ?? 'Extra';
//             }
        
//             $item['extra'] = $extras;
        
//             // Jika item bagian dari paket
//             if (!empty($item['pr_detail_transaksi_paket_id']) && isset($paket_map[$item['pr_detail_transaksi_paket_id']])) {
//                 $item['nama_paket'] = $paket_map[$item['pr_detail_transaksi_paket_id']]['nama_paket'];
//                 $item['harga_paket'] = $paket_map[$item['pr_detail_transaksi_paket_id']]['harga_paket'];
//                 $item['jumlah_paket'] = $paket_map[$item['pr_detail_transaksi_paket_id']]['jumlah'];
//             }
//         }
        

//         // 🔥 Ambil pembayaran
//         $pembayaran = $this->db
//             ->select('pb.id, pb.metode_id, pb.jumlah, mp.metode_pembayaran as metode_nama')
//             ->from('pr_pembayaran pb')
//             ->join('pr_metode_pembayaran mp', 'mp.id = pb.metode_id', 'left')
//             ->where('pb.transaksi_id', $transaksi_id)
//             ->get()
//             ->result_array();

//         // 🔥 Response: kirim SEMUA data penting
//         $response = [
//             'id' => $transaksi['id'],
//             'no_transaksi' => $transaksi['no_transaksi'],
//             'customer' => $transaksi['customer'],
//             'jenis_order' => $transaksi['jenis_order'],
//             'nomor_meja' => $transaksi['nomor_meja'],
//             'kode_voucher' => $transaksi['kode_voucher'],
//             'total_penjualan' => $transaksi['total_penjualan'],
//             'diskon' => $transaksi['diskon'] ?? 0,
//             'total_pembayaran' => $transaksi['total_pembayaran'] ?? 0,
//             'items' => $aktif_items,
//             'pembayaran' => $pembayaran
//         ];

//         echo json_encode($response);
//     } else {
//         echo json_encode(['error' => 'Transaksi tidak ditemukan']);
//     }
// }



public function update_order()
{
    $transaksi_id = $this->input->post('transaksi_id');
    $updated_items = json_decode($this->input->post('items'), true);

    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);
    if (!$transaksi || !empty($transaksi['waktu_bayar'])) {
        echo json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan atau sudah dibayar.']);
        return;
    }

    $this->db->trans_begin();

    foreach ($updated_items as $item) {
        if (empty($item['detail_id'])) {
            if (!empty($item['is_paket']) && $item['is_paket'] == 1) {
                // Produk Paket
                if (empty($item['pr_produk_paket_id']) || empty($item['paket_items'])) {
                    log_message('error', '❌ Produk paket tidak valid saat update transaksi: ' . json_encode($item));
                    continue;
                }

                for ($i = 0; $i < $item['jumlah']; $i++) {
                    $this->db->insert('pr_detail_transaksi_paket', [
                        'pr_transaksi_id'      => $transaksi_id,
                        'pr_produk_paket_id'   => $item['pr_produk_paket_id'],
                        'harga'                => $item['harga'],
                        'created_at'           => date('Y-m-d H:i:s'),
                    ]);
                    $paket_id = $this->db->insert_id();

                    // Simpan isi produk paket
                    foreach ($item['paket_items'] as $paket_item) {
                        $detail_unit_id = uniqid();
                        for ($j = 0; $j < $paket_item['jumlah']; $j++) {
                            $this->db->insert('pr_detail_transaksi', [
                                'pr_transaksi_id' => $transaksi_id,
                                'pr_produk_id'    => $paket_item['pr_produk_id'],
                                'jumlah'          => 1,
                                'harga'           => 0,
                                'status'          => null,
                                'is_printed'      => 0,
                                'detail_unit_id'  => $detail_unit_id,
                                'pr_detail_transaksi_paket_id' => $paket_id,
                                'created_at'      => date('Y-m-d H:i:s'),
                            ]);
                            $detail_id = $this->db->insert_id();

                            if (!empty($paket_item['extra'])) {
                                $this->Kasir_model->simpan_detail_extra($detail_id, $paket_item['extra']);
                            }
                        }
                    }
                }
            } else {
                // Produk Reguler
                if (empty($item['pr_produk_id'])) {
                    log_message('error', '❌ Produk tidak valid saat update transaksi: ' . json_encode($item));
                    continue;
                }

                $detail_unit_id = uniqid();
                for ($i = 0; $i < $item['jumlah']; $i++) {
                    $this->db->insert('pr_detail_transaksi', [
                        'pr_transaksi_id' => $transaksi_id,
                        'pr_produk_id'    => $item['pr_produk_id'],
                        'jumlah'          => 1,
                        'harga'           => $item['harga'],
                        'catatan'         => $item['catatan'] ?? null,
                        'status'          => null,
                        'is_printed'      => 0,
                        'detail_unit_id'  => $detail_unit_id,
                        'created_at'      => date('Y-m-d H:i:s'),
                    ]);
                    $detail_id = $this->db->insert_id();

                    if (!empty($item['extra'])) {
                        $this->Kasir_model->simpan_detail_extra($detail_id, $item['extra']);
                    }
                }
            }
        }
    }

    $this->Kasir_model->update_total_transaksi($transaksi_id);

    if ($this->db->trans_status() === false) {
        $this->db->trans_rollback();
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui pesanan.']);
        return;
    }

    $this->db->trans_commit();

    // Kirim ke VPS
    $this->load->model('Api_model');
    $transaksi_data = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row_array();
    $detail_data    = $this->db->get_where('pr_detail_transaksi', ['pr_transaksi_id' => $transaksi_id])->result_array();
    $paket_data     = $this->db->get_where('pr_detail_transaksi_paket', ['pr_transaksi_id' => $transaksi_id])->result_array();

    $extras = [];
    foreach ($detail_data as $dt) {
        $extra = $this->db->get_where('pr_detail_extra', ['detail_transaksi_id' => $dt['id']])->result_array();
        $extras = array_merge($extras, $extra);
    }

    $this->Api_model->kirim_data('pr_transaksi', $transaksi_data);
    $this->Api_model->insert_log_sync('pr_transaksi', $transaksi_data);

    $this->Api_model->kirim_data('pr_detail_transaksi', $detail_data);
    $this->Api_model->insert_log_sync('pr_detail_transaksi', $detail_data);

    if (!empty($extras)) {
        $this->Api_model->kirim_data('pr_detail_extra', $extras);
        $this->Api_model->insert_log_sync('pr_detail_extra', $extras);
    }

    if (!empty($paket_data)) {
        $this->Api_model->kirim_data('pr_detail_transaksi_paket', $paket_data);
        $this->Api_model->insert_log_sync('pr_detail_transaksi_paket', $paket_data);
    }

    // Cetak ulang
    $this->cetak_pesanan_baru_internal($transaksi_id);

    echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil ditambahkan.']);
}



// public function update_order()
// {
//     $order_data = json_decode($this->input->post('order_data'), true);
//     $transaksi_id = $order_data['transaksi_id'];
//     $items = $order_data['items'];

//     $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);

//     if (!$transaksi || !empty($transaksi['waktu_bayar'])) {
//         echo json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan atau sudah dibayar.']);
//         return;
//     }

//     $this->db->trans_begin();

//     // ✅ Perbarui detail transaksi (produk reguler & paket)
//     $this->Kasir_model->update_detail_transaksi($transaksi_id, $items, $transaksi);

//     // ✅ Update total penjualan
//     $this->Kasir_model->update_total_transaksi($transaksi_id);

//     if ($this->db->trans_status() === false) {
//         $this->db->trans_rollback();
//         echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan perubahan.']);
//         return;
//     }

//     $this->db->trans_commit();

//     // ✅ Sync ke VPS
//     $this->load->model('Api_model');
//     $transaksi_data = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row_array();
//     $detail_data = $this->db->get_where('pr_detail_transaksi', ['pr_transaksi_id' => $transaksi_id])->result_array();
//     $extra_data = $this->db->query("
//         SELECT e.* FROM pr_detail_extra e
//         JOIN pr_detail_transaksi d ON d.id = e.detail_transaksi_id
//         WHERE d.pr_transaksi_id = $transaksi_id
//     ")->result_array();
//     $paket_data = $this->db->get_where('pr_detail_transaksi_paket', ['pr_transaksi_id' => $transaksi_id])->result_array();

//     $this->Api_model->kirim_data('pr_transaksi', $transaksi_data);
//     $this->Api_model->kirim_data('pr_detail_transaksi', $detail_data);
//     if (!empty($extra_data)) $this->Api_model->kirim_data('pr_detail_extra', $extra_data);
//     if (!empty($paket_data)) $this->Api_model->kirim_data('pr_detail_transaksi_paket', $paket_data);

//     // ✅ Cetak ulang
//     $this->cetak_pesanan_baru_internal($transaksi_id);

//     echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil diperbarui.']);
// }


public function search_voucher()
{
    $keyword = $this->input->get('keyword');

    $this->db->select('kode_voucher, jenis, nilai, min_pembelian, tanggal_berakhir');
    $this->db->from('pr_voucher');
    $this->db->where('sisa_voucher >', 0);
    $this->db->where('status', 'aktif');

    if (!empty($keyword)) {
        $this->db->like('kode_voucher', $keyword);
    }

    $result = $this->db->get()->result();

    $data = [];
    foreach ($result as $v) {
        $data[] = [
            'kode_voucher' => $v->kode_voucher,
            'jenis' => $v->jenis,
            'nilai' => $v->nilai,
            'min_pembelian' => $v->min_pembelian,
            'tanggal_berakhir' => $v->tanggal_berakhir,
        ];
    }

    echo json_encode($data);
}


public function get_voucher_list()
{
    $search = $this->input->get('search');
    $this->db->like('kode_voucher', $search);
    $this->db->where('sisa_voucher >', 0);
    $result = $this->db->get('pr_voucher')->result();

    $data = [];
    foreach ($result as $v) {
        // Tambahkan pengecekan dan format diskon
        if ($v->jenis_diskon === 'nominal') {
            $diskon_text = 'Rp ' . number_format($v->nilai_diskon, 0, ',', '.');
        } elseif ($v->jenis_diskon === 'persentase') {
            $diskon_text = $v->nilai_diskon . '%';
        } else {
            $diskon_text = '-';
        }

        $data[] = [
            'id' => $v->id,
            'kode_voucher' => $v->kode_voucher,
            'nama_voucher' => $v->nama_voucher,
            'diskon' => $diskon_text,
            'nilai_diskon' => $v->nilai_diskon,
            'jenis_diskon' => $v->jenis_diskon,
            'min_pembelian' => $v->min_pembelian,
            'tanggal_berakhir' => $v->tanggal_berakhir,
            'foto' => $v->gambar ?? 'default.jpg'
        ];
    }

    echo json_encode($data);
}


public function cek_voucher()
{
    $kode_voucher = $this->input->post('kode_voucher');
    $items = json_decode($this->input->post('items'), true);
    $total_penjualan = intval($this->input->post('total'));

    $voucher = $this->db
        ->where('kode_voucher', $kode_voucher)
        ->where('status', 'aktif')
        ->where('tanggal_mulai <=', date('Y-m-d'))
        ->where('tanggal_berakhir >=', date('Y-m-d'))
        ->get('pr_voucher')
        ->row_array();

    if (!$voucher) {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ Kode voucher tidak ditemukan atau sudah tidak aktif'
        ]);
        return;
    }

    if (!isset($voucher['sisa_voucher']) || intval($voucher['sisa_voucher']) <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ Voucher sudah habis digunakan atau tidak tersedia'
        ]);
        return;
    }

    $diskon = 0;

    // === Jika voucher hanya untuk produk tertentu
    if (!empty($voucher['produk_id'])) {
        $subtotal_produk = 0;

        foreach ($items as $item) {
            if ($item['pr_produk_id'] == $voucher['produk_id']) {
                $subtotal_produk += $item['subtotal']; 
            }
        }

        if ($subtotal_produk == 0) {
            echo json_encode([
                'status' => 'error',
                'message' => '❌ Voucher ini hanya berlaku untuk produk tertentu yang tidak ada dalam pesanan.'
            ]);
            return;
        }

        // 🔥 Cek minimal pembelian berdasarkan subtotal produk ini saja
        if ($subtotal_produk < intval($voucher['min_pembelian'])) {
            echo json_encode([
                'status' => 'error',
                'message' => '❌ Voucher ini hanya berlaku untuk minimal belanja Rp ' . number_format($voucher['min_pembelian'], 0, ',', '.')
            ]);
            return;
        }

        // Hitung diskon
        if ($voucher['jenis'] == 'persentase') {
            $diskon = ($voucher['nilai'] / 100) * $subtotal_produk;
            if (!empty($voucher['max_diskon']) && $diskon > $voucher['max_diskon']) {
                $diskon = $voucher['max_diskon'];
            }
        } else if ($voucher['jenis'] == 'nominal') {
            $diskon = min($voucher['nilai'], $subtotal_produk);
        }
    }
    // === Jika voucher berlaku untuk semua produk
    else {
        if ($total_penjualan < intval($voucher['min_pembelian'])) {
            echo json_encode([
                'status' => 'error',
                'message' => '❌ Voucher hanya berlaku untuk minimal belanja Rp ' . number_format($voucher['min_pembelian'], 0, ',', '.')
            ]);
            return;
        }

        if ($voucher['jenis'] == 'persentase') {
            $diskon = ($voucher['nilai'] / 100) * $total_penjualan;
            if (!empty($voucher['max_diskon']) && $diskon > $voucher['max_diskon']) {
                $diskon = $voucher['max_diskon'];
            }
        } else if ($voucher['jenis'] == 'nominal') {
            $diskon = min($voucher['nilai'], $total_penjualan);
        }
    }

    $diskon = round($diskon);
    $total_bayar = max(0, $total_penjualan - $diskon);

    echo json_encode([
        'status' => 'success',
        'message' => '✅ Voucher berhasil digunakan',
        'kode_voucher' => $voucher['kode_voucher'],
        'diskon' => $diskon,
        'total_bayar' => $total_bayar,
        'total_penjualan' => $total_penjualan
    ]);
}


public function get_extra_list() {
    $this->db->select('id, sku, nama_extra, satuan, harga, hpp');
    $this->db->from('pr_produk_extra');
    $this->db->where('status', 1);
    echo json_encode($this->db->get()->result_array());
}


public function simpan_pembayaran()
{
    $this->load->model('Api_model');

    $transaksi_id = $this->input->post('transaksi_id');
    $pembayaran = json_decode($this->input->post('pembayaran'), true);
    $kasir_id = $this->session->userdata('pegawai_id');

    $kode_voucher = $this->input->post('kode_voucher');
    $diskon = intval($this->input->post('diskon'));

    $total_bayar_baru = 0;
    foreach ($pembayaran as $p) {
        $total_bayar_baru += intval($p['jumlah']);
    }

    if ($total_bayar_baru <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Jumlah pembayaran tidak boleh kosong.']);
        return;
    }

    $transaksi = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row();
    $total_penjualan = $transaksi->total_penjualan;

    $voucher = null;
    if (!empty($kode_voucher)) {
        $voucher = $this->db->get_where('pr_voucher', ['kode_voucher' => $kode_voucher])->row();
        if (!$voucher || ($voucher->sisa_voucher ?? $voucher->jumlah_diskon) <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Voucher tidak valid atau habis.']);
            return;
        }
    }

    $grand_total = $total_penjualan - $diskon;

    // 🔥 Step 1: Simpan pemb   ayaran dulu
    $this->Kasir_model->simpan_pembayaran($transaksi_id, $pembayaran, $kasir_id);

    // 🔥 Step 2: Ambil total pembayaran TERBARU dari database setelah disimpan
    $pembayaran_now = $this->db->select_sum('jumlah')
        ->where('transaksi_id', $transaksi_id)
        ->get('pr_pembayaran')
        ->row();
    $total_pembayaran_terbaru = intval($pembayaran_now->jumlah) ?? 0;

    // 🔥 Step 3: Hitung sisa pembayaran
    $sisa_pembayaran = $grand_total - $total_pembayaran_terbaru;

    if ($sisa_pembayaran <= 0) {
        $sisa_pembayaran = 0;
        $status_pembayaran = 'LUNAS';
    } else {
        $status_pembayaran = 'DP';
    }

    // 🔥 Step 4: Update transaksi
    $this->db->update('pr_transaksi', [
        'kasir_bayar' => $kasir_id,
        'waktu_bayar' => ($status_pembayaran == 'LUNAS') ? date('Y-m-d H:i:s') : NULL,
        'total_pembayaran' => $total_pembayaran_terbaru,
        'sisa_pembayaran' => $sisa_pembayaran,
        'status_pembayaran' => $status_pembayaran,
        'kode_voucher' => $kode_voucher,
        'diskon' => $diskon,
        'updated_at' => date('Y-m-d H:i:s')
    ], ['id' => $transaksi_id]);


    if ($status_pembayaran == 'LUNAS') {
        $this->db->where('pr_transaksi_id', $transaksi_id)
            ->where('status IS NULL', null, false)
            ->update('pr_detail_transaksi', ['status' => 'BERHASIL']);
        // Update juga semua extra yang terkait
        $detail_ids = $this->db->select('id')
            ->from('pr_detail_transaksi')
            ->where('pr_transaksi_id', $transaksi_id)
            ->where('status', 'BERHASIL')
            ->get()
            ->result_array();

        if (!empty($detail_ids)) {
            $detail_id_list = array_column($detail_ids, 'id');
            $this->db->where_in('detail_transaksi_id', $detail_id_list)
                    ->where('(status IS NULL OR status = "")', null, false)
                    ->update('pr_detail_extra', ['status' => 'BERHASIL']);
        }

    }

    // Tambah poin customer hanya jika transaksi sudah LUNAS
    if ($transaksi->customer_id && $status_pembayaran == 'LUNAS') {
        $detail = $this->db->get_where('pr_detail_transaksi', [
            'pr_transaksi_id' => $transaksi_id,
            'status' => 'BERHASIL'
        ])->result();

        $poin_data = [];

        foreach ($detail as $d) {
            $poin = $this->db->get_where('pr_poin', [
                'jenis_point' => 'per_produk',
                'produk_id' => $d->pr_produk_id
            ])->row();
            if ($poin) {
                $poin_data[] = [
                    'customer_id' => $transaksi->customer_id,
                    'transaksi_id' => $transaksi_id,
                    'jumlah_poin' => $poin->nilai_point * $d->jumlah,
                    'jenis' => 'per_produk',
                    'sumber' => $d->pr_produk_id,
                    'tanggal_kedaluwarsa' => date('Y-m-d', strtotime("+{$poin->kedaluwarsa_hari} days")),
                    'status' => 'aktif',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
        }

        $poin_beli = $this->db->query("SELECT * FROM pr_poin WHERE jenis_point = 'per_pembelian' ORDER BY min_pembelian DESC")->result();
        foreach ($poin_beli as $p) {
            if ($grand_total >= $p->min_pembelian) {
                $jumlah_kelipatan = floor($grand_total / $p->min_pembelian);
                $poin_data[] = [
                    'customer_id' => $transaksi->customer_id,
                    'transaksi_id' => $transaksi_id,
                    'jumlah_poin' => $jumlah_kelipatan * $p->nilai_point,
                    'jenis' => 'per_pembelian',
                    'sumber' => null,
                    'tanggal_kedaluwarsa' => date('Y-m-d', strtotime("+{$p->kedaluwarsa_hari} days")),
                    'status' => 'aktif',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                break; // pakai yang maksimal dulu
            }
        }

        
        if (!empty($poin_data)) {
            $this->db->insert_batch('pr_customer_poin', $poin_data);
        }
    }

    // Simpan log voucher hanya jika sudah LUNAS
    if (!empty($kode_voucher) && $voucher && $status_pembayaran == 'LUNAS') {
        $sisa_voucher = max(0, ($voucher->sisa_voucher ?? 1) - 1);

        $this->db->insert('pr_log_voucher', [
            'voucher_id' => $voucher->id,
            'transaksi_id' => $transaksi_id,
            'detail_transaksi_id' => null,
            'customer_id' => $transaksi->customer_id,
            'kode_voucher' => $kode_voucher,
            'jumlah_diskon' => $diskon,
            'sisa_voucher' => $sisa_voucher,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);


        $this->db->update('pr_voucher', ['sisa_voucher' => $sisa_voucher], ['id' => $voucher->id]);
        
    }

    // Simpan stamp jika memenuhi dan customer tersedia
    $stamp_data = [];
    if ($transaksi->customer_id && $status_pembayaran == 'LUNAS') {
        $produk_transaksi = $this->db->get_where('pr_detail_transaksi', [
            'pr_transaksi_id' => $transaksi_id,
            'status' => 'BERHASIL'
        ])->result();

        $promo_stamp_list = $this->db->where('aktif', 1)->get('pr_promo_stamp')->result();

        foreach ($promo_stamp_list as $promo) {
            $jumlah_stamp = 0;

            $pakai_min_pembelian = $promo->minimal_pembelian > 0 && empty($promo->produk_berlaku);
            $pakai_produk_berlaku = $promo->minimal_pembelian == 0 && !empty($promo->produk_berlaku);
            $pakai_dua_duanya = $promo->minimal_pembelian > 0 && !empty($promo->produk_berlaku);

            // --- CASE 1: Hanya berdasarkan minimal pembelian
            if ($pakai_min_pembelian) {
                if ($grand_total >= $promo->minimal_pembelian) {
                    $jumlah_stamp = $promo->berlaku_kelipatan
                        ? floor($grand_total / $promo->minimal_pembelian)
                        : 1;
                }
            }

            // --- CASE 2: Hanya berdasarkan produk tertentu
            if ($pakai_produk_berlaku) {
                $produk_ids = explode(',', $promo->produk_berlaku);
                $jumlah_produk = 0;
                foreach ($produk_transaksi as $item) {
                    if (in_array($item->pr_produk_id, $produk_ids)) {
                        $jumlah_produk += $item->jumlah;
                    }
                }
                if ($jumlah_produk > 0) {
                    $jumlah_stamp = $promo->berlaku_kelipatan
                        ? $jumlah_produk
                        : 1;
                }
            }

            // --- CASE 3: Gabungan minimal pembelian dan produk tertentu
            if ($pakai_dua_duanya) {
                if ($grand_total >= $promo->minimal_pembelian) {
                    $produk_ids = explode(',', $promo->produk_berlaku);
                    $jumlah_produk = 0;
                    foreach ($produk_transaksi as $item) {
                        if (in_array($item->pr_produk_id, $produk_ids)) {
                            $jumlah_produk += $item->jumlah;
                        }
                    }

                    if ($jumlah_produk > 0) {
                        if ($promo->berlaku_kelipatan) {
                            $kelipatan_total = floor($grand_total / $promo->minimal_pembelian);
                            $jumlah_stamp = min($kelipatan_total, $jumlah_produk);
                        } else {
                            $jumlah_stamp = 1;
                        }
                    }
                }
            }

            if ($jumlah_stamp > 0) {
                $masa_berlaku = date('Y-m-d', strtotime("+{$promo->masa_berlaku_hari} days"));
                $now = date('Y-m-d H:i:s');

                $stamp_data[] = [
                    'pr_transaksi_id' => $transaksi_id,
                    'customer_id' => $transaksi->customer_id,
                    'promo_stamp_id' => $promo->id,
                    'jumlah_stamp' => $jumlah_stamp,
                    'last_stamp_at' => $now,
                    'masa_berlaku' => $masa_berlaku,
                    'status' => 'aktif',
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
        }

        if (!empty($stamp_data)) {
            $this->db->insert_batch('pr_customer_stamp', $stamp_data);
        }

        // Update stamp yang sudah kadaluarsa
        $this->db->where('masa_berlaku <', date('Y-m-d'))
            ->where('status', 'aktif')
            ->update('pr_customer_stamp', ['status' => 'kadaluarsa']);
    }

    // 🔥 AUTO VOUCHER: Cek apakah customer dapat voucher baru
    $voucher_auto_list = [];
    if ($status_pembayaran == 'LUNAS') {
//    if ($transaksi->customer_id && $status_pembayaran == 'LUNAS') {
        $promo_voucher_list = $this->db->get_where('pr_promo_voucher_auto', ['aktif' => 1])->result();

        foreach ($promo_voucher_list as $promo) {
            $berhak = false;

            if ($promo->tipe_trigger === 'nominal' && $grand_total >= intval($promo->nilai)) {
                $berhak = true;
            }

            if ($promo->tipe_trigger === 'produk' && !empty($promo->produk_ids)) {
                $produk_ids = explode(',', $promo->produk_ids);
                $jumlah_produk = $this->db->where('pr_transaksi_id', $transaksi_id)
                                        ->where_in('pr_produk_id', $produk_ids)
                                        ->where('status', 'BERHASIL')
                                        ->count_all_results('pr_detail_transaksi');
                if ($jumlah_produk > 0) {
                    $berhak = true;
                }
            }

            if ($berhak) {
                $voucher_code = 'PROMO-' . strtoupper(substr(md5(uniqid()), 0, 6));
                $tanggal_mulai = date('Y-m-d');
                $tanggal_berakhir = date('Y-m-d', strtotime("+{$promo->masa_berlaku} days"));
                $now = date('Y-m-d H:i:s');

                $voucher_auto = [
                    'kode_voucher' => $voucher_code,
                    'jenis' => $promo->jenis,
                    'nilai' => $promo->nilai_voucher,
                    'min_pembelian' => $promo->min_pembelian,
                    'produk_id' => $promo->produk_id,
                    'jumlah_gratis' => null,
                    'max_diskon' => $promo->max_diskon,
                    'maksimal_voucher' => $promo->maksimal_voucher,
                    'sisa_voucher' => $promo->maksimal_voucher,
                    'status' => 'aktif',
                    'tanggal_mulai' => $tanggal_mulai,
                    'tanggal_berakhir' => $tanggal_berakhir,
                    'pr_transaksi_id' => $transaksi_id,
                    'customer_id' => $transaksi->customer_id ?? null, // <- ini tetap aman jika null
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                
                // Setelah insert ke pr_voucher untuk promo otomatis
                $this->db->insert('pr_voucher', $voucher_auto);
                $inserted_id = $this->db->insert_id(); // Ambil ID yang baru saja di-insert

                $voucher_terbaru = $this->db->get_where('pr_voucher', ['id' => $inserted_id])->row_array();
                if (!empty($voucher_terbaru)) {
                    $voucher_auto_list[] = $voucher_terbaru; // Simpan data yang valid dari DB
                }

            }
        }

    }


    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);
   // ❗❗❗ CETAK STRUK KASIR OTOMATIS kalau sudah LUNAS
    // 🔥 Cek lagi, jika sudah LUNAS -> cetak struk kasir
    if ($transaksi && $transaksi['status_pembayaran'] == 'LUNAS') {
        $this->cetak_pesanan_dibayar_internal($transaksi_id);
    }


    // Ambil ulang data dari database setelah semua update
    $transaksi_data = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row_array();
    $pembayaran_data = $this->db->get_where('pr_pembayaran', ['transaksi_id' => $transaksi_id])->result_array();
    $detail_data = $this->db->get_where('pr_detail_transaksi', ['pr_transaksi_id' => $transaksi_id])->result_array();

    $extra_data = [];
    foreach ($detail_data as $d) {
        $extras = $this->db->get_where('pr_detail_extra', ['detail_transaksi_id' => $d['id']])->result_array();
        $extra_data = array_merge($extra_data, $extras);
    }

    $log_voucher = $this->db->get_where('pr_log_voucher', ['transaksi_id' => $transaksi_id])->result_array();
    $customer_poin = $this->db->get_where('pr_customer_poin', ['transaksi_id' => $transaksi_id])->result_array();
    $customer_stamp = $this->db->get_where('pr_customer_stamp', ['pr_transaksi_id' => $transaksi_id])->result_array();
    
    // Kirim ke VPS via API
    $this->Api_model->kirim_data('pr_transaksi', $transaksi_data);
    $this->Api_model->kirim_data('pr_pembayaran', $pembayaran_data);
    $this->Api_model->kirim_data('pr_detail_transaksi', $detail_data);
    if (!empty($extra_data)) {
        $this->Api_model->kirim_data('pr_detail_extra', $extra_data);
    }
    if (!empty($log_voucher)) {
        $this->Api_model->kirim_data('pr_log_voucher', $log_voucher);
    }
    if (!empty($customer_poin)) {
        $this->Api_model->kirim_data('pr_customer_poin', $customer_poin);
    }
    // Ambil data voucher yang digunakan dan sinkronkan
    if (!empty($kode_voucher)) {
        $voucher_data = $this->db->get_where('pr_voucher', ['id' => $voucher->id])->row_array();
        if (!empty($voucher_data)) {
            $this->Api_model->kirim_data('pr_voucher', $voucher_data);
            
        }
    }
    if (!empty($voucher_auto_list)) {
        $this->Api_model->kirim_data('pr_voucher', $voucher_auto_list);
    }
    
    if (!empty($customer_stamp)) {
        $this->Api_model->kirim_data('pr_customer_stamp', $customer_stamp);
    }

    echo json_encode(['status' => 'success', 'message' => 'Pembayaran berhasil disimpan.']);
}



// VOID BARU ///

public function generate_kode_void()
{
    $prefix = 'V' . date('ymd'); // V240430
    $today = date('Y-m-d');

    // Hitung jumlah void hari ini
    $jumlah = $this->db
        ->where('DATE(created_at)', $today)
        ->count_all_results('pr_void');

    // Increment
    $urut = str_pad($jumlah + 1, 3, '0', STR_PAD_LEFT);

    return $prefix . $urut; // Hasil: V240430001
}



public function void_pilihan()
{
    $transaksi_id = $this->input->post('transaksi_id');
    $items = json_decode($this->input->post('items'), true);
    $alasan = trim($this->input->post('alasan'));
    

    if (!$items || !$alasan) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
        return;
    }

    $this->load->model('Kasir_model');
    $new_void_ids = $this->Kasir_model->void_batch($items, $alasan);

    echo json_encode(['status' => 'success', 'message' => 'Produk berhasil di-void', 'void_ids' => $new_void_ids]);
}



public function cetak_void() {
    $transaksi_id = $this->input->post('transaksi_id');
    $this->load->model('Kasir_model');
    $hasil = $this->Kasir_model->cetak_void($transaksi_id);

    echo json_encode($hasil);
}


// public function void_semua()
// {
//     $this->load->model('Kasir_model');
//     $transaksi_id = $this->input->post('transaksi_id');
//     $user_id = $this->session->userdata('pegawai_id');
//     $kode_void = $this->Kasir_model->generate_kode_void();

//     if (!$transaksi_id) {
//         echo json_encode(['status' => 'error', 'message' => 'ID transaksi tidak valid']);
//         return;
//     }

//     $transaksi = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row();
//     if (!$transaksi) {
//         echo json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan']);
//         return;
//     }

//     // Ambil semua produk aktif (status NULL) + JOIN nama_produk
//     $items = $this->db
//         ->select('d.*, p.nama_produk')
//         ->from('pr_detail_transaksi d')
//         ->join('pr_produk p', 'p.id = d.pr_produk_id', 'left')
//         ->where('d.pr_transaksi_id', $transaksi_id)
//         ->where('d.status IS NULL', null, false)
//         ->get()
//         ->result();

//     foreach ($items as $item) {
//         // Void produk utama
//         $this->db->where('id', $item->id)->update('pr_detail_transaksi', ['status' => 'BATAL']);

//         $this->db->insert('pr_void', [
//             'pr_transaksi_id' => $transaksi_id,
//             'kode_void' => $kode_void,
//             'no_transaksi' => $transaksi->no_transaksi,
//             'detail_transaksi_id' => $item->id,
//             'nama_produk' => $item->nama_produk ?? 'Produk Tidak Dikenal',
//             'pr_produk_id' => $item->pr_produk_id,
//             'jumlah' => $item->jumlah,
//             'harga' => $item->harga,
//             'alasan' => 'Dibatalkan Semua',
//             'void_by' => $user_id,
//             'waktu' => date('Y-m-d H:i:s'),
//             'created_at' => date('Y-m-d H:i:s'),
//             'updated_at' => date('Y-m-d H:i:s'),
//         ]);

//         // Ambil semua extra aktif (status NULL) + JOIN nama_extra
//         $extras = $this->db
//             ->select('ex.*, pe.nama_extra')
//             ->from('pr_detail_extra ex')
//             ->join('pr_produk_extra pe', 'pe.id = ex.pr_produk_extra_id', 'left')
//             ->where('ex.detail_transaksi_id', $item->id)
//             ->where('ex.status IS NULL', null, false)
//             ->get()
//             ->result();

//         foreach ($extras as $extra) {
//             $this->db->where('id', $extra->id)->update('pr_detail_extra', ['status' => 'BATAL']);
//             $this->db->insert('pr_void', [
//                 'pr_transaksi_id' => $transaksi_id,
//                 'kode_void' => $kode_void,
//                 'no_transaksi' => $transaksi->no_transaksi,
//                 'detail_transaksi_id' => $item->id,
//                 'nama_produk' => $item->nama_produk ?? 'Produk Tidak Dikenal',
//                 'pr_produk_id' => $item->pr_produk_id,
//                 'detail_extra_id' => $extra->id,
//                 'produk_extra_id' => $extra->pr_produk_extra_id,
//                 'nama_extra' => $extra->nama_extra ?? 'Extra Tidak Dikenal',
//                 'jumlah' => $extra->jumlah,
//                 'harga' => $extra->harga,
//                 'alasan' => 'Dibatalkan Semua',
//                 'void_by' => $user_id,
//                 'waktu' => date('Y-m-d H:i:s'),
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'updated_at' => date('Y-m-d H:i:s'),
//             ]);
//         }
//     }

//     // Update transaksi utama: total_penjualan 0, status_pembayaran BATAL
//     $this->db->where('id', $transaksi_id)->update('pr_transaksi', [
//         'total_penjualan' => 0,
//         'status_pembayaran' => 'BATAL'
//     ]);

//     echo json_encode(['status' => 'success', 'message' => 'Semua item berhasil dibatalkan.']);
// }


public function pesanan_terbayar()
{
  $data['title'] = "Pesanan Terbayar";
  $this->load->view('templates/header', $data);
  $this->load->view('kasir/pesanan_terbayar');
  $this->load->view('templates/footer');


}

public function get_pesanan_terbayar()
{
    $tanggal_awal = $this->input->get('tanggal_awal');
    $tanggal_akhir = $this->input->get('tanggal_akhir');
    $search = $this->input->get('search');

    $this->db->select('id, no_transaksi, customer, tanggal, total_pembayaran, status_pembayaran');
    $this->db->from('pr_transaksi');
    $this->db->where_in('status_pembayaran', ['LUNAS', 'REFUND']); // ✅ BISA LUNAS ATAU REFUND

    if ($tanggal_awal && $tanggal_akhir) {
        $this->db->where('tanggal >=', $tanggal_awal);
        $this->db->where('tanggal <=', $tanggal_akhir);
    }

    if ($search) {
        $this->db->group_start();
        $this->db->like('no_transaksi', $search);
        $this->db->or_like('customer', $search);
        $this->db->group_end();
    }

    $this->db->order_by('tanggal', 'DESC');

    $query = $this->db->get();
    echo json_encode($query->result());
}


// HALAMAN RINCIAN PESANAN TERBAYAR UNTUK REFUND
public function rincian_pesanan($id)
{
    $kode_refund = $this->input->get('kode_refund');

    $this->db->select('id, no_transaksi, customer, tanggal, total_pembayaran');
    $this->db->from('pr_transaksi');
    $this->db->where('id', $id);
    $transaksi = $this->db->get()->row();

    if (!$transaksi) {
        show_404();
    }

    $this->db->select('dt.*, p.nama_produk');
    $this->db->from('pr_detail_transaksi dt');
    $this->db->join('pr_produk p', 'dt.pr_produk_id = p.id', 'left');
    $this->db->where('dt.pr_transaksi_id', $id);
    $this->db->where('dt.status !=', 'BATAL');
    $items = $this->db->get()->result();

    foreach ($items as &$item) {
        $this->db->select('de.id, e.nama_extra as nama, e.harga, de.jumlah');
        $this->db->from('pr_detail_extra de');
        $this->db->join('pr_produk_extra e', 'de.pr_produk_extra_id = e.id', 'left');
        $this->db->where('de.detail_transaksi_id', $item->id);
        $item->extra = $this->db->get()->result();
    }
    
    $kode_refund = $this->session->flashdata('kode_refund') ?? $this->input->get('kode_refund');
    $data['transaksi'] = $transaksi;
    $data['items'] = $items;
    $data['kode_refund'] = $kode_refund;

    // ✅ Tambahan ini
    $data['metode_pembayaran'] = $this->db
        ->select('id, metode_pembayaran')
        ->order_by('id', 'ASC')
        ->get('pr_metode_pembayaran')
        ->result();


    $data['title'] = "Rincian Pesanan";
    $this->load->view('templates/header', $data);
    $this->load->view('kasir/rincian_pesanan', $data);
    $this->load->view('templates/footer');
}


// --- API Refund

public function cetak_refund_internal()
{
    $kode_refund = $this->input->post('kode_refund');
    $this->load->model('Setting_model');
    $this->load->model('Printer_model');

    if (empty($kode_refund)) {
        echo json_encode(['status' => 'error', 'message' => 'Kode refund kosong.']);
        return;
    }

    // Ambil semua refund item berdasarkan kode refund
    $refunds = $this->db
        ->select('
            r.*, 
            dt.pr_produk_id, 
            p.nama_produk, 
            r.produk_extra_id, 
            e.nama_extra, 
            t.no_transaksi, 
            r.kode_refund,
            t.customer, 
            t.nomor_meja, 
            k.pr_divisi_id, 
            pg.nama as kasir_order
        ')
        ->from('pr_refund r')
        ->join('pr_detail_transaksi dt', 'dt.id = r.pr_detail_transaksi_id', 'left')
        ->join('pr_produk p', 'p.id = dt.pr_produk_id', 'left')
        ->join('pr_produk_extra e', 'e.id = r.produk_extra_id', 'left')
        ->join('pr_kategori k', 'k.id = p.kategori_id', 'left')
        ->join('pr_transaksi t', 't.id = r.pr_transaksi_id', 'left')
        ->join('abs_pegawai pg', 'pg.id = t.kasir_order', 'left')
        ->where('r.kode_refund', $kode_refund)
        ->get()
        ->result_array();


    if (empty($refunds)) {
        echo json_encode(['status' => 'error', 'message' => 'Data refund tidak ditemukan.']);
        return;
    }

    $transaksi = [
        'no_transaksi' => $refunds[0]['no_transaksi'],
        'kode_refund' => $refunds[0]['kode_refund'],
        'customer' => $refunds[0]['customer'],
        'nomor_meja' => $refunds[0]['nomor_meja'],
        'kasir_order' => $refunds[0]['kasir_order']
    ];

    $struk_data = $this->Setting_model->get_data_struk();

    // Group refund per divisi
    $refund_per_divisi = [];
    foreach ($refunds as $r) {
        $divisi_id = $r['pr_divisi_id'] ?: 0;
        $refund_per_divisi[$divisi_id][] = $r;
    }

    // Cetak per divisi
    foreach ($refund_per_divisi as $divisi_id => $list_refund) {
        $printer = $this->Printer_model->get_by_divisi($divisi_id);
        if (!$printer) continue;

        $lokasi = strtoupper($printer['lokasi_printer']);
        if (!in_array($lokasi, ['BAR', 'KITCHEN'])) continue;

        $tampilan = $this->Setting_model->get_tampilan_struk($printer['id']);
        $struk_text = $this->Kasir_model->generate_refund_struk($transaksi, $list_refund, $printer, $struk_data, $lokasi);

        $this->send_to_python_service($lokasi, $struk_text);
    }

    // Cetak ke CHECKER
    $printer_checker = $this->Printer_model->get_by_lokasi('CHECKER');
    if ($printer_checker) {
        $tampilan_checker = $this->Setting_model->get_tampilan_struk($printer_checker['id']);
        $struk_checker = $this->Kasir_model->generate_refund_struk($transaksi, $refunds, $printer_checker, $struk_data, 'CHECKER');

        $this->send_to_python_service('CHECKER', $struk_checker);
    }

    echo json_encode(['status' => 'success', 'message' => 'Refund berhasil dicetak']);
}



public function refund_produk($detail_id)
{
    $this->load->library('session');
    $this->load->model('Api_model');

    $kasir_id = $this->session->userdata('pegawai_id') ?? 0;
    $alasan = $this->input->get('alasan') ?? 'Refund produk';
    $kode_refund = $this->Refund_model->generate_kode_refund();
    $metode_pembayaran_id = $this->input->get('metode');

    // Ambil detail transaksi
    $this->db->select('dt.*, p.nama_produk');
    $this->db->from('pr_detail_transaksi dt');
    $this->db->join('pr_produk p', 'dt.pr_produk_id = p.id', 'left');
    $this->db->where('dt.id', $detail_id);
    $detail = $this->db->get()->row();

    if (!$detail) show_404();

    $transaksi = $this->db->get_where('pr_transaksi', ['id' => $detail->pr_transaksi_id])->row();
    if (!$transaksi) show_404();

    $no_transaksi = $transaksi->no_transaksi;

    // 🔁 Simpan produk utama ke pr_refund + kirim ke VPS
    $data_refund = [
        'kode_refund' => $kode_refund,
        'pr_transaksi_id' => $transaksi->id,
        'pr_detail_transaksi_id' => $detail->id,
        'pr_produk_id' => $detail->pr_produk_id,
        'no_transaksi' => $no_transaksi,
        'nama_produk' => $detail->nama_produk,
        'jumlah' => $detail->jumlah,
        'harga' => $detail->harga,
        'catatan' => 'Refund produk',
        'alasan' => $alasan,
        'refund_by' => $kasir_id,
        'metode_pembayaran_id' => $metode_pembayaran_id,
        'waktu_refund' => date('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        'detail_extra_id' => null,
        'produk_extra_id' => null,
        'nama_extra' => null,
    ];
    $this->db->insert('pr_refund', $data_refund);
    $this->Api_model->kirim_data('pr_refund', [$data_refund]);

    // 🔁 Simpan extra jika ada + kirim ke VPS
    $this->db->select('de.*, e.nama_extra');
    $this->db->from('pr_detail_extra de');
    $this->db->join('pr_produk_extra e', 'de.pr_produk_extra_id = e.id', 'left');
    $this->db->where('de.detail_transaksi_id', $detail_id);
    $extras = $this->db->get()->result();

    foreach ($extras as $ex) {
        $data_refund_extra = [
            'kode_refund' => $kode_refund,
            'pr_transaksi_id' => $transaksi->id,
            'pr_detail_transaksi_id' => $detail->id,
            'pr_produk_id' => $detail->pr_produk_id,
            'no_transaksi' => $no_transaksi,
            'nama_produk' => $detail->nama_produk,
            'jumlah' => $ex->jumlah,
            'harga' => $ex->harga,
            'catatan' => 'Refund extra',
            'alasan' => $alasan,
            'refund_by' => $kasir_id,
            'metode_pembayaran_id' => $metode_pembayaran_id,
            'waktu_refund' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'detail_extra_id' => $ex->id,
            'produk_extra_id' => $ex->pr_produk_extra_id,
            'nama_extra' => $ex->nama_extra,
        ];
        $this->db->insert('pr_refund', $data_refund_extra);
        $this->Api_model->kirim_data('pr_refund', [$data_refund_extra]);

        $this->db->set('status', 'REFUND')->where('id', $ex->id)->update('pr_detail_extra');
        $updated_extra = $this->db->get_where('pr_detail_extra', ['id' => $ex->id])->row_array();
        $this->Api_model->kirim_data('pr_detail_extra', [$updated_extra]);
    }

    // ✅ Update status produk
    $this->db->set('status', 'REFUND')->where('id', $detail_id)->update('pr_detail_transaksi');
    $updated_detail = $this->db->get_where('pr_detail_transaksi', ['id' => $detail_id])->row_array();
    $this->Api_model->kirim_data('pr_detail_transaksi', [$updated_detail]);


    // ✅ Jika semua produk di transaksi sudah REFUND, update status transaksi
    $cek_aktif = $this->db->where([
        'pr_transaksi_id' => $transaksi->id,
        'status' => 'BERHASIL'
    ])->count_all_results('pr_detail_transaksi');

    // if ($cek_aktif == 0) {
    //     $this->db->set('status_pembayaran', 'REFUND')->where('id', $transaksi->id)->update('pr_transaksi');
    // }

    $this->session->set_flashdata('kode_refund', $kode_refund);
    redirect('kasir/rincian_pesanan/' . $transaksi->id . '?kode_refund=' . $kode_refund);
}


public function refund_pilihan()
{
    $this->load->library('session');
    $this->load->model('Api_model');

    $kasir_id = $this->session->userdata('pegawai_id') ?? 0;
    $kode_refund = $this->Refund_model->generate_kode_refund();
    $metode_pembayaran_id = $this->input->post('metode_pembayaran_id');
    $produk_ids = $this->input->post('produk_ids');
    $extra_ids  = $this->input->post('extra_ids');
    $alasan = $this->input->post('alasan');
    $transaksi_id = $this->input->post('transaksi_id');

    if ((!$produk_ids && !$extra_ids) || !$alasan || !$transaksi_id) {
        show_error("Input tidak valid.");
    }

    $no_transaksi = $this->db->select('no_transaksi')->get_where('pr_transaksi', ['id' => $transaksi_id])->row('no_transaksi');

    $refund_data = [];
    $updated_detail_transaksi = [];
    $updated_detail_extra = [];

    // =====================
    // ✅ Refund Produk Utama
    // =====================
    if (!empty($produk_ids)) {
        foreach ($produk_ids as $detail_id) {
            $detail = $this->db->select('dt.*, p.nama_produk')
                ->from('pr_detail_transaksi dt')
                ->join('pr_produk p', 'dt.pr_produk_id = p.id', 'left')
                ->where('dt.id', $detail_id)->get()->row();

            if (!$detail) continue;

            $data_refund = [
                'kode_refund' => $kode_refund,
                'pr_transaksi_id' => $transaksi_id,
                'pr_detail_transaksi_id' => $detail->id,
                'pr_produk_id' => $detail->pr_produk_id,
                'no_transaksi' => $no_transaksi,
                'nama_produk' => $detail->nama_produk,
                'jumlah' => $detail->jumlah,
                'harga' => $detail->harga,
                'catatan' => 'Refund pilihan - produk',
                'alasan' => $alasan,
                'refund_by' => $kasir_id,
                'metode_pembayaran_id' => $metode_pembayaran_id,
                'waktu_refund' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'detail_extra_id' => null,
                'produk_extra_id' => null,
                'nama_extra' => null,
            ];
            $this->db->insert('pr_refund', $data_refund);
            $refund_data[] = $data_refund;

            $this->db->set('status', 'REFUND')->where('id', $detail_id)->update('pr_detail_transaksi');
            $updated_detail_transaksi[] = $this->db->get_where('pr_detail_transaksi', ['id' => $detail_id])->row_array();

            // Ambil dan proses semua extra yang terkait produk ini
            $extras = $this->db->select('de.*, e.nama_extra')
                ->from('pr_detail_extra de')
                ->join('pr_produk_extra e', 'de.pr_produk_extra_id = e.id', 'left')
                ->where('de.detail_transaksi_id', $detail_id)->get()->result();

            foreach ($extras as $ex) {
                $data_refund_extra = [
                    'kode_refund' => $kode_refund,
                    'pr_transaksi_id' => $transaksi_id,
                    'pr_detail_transaksi_id' => $detail->id,
                    'pr_produk_id' => $detail->pr_produk_id,
                    'no_transaksi' => $no_transaksi,
                    'nama_produk' => $detail->nama_produk,
                    'jumlah' => $ex->jumlah,
                    'harga' => $ex->harga,
                    'catatan' => 'Refund pilihan - extra',
                    'alasan' => $alasan,
                    'refund_by' => $kasir_id,
                    'metode_pembayaran_id' => $metode_pembayaran_id,
                    'waktu_refund' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'detail_extra_id' => $ex->id,
                    'produk_extra_id' => $ex->pr_produk_extra_id,
                    'nama_extra' => $ex->nama_extra,
                ];
                $this->db->insert('pr_refund', $data_refund_extra);
                $refund_data[] = $data_refund_extra;

                $this->db->set('status', 'REFUND')->where('id', $ex->id)->update('pr_detail_extra');
                $updated_detail_extra[] = $this->db->get_where('pr_detail_extra', ['id' => $ex->id])->row_array();
            }
        }
    }

    // =====================
    // ✅ Refund Extra Pilihan (jika tidak ikut dari produk utama)
    // =====================
    if (!empty($extra_ids)) {
        foreach ($extra_ids as $extra_id) {
            // Skip jika sudah diproses di atas (karena ikut produk yang direfund)
            if (in_array($extra_id, array_column($updated_detail_extra, 'id'))) continue;

            $ex = $this->db->select('de.*, e.nama_extra, dt.pr_produk_id, dt.pr_transaksi_id, p.nama_produk')
                ->from('pr_detail_extra de')
                ->join('pr_produk_extra e', 'de.pr_produk_extra_id = e.id', 'left')
                ->join('pr_detail_transaksi dt', 'de.detail_transaksi_id = dt.id', 'left')
                ->join('pr_produk p', 'dt.pr_produk_id = p.id', 'left')
                ->where('de.id', $extra_id)->get()->row();

            if (!$ex) continue;

            $data_refund_extra = [
                'kode_refund' => $kode_refund,
                'pr_transaksi_id' => $transaksi_id,
                'pr_detail_transaksi_id' => $ex->detail_transaksi_id,
                'pr_produk_id' => $ex->pr_produk_id,
                'no_transaksi' => $no_transaksi,
                'nama_produk' => $ex->nama_produk,
                'jumlah' => $ex->jumlah,
                'harga' => $ex->harga,
                'catatan' => 'Refund pilihan - extra (manual)',
                'alasan' => $alasan,
                'refund_by' => $kasir_id,
                'metode_pembayaran_id' => $metode_pembayaran_id,
                'waktu_refund' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'detail_extra_id' => $ex->id,
                'produk_extra_id' => $ex->pr_produk_extra_id,
                'nama_extra' => $ex->nama_extra,
            ];
            $this->db->insert('pr_refund', $data_refund_extra);
            $refund_data[] = $data_refund_extra;

            $this->db->set('status', 'REFUND')->where('id', $ex->id)->update('pr_detail_extra');
            $updated_detail_extra[] = $this->db->get_where('pr_detail_extra', ['id' => $ex->id])->row_array();
        }
    }

    
    // // Cek status transaksi
    // $sisa = $this->db->where(['pr_transaksi_id' => $transaksi_id, 'status' => 'BERHASIL'])->count_all_results('pr_detail_transaksi');
    // if ($sisa == 0) {
    //     $this->db->set('status_pembayaran', 'REFUND')->where('id', $transaksi_id)->update('pr_transaksi');
    //     $transaksi = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row_array();
    //     $this->Api_model->kirim_data('pr_transaksi', $transaksi);
    // }

    // 🔁 Sync ke VPS
    if (!empty($refund_data)) {
        $this->Api_model->kirim_data('pr_refund', $refund_data);
    }
    if (!empty($updated_detail_transaksi)) {
        $this->Api_model->kirim_data('pr_detail_transaksi', $updated_detail_transaksi);
    }
    if (!empty($updated_detail_extra)) {
        $this->Api_model->kirim_data('pr_detail_extra', $updated_detail_extra);
    }

    $this->session->set_flashdata('kode_refund', $kode_refund);
    echo json_encode([
        'status' => 'ok',
        'redirect' => base_url('kasir/rincian_pesanan/' . $transaksi_id . '?kode_refund=' . $kode_refund)
    ]);
}



public function refund_semua($transaksi_id)
{
    $this->load->library('session');
    $this->load->model('Api_model');

    $kasir_id = $this->session->userdata('pegawai_id') ?? 0;
    $alasan = $this->input->get('alasan') ?? 'Refund semua produk';
    $kode_refund = $this->Refund_model->generate_kode_refund();
    $metode_pembayaran_id = $this->input->get('metode');

    $this->db->select('dt.*, p.nama_produk');
    $this->db->from('pr_detail_transaksi dt');
    $this->db->join('pr_produk p', 'dt.pr_produk_id = p.id', 'left');
    $this->db->where(['dt.pr_transaksi_id' => $transaksi_id, 'dt.status' => 'BERHASIL']);
    $details = $this->db->get()->result();

    $no_transaksi = $this->db->select('no_transaksi')->get_where('pr_transaksi', ['id' => $transaksi_id])->row('no_transaksi');

    $refund_data = [];
    $updated_detail_transaksi = [];
    $updated_detail_extra = [];

    foreach ($details as $detail) {
        // Produk utama
        $data_refund = [
            'kode_refund' => $kode_refund,
            'pr_transaksi_id' => $transaksi_id,
            'pr_detail_transaksi_id' => $detail->id,
            'pr_produk_id' => $detail->pr_produk_id,
            'no_transaksi' => $no_transaksi,
            'nama_produk' => $detail->nama_produk,
            'jumlah' => $detail->jumlah,
            'harga' => $detail->harga,
            'catatan' => 'Refund semua - produk',
            'alasan' => $alasan,
            'refund_by' => $kasir_id,
            'metode_pembayaran_id' => $metode_pembayaran_id,
            'waktu_refund' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'detail_extra_id' => null,
            'produk_extra_id' => null,
            'nama_extra' => null,
        ];
        $this->db->insert('pr_refund', $data_refund);
        $refund_data[] = $data_refund;

        $this->db->set('status', 'REFUND')->where('id', $detail->id)->update('pr_detail_transaksi');
        $updated_detail_transaksi[] = $this->db->get_where('pr_detail_transaksi', ['id' => $detail->id])->row_array();

        // Extra
        $this->db->select('de.*, e.nama_extra');
        $this->db->from('pr_detail_extra de');
        $this->db->join('pr_produk_extra e', 'de.pr_produk_extra_id = e.id', 'left');
        $this->db->where('de.detail_transaksi_id', $detail->id);
        $extras = $this->db->get()->result();

        foreach ($extras as $ex) {
            $data_refund_extra = [
                'kode_refund' => $kode_refund,
                'pr_transaksi_id' => $transaksi_id,
                'pr_detail_transaksi_id' => $detail->id,
                'pr_produk_id' => $detail->pr_produk_id,
                'no_transaksi' => $no_transaksi,
                'nama_produk' => $detail->nama_produk,
                'jumlah' => $ex->jumlah,
                'harga' => $ex->harga,
                'catatan' => 'Refund semua - extra',
                'alasan' => $alasan,
                'refund_by' => $kasir_id,
                'metode_pembayaran_id' => $metode_pembayaran_id,
                'waktu_refund' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'detail_extra_id' => $ex->id,
                'produk_extra_id' => $ex->pr_produk_extra_id,
                'nama_extra' => $ex->nama_extra,
            ];
            $this->db->insert('pr_refund', $data_refund_extra);
            $refund_data[] = $data_refund_extra;

            $this->db->set('status', 'REFUND')->where('id', $ex->id)->update('pr_detail_extra');
            $updated_detail_extra[] = $this->db->get_where('pr_detail_extra', ['id' => $ex->id])->row_array();
        }
    }

    // ❌ Jangan ubah status pembayaran transaksi
    // $this->db->set('status_pembayaran', 'REFUND')->where('id', $transaksi_id)->update('pr_transaksi');

    // 🔁 Kirim ke VPS
    if (!empty($refund_data)) {
        $this->Api_model->kirim_data('pr_refund', $refund_data);
    }
    if (!empty($updated_detail_transaksi)) {
        $this->Api_model->kirim_data('pr_detail_transaksi', $updated_detail_transaksi);
    }
    if (!empty($updated_detail_extra)) {
        $this->Api_model->kirim_data('pr_detail_extra', $updated_detail_extra);
    }

    $this->session->set_flashdata('kode_refund', $kode_refund);
    redirect('kasir/rincian_pesanan/' . $transaksi_id . '?kode_refund=' . $kode_refund);
}

public function daftar_refund()
{
    $data['title'] = "Laporan Refund";
    $this->load->view('templates/header', $data);
    $this->load->view('kasir/daftar_refund', $data);
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

public function get_detail_refund()
{
    $kode = $this->input->get('kode_refund');
    $this->load->model('Refund_model');
    $data['refund'] = $this->Refund_model->get_by_kode($kode);

    if (!$data['refund']) {
        echo '<div class="text-danger">Data refund tidak ditemukan.</div>';
        return;
    }

    $this->load->view('kasir/modal_detail_refund', $data);
}



public function detail_refund_kode($kode_refund_encoded)
{
    $kode_refund = urldecode($kode_refund_encoded); // decode dulu
    $this->load->model('Refund_model');

    $refund = $this->Refund_model->get_by_kode($kode_refund);
    if (!$refund) {
        show_error("Data refund tidak ditemukan.");
    }

    $data['refund'] = $refund;
    $data['title'] = "Detail Refund";

    $this->load->view('templates/header', $data);
    $this->load->view('kasir/detail_refund', $data);
    $this->load->view('templates/footer');
}

public function transaksi_pending()
    {
    $tanggal_awal = $this->input->get('tanggal_awal') ?? date('Y-m-d');
    $tanggal_akhir = $this->input->get('tanggal_akhir') ?? date('Y-m-d');

    $data['title'] = "Transaksi Belum Dibayar";
    $data['tanggal_awal'] = $tanggal_awal;
    $data['tanggal_akhir'] = $tanggal_akhir;
    $data['jenis_order'] = $this->Kasir_model->get_jenis_order();
    $data['kategori'] = $this->Kasir_model->get_kategori_produk();
    $data['printer'] = $this->Kasir_model->get_list_printer();
    $data['pending'] = $this->Kasir_model->get_pending_orders_filtered($tanggal_awal, $tanggal_akhir);

    $this->load->view('templates/header', $data);
    $this->load->view('kasir/transaksi_pending', $data);
    $this->load->view('templates/footer');
}

public function get_transaksi_pending()
{
    $this->load->model('Kasir_model');
    $pending = $this->Kasir_model->get_pending_orders();
    echo json_encode($pending);
}
public function detail($id)
{
    $this->load->model('Kasir_model');

    $transaksi = $this->Kasir_model->get_transaksi_by_id($id);

    if (!$transaksi) {
        show_404();
    }

    $transaksi['items'] = $this->Kasir_model->group_items($transaksi['items']);
    $transaksi['extra_grouped'] = $this->Kasir_model->get_detail_extra_grouped($id);

    $data['title'] = "Detail Transaksi";
    $data['transaksi'] = $transaksi;

    $this->load->view('templates/header', $data);
    $this->load->view('kasir/detail_transaksi', $data);
    $this->load->view('templates/footer');
}


}