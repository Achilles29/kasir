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

        log_message('debug', 'Kasir Controller Initialized');
        // Periksa apakah pengguna sudah login
        if (!$this->session->userdata('username')) {
            redirect('auth'); // Redirect ke halaman login jika belum login
        }
    }

    public function index() {

        $data['title'] = 'POS Namua Coffee & Eatery';
        $data['jenis_order'] = $this->db->get('pr_jenis_order')->result_array();
        $data['metode_pembayaran'] = $this->db->get('pr_metode_pembayaran')->result_array();
        $data['kategori'] = $this->Produk_model->get_kategori_pos(); // Kategori untuk tab
        $data['produk'] = $this->Produk_model->search_produk_pos('', ''); // Semua produk saat awal load
//        $this->load->view('templates/header', $data);
        $data['printer'] = $this->Printer_model->get_all_with_divisi();
        $data['divisi'] = $this->db->get('pr_divisi')->result_array();


$this->load->view('kasir/index', $data);
//        $this->load->view('templates/footer');
    }
public function get_printer_list() {
    $data = $this->Printer_model->get_all_printers();
    echo json_encode($data);
}

    // Load Produk AJAX untuk pencarian & kategori
public function load_produk() {
    $kategori = $this->input->get('kategori');
    $search = $this->input->get('search');

    $this->db->select('pr_produk.id, pr_produk.nama_produk, FLOOR(pr_produk.harga_jual) AS harga_jual, pr_produk.foto, pr_kategori.urutan AS urutan_kategori');
    $this->db->from('pr_produk');
    $this->db->join('pr_kategori', 'pr_produk.kategori_id = pr_kategori.id', 'left');
    $this->db->where('pr_produk.tampil', 1); // Hanya tampilkan produk yang aktif

    if (!empty($kategori)) {
        $this->db->where('pr_produk.kategori_id', $kategori);
    }
    if (!empty($search)) {
        $this->db->like('pr_produk.nama_produk', $search);
    }

    $this->db->order_by('pr_kategori.urutan', 'ASC');
    $this->db->order_by('pr_produk.id', 'ASC');

    $query = $this->db->get();
    echo json_encode($query->result_array());
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


public function cetak_pending_printer() {
    $transaksi_id = $this->input->post('transaksi_id');
    $lokasi_printer = $this->input->post('lokasi_printer');

    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);
    $this->load->model('Setting_model');
    $printer = $this->Printer_model->get_by_lokasi($lokasi_printer);
    $tampilan = $this->Setting_model->get_tampilan_struk($printer['id']);
    $struk_data = $this->Setting_model->get_data_struk();
    $text = $this->Kasir_model->generate_struk_full_by_setting($transaksi, $printer, $struk_data, $tampilan);


    $res = $this->send_to_python_service($lokasi_printer, $text);

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

    echo json_encode([
        'status' => 'success',
        'message' => "🖨️ Hasil cetak pesanan:\n" . implode("\n", $hasil)
    ]);
}

private function cetak_pesanan_baru_internal($transaksi_id)
{
    $this->load->model('Setting_model');
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
                    'nama_extra' => $ex['nama_extra'] ?? 'Extra' // <-- tambahkan ini
                ];
            }
        }
    }

    unset($p); // penting!


    // ✅ Cetak ke printer masing-masing
    foreach ($printers as $printer) {
        $lokasi = strtoupper($printer['lokasi_printer']);

        if (!in_array($lokasi, ['BAR', 'KITCHEN', 'CHECKER'])) {
            continue;
        }

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

        $struk = $this->Kasir_model->generate_struk_full_by_setting($transaksi_cetak, $printer, $struk_data, $tampilan);

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
    }

    // ✅ Update is_printed
    $this->db->where('pr_transaksi_id', $transaksi_id);
    $this->db->where('(is_printed = 0 OR is_printed IS NULL)', null, false);
    $this->db->update('pr_detail_transaksi', ['is_printed' => 1]);
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
    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);
    $printer = $this->Printer_model->get_by_lokasi($lokasi_printer);

    if (!$transaksi || !$printer) {
        show_error('Data transaksi atau printer tidak ditemukan.');
        return;
    }

    $struk_data = $this->Setting_model->get_data_struk();
    $tampilan = $this->Setting_model->get_tampilan_struk($printer['id']);
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

            // foreach ($extra_grouped as $ex) {
            //     if (isset($p['detail_unit_id']) && $ex['detail_unit_id'] == $p['detail_unit_id']) {
            //         $p['extra'][] = [
            //             'id' => $ex['pr_produk_extra_id'],
            //             'nama_extra' => $ex['nama_extra'] ?? 'Extra',
            //             'jumlah' => $ex['jumlah_extra'],
            //             'harga' => $ex['harga'],
            //             'satuan' => $ex['satuan'] ?? '',
                    
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

      // ✅ Group dulu semua item
//    $order_data['items'] = $this->Kasir_model->group_items($order_data['items']);

    $kasir_id = $this->session->userdata('pegawai_id');
    if (!$kasir_id) {
        echo json_encode(['status' => 'error', 'message' => 'Session kasir tidak ditemukan']);
        return;
    }

    $is_edit = isset($order_data['transaksi_id']) && intval($order_data['transaksi_id']) > 0;
    $transaksi_id = $is_edit ? intval($order_data['transaksi_id']) : null;

    $customer_id = ($order_data['customer_type'] !== 'walkin' && !empty($order_data['customer_id']))
        ? intval($order_data['customer_id']) : null;

    $total_penjualan = 0;
    foreach ($order_data['items'] as $item) {
        $subtotal_produk = $item['harga'] * $item['jumlah'];
        $subtotal_extra = 0;

        if (!empty($item['extra'])) {
            foreach ($item['extra'] as $extra) {
                $subtotal_extra += $extra['harga'] * $item['jumlah'];
            }
        }

        $total_penjualan += $subtotal_produk + $subtotal_extra;
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
            'customer' => $order_data['customer'],
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
            'customer' => $order_data['customer'],
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

    if ($this->db->trans_status()) {
        // ✅ Setelah simpan transaksi, langsung cetak otomatis!
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
    $this->db->select('id, no_transaksi, customer, total_penjualan');
    $this->db->from('pr_transaksi');
    $this->db->where('status_pembayaran !=', 'LUNAS'); // ✅ ganti jadi cek status_pembayaran
    $this->db->order_by('waktu_order', 'DESC');
    echo json_encode($this->db->get()->result_array());
}

public function get_detail_transaksi()
{
    $transaksi_id = $this->input->post('transaksi_id');
    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);

    if ($transaksi) {
        // Tambahkan extra ke setiap item
        foreach ($transaksi['items'] as &$item) {
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
                ->get()
                ->result_array();

            foreach ($extras as &$ex) {
                $ex['nama'] = $ex['nama_extra'] ?? 'Extra';
                $ex['status'] = $ex['status'] ?? NULL;
            }

            $item['extra'] = $extras;
        }

        // 🔥 Ambil semua pembayaran
        $pembayaran = $this->db
            ->select('pb.id, pb.metode_id, pb.jumlah, mp.metode_pembayaran as metode_nama')
            ->from('pr_pembayaran pb')
            ->join('pr_metode_pembayaran mp', 'mp.id = pb.metode_id', 'left')
            ->where('pb.transaksi_id', $transaksi_id)
            ->get()
            ->result_array();

        // 🔥 Tambahkan pembayaran ke data transaksi, tanpa menghilangkan field lama
        $transaksi['pembayaran'] = $pembayaran;

        // Langsung kirim transaksi lengkap
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



public function update_order() {
    $transaksi_id = $this->input->post('transaksi_id');
    $updated_items = json_decode($this->input->post('items'), true);

    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);

    if (!$transaksi || !empty($transaksi['waktu_bayar'])) {
        echo json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan atau sudah dibayar.']);
        return;
    }

    $existing_items = $this->Kasir_model->get_detail_transaksi($transaksi_id);
    $this->load->model('Void_model');

    $this->db->trans_begin();

    // Loop untuk update atau void
    foreach ($existing_items as $item) {
        $found = false;
        foreach ($updated_items as $new) {
            if ($item['pr_produk_id'] == $new['pr_produk_id']) {
                $found = true;
                if ($new['jumlah'] < $item['jumlah']) {
                    $this->Void_model->log_void($item, $item['jumlah'] - $new['jumlah'], 'Jumlah dikurangi');
                    $this->db->where('id', $item['id'])->update('pr_detail_transaksi', [
                        'jumlah' => $new['jumlah'],
                        'catatan' => $new['catatan']
                    ]);
                } else {
                    $this->db->where('id', $item['id'])->update('pr_detail_transaksi', [
                        'jumlah' => $new['jumlah'],
                        'catatan' => $new['catatan']
                    ]);
                }

                // Hapus dan simpan ulang extra
                $this->db->delete('pr_detail_extra', ['detail_transaksi_id' => $item['id']]);
                if (!empty($new['extra'])) {
                    $this->Kasir_model->simpan_detail_extra($item['id'], $new['extra']);
                    //$this->Kasir_model->simpan_detail_extra($detail_id, $item['extra'], $item['jumlah']);

                }
                break;
            }
        }

        // Jika item dihapus
        if (!$found) {
            $this->Void_model->log_void($item, $item['jumlah'], 'Item dihapus');
            $this->db->where('id', $item['id'])->update('pr_detail_transaksi', ['status' => 'batal']);
        }
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === false) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui pesanan.']);
    } else {
        $this->Kasir_model->update_total_transaksi($transaksi_id); // 🔥 Tambahkan ini
        $this->cetak_pesanan_baru_internal($transaksi_id);
        echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil diperbarui.']);
    }

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

    if (isset($voucher['sisa_voucher']) && $voucher['sisa_voucher'] <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ Voucher sudah habis digunakan'
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

    // 🔥 Step 1: Simpan pembayaran dulu
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
                $poin_data[] = [
                    'customer_id' => $transaksi->customer_id,
                    'transaksi_id' => $transaksi_id,
                    'jumlah_poin' => $p->nilai_point,
                    'jenis' => 'per_pembelian',
                    'sumber' => null,
                    'tanggal_kedaluwarsa' => date('Y-m-d', strtotime("+{$p->kedaluwarsa_hari} days")),
                    'status' => 'aktif',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                break;
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
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->update('pr_voucher', ['sisa_voucher' => $sisa_voucher], ['id' => $voucher->id]);
    }

    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);
   // ❗❗❗ CETAK STRUK KASIR OTOMATIS kalau sudah LUNAS
    // 🔥 Cek lagi, jika sudah LUNAS -> cetak struk kasir
    if ($transaksi && $transaksi['status_pembayaran'] == 'LUNAS') {
        $this->cetak_pesanan_dibayar_internal($transaksi_id);
    }
    echo json_encode(['status' => 'success', 'message' => 'Pembayaran berhasil disimpan.']);
}


public function void_item()
{
    $detail_id = $this->input->post('detail_id');
    $alasan = trim($this->input->post('alasan'));
    $user_id = $this->session->userdata('pegawai_id');

    if (empty($alasan)) {
        echo json_encode(['status' => 'error', 'message' => 'Alasan tidak boleh kosong!']);
        return;
    }

    $produk = $this->db->get_where('pr_detail_transaksi', ['id' => $detail_id])->row();
    if (!$produk) {
        echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan']);
        return;
    }

    $transaksi = $this->db->get_where('pr_transaksi', ['id' => $produk->pr_transaksi_id])->row();
    $master_produk = $this->db->get_where('pr_produk', ['id' => $produk->pr_produk_id])->row();

    // 1. Ambil semua extra yang status NULL (belum batal)
    $this->db->select('ex.id, ex.pr_produk_extra_id, ex.jumlah, ex.harga, pe.nama_extra');
    $this->db->from('pr_detail_extra ex');
    $this->db->join('pr_produk_extra pe', 'ex.pr_produk_extra_id = pe.id', 'left');
    $this->db->where('ex.detail_transaksi_id', $detail_id);
    $this->db->where('ex.status IS NULL', null, false); // 👉 ambil yang status NULL saja
    $extra_items = $this->db->get()->result();

    // 2. Update status produk utama dan semua extra menjadi BATAL
    $this->db->where('id', $detail_id)->update('pr_detail_transaksi', ['status' => 'BATAL']);
    $this->db->where('detail_transaksi_id', $detail_id)->where('status IS NULL', null, false)->update('pr_detail_extra', ['status' => 'BATAL']);

    // 3. Hitung pengurang total_penjualan
    $pengurang = (int)$produk->harga * (int)$produk->jumlah;
    foreach ($extra_items as $ex) {
        $pengurang += ((int)$ex->harga * (int)$ex->jumlah);
    }

    $this->db->where('id', $produk->pr_transaksi_id);
    $this->db->set('total_penjualan', 'total_penjualan - ' . $pengurang, false);
    $this->db->set('sisa_pembayaran', 'GREATEST(0, sisa_pembayaran - ' . $pengurang . ')', false); // 🔥 Tambahkan ini
    $this->db->update('pr_transaksi');
    
    // 4. Insert void untuk produk utama
    $this->db->insert('pr_void', [
        'pr_transaksi_id' => $produk->pr_transaksi_id,
        'no_transaksi' => $transaksi->no_transaksi,
        'detail_transaksi_id' => $produk->id,
        'nama_produk' => $master_produk ? $master_produk->nama_produk : $produk->nama_produk,
        'detail_extra_id' => NULL,
        'pr_produk_id' => $produk->pr_produk_id,
        'produk_extra_id' => NULL,
        'nama_extra' => NULL,
        'jumlah' => $produk->jumlah,
        'harga' => $produk->harga,
        'catatan' => $produk->catatan,
        'alasan' => $alasan,
        'void_by' => $user_id,
        'waktu' => date('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);

    // 5. Insert void untuk semua extra aktif (yang awalnya status NULL)
    foreach ($extra_items as $ex) {
        $this->db->insert('pr_void', [
            'pr_transaksi_id' => $produk->pr_transaksi_id,
            'no_transaksi' => $transaksi->no_transaksi,
            'detail_transaksi_id' => $produk->id,
            'nama_produk' => $master_produk ? $master_produk->nama_produk : $produk->nama_produk,
            'detail_extra_id' => $ex->id,
            'pr_produk_id' => $produk->pr_produk_id,
            'produk_extra_id' => $ex->pr_produk_extra_id,
            'nama_extra' => $ex->nama_extra,
            'jumlah' => $ex->jumlah,
            'harga' => $ex->harga,
            'catatan' => 'Extra dari ' . ($master_produk ? $master_produk->nama_produk : $produk->nama_produk),
            'alasan' => $alasan,
            'void_by' => $user_id,
            'waktu' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    echo json_encode(['status' => 'success', 'message' => 'Produk berhasil di-void!']);
}


public function void_extra_item()
{
    $extra_id = $this->input->post('extra_id'); // ID dari pr_detail_extra.id
    $alasan = trim($this->input->post('alasan'));
    $user_id = $this->session->userdata('pegawai_id');

    // 1. Ambil data extra + produk utama
    $this->db->select('
        ex.id AS detail_extra_id, 
        ex.pr_produk_extra_id, 
        ex.detail_transaksi_id, 
        ex.jumlah, 
        ex.harga,
        d.pr_transaksi_id, 
        d.pr_produk_id, 
        prod.nama_produk AS nama_produk,
        pextra.nama_extra AS nama_extra
    ');
    $this->db->from('pr_detail_extra ex');
    $this->db->join('pr_detail_transaksi d', 'ex.detail_transaksi_id = d.id', 'left'); // join ke transaksi
    $this->db->join('pr_produk prod', 'd.pr_produk_id = prod.id', 'left'); // join ke produk utama
    $this->db->join('pr_produk_extra pextra', 'ex.pr_produk_extra_id = pextra.id', 'left'); // join ke produk extra
    $this->db->where('ex.id', $extra_id);
    $extra = $this->db->get()->row();

    if (!$extra) {
        echo json_encode(['status' => 'error', 'message' => 'Extra tidak ditemukan']);
        return;
    }

    // 2. Ambil data transaksi
    $transaksi = $this->db->get_where('pr_transaksi', ['id' => $extra->pr_transaksi_id])->row();

    // 3. Update status pr_detail_extra menjadi BATAL
    $this->db->where('id', $extra->detail_extra_id);
    $this->db->update('pr_detail_extra', ['status' => 'BATAL']);

    // 4. Update pengurangan total_penjualan
    $pengurang = (int)$extra->harga * (int)$extra->jumlah;
    $this->db->where('id', $extra->pr_transaksi_id);
    $this->db->set('total_penjualan', 'total_penjualan - ' . $pengurang, false);
    $this->db->set('sisa_pembayaran', 'GREATEST(0, sisa_pembayaran - ' . $pengurang . ')', false); // 🔥 Tambahkan ini
    $this->db->update('pr_transaksi');

    // 5. Insert ke pr_void
    $this->db->insert('pr_void', [
        'pr_transaksi_id'     => $extra->pr_transaksi_id,
        'no_transaksi'        => $transaksi->no_transaksi,
        'detail_transaksi_id' => $extra->detail_transaksi_id, // ID Produk Utama
        'pr_produk_id'        => $extra->pr_produk_id,        // ID Produk Utama
        'nama_produk'         => $extra->nama_produk,         // Nama Produk Utama
        'detail_extra_id'     => $extra->detail_extra_id,     // ID detail_extra (ID Extra)
        'produk_extra_id'     => $extra->pr_produk_extra_id,  // ID Produk Extra
        'nama_extra'          => $extra->nama_extra,          // Nama Produk Extra
        'jumlah'              => $extra->jumlah,
        'harga'               => $extra->harga,
        'catatan'             => 'Extra',
        'alasan'              => $alasan,
        'void_by'             => $user_id,
        'waktu'               => date('Y-m-d H:i:s'),
        'created_at'          => date('Y-m-d H:i:s'),
        'updated_at'          => date('Y-m-d H:i:s'),
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Extra berhasil di-void!']);
}

// VOID BARU ///

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


public function void_semua()
{
    $transaksi_id = $this->input->post('transaksi_id');
    $user_id = $this->session->userdata('pegawai_id');

    if (!$transaksi_id) {
        echo json_encode(['status' => 'error', 'message' => 'ID transaksi tidak valid']);
        return;
    }

    $transaksi = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row();
    if (!$transaksi) {
        echo json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan']);
        return;
    }

    // Ambil semua produk aktif (status NULL) + JOIN nama_produk
    $items = $this->db
        ->select('d.*, p.nama_produk')
        ->from('pr_detail_transaksi d')
        ->join('pr_produk p', 'p.id = d.pr_produk_id', 'left')
        ->where('d.pr_transaksi_id', $transaksi_id)
        ->where('d.status IS NULL', null, false)
        ->get()
        ->result();

    foreach ($items as $item) {
        // Void produk utama
        $this->db->where('id', $item->id)->update('pr_detail_transaksi', ['status' => 'BATAL']);

        $this->db->insert('pr_void', [
            'pr_transaksi_id' => $transaksi_id,
            'no_transaksi' => $transaksi->no_transaksi,
            'detail_transaksi_id' => $item->id,
            'nama_produk' => $item->nama_produk ?? 'Produk Tidak Dikenal',
            'pr_produk_id' => $item->pr_produk_id,
            'jumlah' => $item->jumlah,
            'harga' => $item->harga,
            'alasan' => 'Dibatalkan Semua',
            'void_by' => $user_id,
            'waktu' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Ambil semua extra aktif (status NULL) + JOIN nama_extra
        $extras = $this->db
            ->select('ex.*, pe.nama_extra')
            ->from('pr_detail_extra ex')
            ->join('pr_produk_extra pe', 'pe.id = ex.pr_produk_extra_id', 'left')
            ->where('ex.detail_transaksi_id', $item->id)
            ->where('ex.status IS NULL', null, false)
            ->get()
            ->result();

        foreach ($extras as $extra) {
            $this->db->where('id', $extra->id)->update('pr_detail_extra', ['status' => 'BATAL']);
            $this->db->insert('pr_void', [
                'pr_transaksi_id' => $transaksi_id,
                'no_transaksi' => $transaksi->no_transaksi,
                'detail_transaksi_id' => $item->id,
                'nama_produk' => $item->nama_produk ?? 'Produk Tidak Dikenal',
                'pr_produk_id' => $item->pr_produk_id,
                'detail_extra_id' => $extra->id,
                'produk_extra_id' => $extra->pr_produk_extra_id,
                'nama_extra' => $extra->nama_extra ?? 'Extra Tidak Dikenal',
                'jumlah' => $extra->jumlah,
                'harga' => $extra->harga,
                'alasan' => 'Dibatalkan Semua',
                'void_by' => $user_id,
                'waktu' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    // Update transaksi utama: total_penjualan 0, status_pembayaran BATAL
    $this->db->where('id', $transaksi_id)->update('pr_transaksi', [
        'total_penjualan' => 0,
        'status_pembayaran' => 'BATAL'
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Semua item berhasil dibatalkan.']);
}


public function pesanan_terbayar()
{

    $data['title'] = "Pesanan Terbayar";
    $this->load->view("templates/header", $data);
    $this->load->view('kasir/pesanan_terbayar');
    $this->load->view("templates/footer");

}

public function get_pesanan_terbayar()
{
    $tanggal_awal = $this->input->get('tanggal_awal');
    $tanggal_akhir = $this->input->get('tanggal_akhir');
    $search = $this->input->get('search');

    $this->db->select('id, no_transaksi, customer, tanggal, total_pembayaran, status_pembayaran');
    $this->db->from('pr_transaksi');
    $this->db->where('status_pembayaran', 'LUNAS');

    if ($tanggal_awal && $tanggal_akhir) {
        $this->db->where('tanggal >=', $tanggal_awal);
        $this->db->where('tanggal <=', $tanggal_akhir);
    }

    if ($search) {
        $this->db->like('no_transaksi', $search);
        $this->db->or_like('customer', $search);
    }

    $this->db->order_by('tanggal', 'DESC');

    $query = $this->db->get();
    echo json_encode($query->result());
}


}