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


    // Fungsi untuk menyimpan transaksi

public function simpan_transaksi() {
    $order_data = json_decode($this->input->post('order_data'), true);

    if (!isset($order_data['items']) || empty($order_data['items'])) {
        echo json_encode(['status' => 'error', 'message' => 'Tidak ada item dalam pesanan']);
        return;
    }

    $kasir_id = $this->session->userdata('pegawai_id');
    if (!$kasir_id) {
        echo json_encode(['status' => 'error', 'message' => 'Session kasir tidak ditemukan! Silakan login ulang.']);
        return;
    }

    $customer_id = null;
    if (!empty($order_data['customer_id']) && $order_data['customer_type'] !== 'walkin') {
        $customer_id = intval($order_data['customer_id']);
    }

    $total_penjualan = 0;
    foreach ($order_data['items'] as $item) {
        $total_penjualan += intval($item['harga']) * intval($item['jumlah']);
    }

    $kode_voucher = isset($order_data['kode_voucher']) ? $order_data['kode_voucher'] : null;
    $diskon = isset($order_data['diskon']) ? intval($order_data['diskon']) : 0;
    $total_pembayaran = $total_penjualan - $diskon;

    $data_transaksi = [
        'tanggal' => date('Y-m-d'),
        'no_transaksi' => $this->generate_no_transaksi(),
        'waktu_order' => date('Y-m-d H:i:s'),
        'jenis_order_id' => $order_data['jenis_order_id'],
        'customer_id' => $customer_id,
        'customer' => $order_data['customer'],
        'nomor_meja' => isset($order_data['nomor_meja']) ? $order_data['nomor_meja'] : NULL,
        'total_penjualan' => $total_penjualan,
        'kode_voucher' => $kode_voucher,
        'diskon' => $diskon,
        'total_pembayaran' => $total_pembayaran,
        'kasir_order' => $kasir_id
    ];

    // ✅ JALANKAN DAN TANGANI HASILNYA
    $transaksi_id = $this->Kasir_model->simpan_transaksi($data_transaksi, $order_data['items']);

    if ($transaksi_id) {
        echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil disimpan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan transaksi']);
    }
}


    public function load_pending_orders() {
        $this->db->select('id, no_transaksi, customer, total_pembayaran');
        $this->db->from('pr_transaksi');
        $this->db->where('waktu_bayar IS NULL'); // Pesanan yang belum dibayar
        $this->db->order_by('waktu_order', 'DESC');
        echo json_encode($this->db->get()->result_array());
    }


public function cek_voucher() {
    $kode_voucher = $this->input->post('kode_voucher');
    $items = json_decode($this->input->post('items'), true); // Ambil daftar produk dalam transaksi
    $total_penjualan = intval($this->input->post('total'));

    // Cari voucher yang sesuai
    $this->db->where('kode_voucher', $kode_voucher);
    $this->db->where('status', 'aktif');
    $this->db->where('tanggal_mulai <=', date('Y-m-d'));
    $this->db->where('tanggal_berakhir >=', date('Y-m-d'));
    $voucher = $this->db->get('pr_voucher')->row_array();

    if (!$voucher) {
        echo json_encode(['status' => 'error', 'message' => 'Kode voucher tidak valid atau sudah kedaluwarsa!']);
        return;
    }

    $diskon = 0;

    // Jika voucher berlaku hanya untuk produk tertentu
    if (!empty($voucher['produk_id'])) {
        $subtotal_produk = 0;

        // Periksa apakah produk yang dimaksud ada dalam transaksi
        foreach ($items as $item) {
            if ($item['pr_produk_id'] == $voucher['produk_id']) {
                $subtotal_produk = $item['subtotal'];
                break;
            }
        }

        if ($subtotal_produk == 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Voucher ini hanya berlaku untuk produk tertentu yang tidak ada dalam pesanan.'
            ]);
            return;
        }

        // Hitung diskon berdasarkan subtotal produk tertentu
        if ($voucher['jenis'] == 'persentase') {
            $diskon = ($voucher['nilai'] / 100) * $subtotal_produk;
            if (!empty($voucher['max_diskon']) && $diskon > $voucher['max_diskon']) {
                $diskon = $voucher['max_diskon'];
            }
        } elseif ($voucher['jenis'] == 'nominal') {
            $diskon = min($voucher['nilai'], $subtotal_produk); // Pastikan diskon tidak lebih besar dari harga produk
        }
    } else {
        // Jika voucher berlaku untuk semua produk dalam transaksi
        if ($total_penjualan < intval($voucher['min_pembelian'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Voucher hanya berlaku untuk pembelian minimal Rp ' . number_format($voucher['min_pembelian'], 0, ',', '.')
            ]);
            return;
        }

        if ($voucher['jenis'] == 'persentase') {
            $diskon = ($voucher['nilai'] / 100) * $total_penjualan;
            if (!empty($voucher['max_diskon']) && $diskon > $voucher['max_diskon']) {
                $diskon = $voucher['max_diskon'];
            }
        } elseif ($voucher['jenis'] == 'nominal') {
            $diskon = $voucher['nilai'];
        }
    }

    // Hitung total bayar setelah diskon
    $total_bayar = max(0, $total_penjualan - $diskon);

    echo json_encode([
        'status' => 'success',
        'diskon' => $diskon,
        'total_bayar' => $total_bayar
    ]);
}

// public function cetak_struk($transaksi_id = NULL, $cetakType = NULL) {
//     header('Content-Type: application/json');

//     if (!$transaksi_id || !$cetakType) {
//         echo json_encode(["status" => "error", "message" => "Parameter tidak lengkap"]);
//         return;
//     }

//     $printer = $this->Printer_model->get_printer_by_divisi($cetakType);
//     if (!$printer) {
//         echo json_encode(["status" => "error", "message" => "Printer belum disetel untuk divisi ini"]);
//         return;
//     }

//     $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);
//     if (!$transaksi) {
//         echo json_encode(["status" => "error", "message" => "Transaksi tidak ditemukan"]);
//         return;
//     }

//     $output = $this->format_struk($transaksi, $cetakType);

//     $print_result = $this->send_to_printer($printer['lokasi_printer'], $output);

//     if ($print_result) {
//         echo json_encode(["status" => "success", "printer" => $printer['lokasi_printer']]);
//     } else {
//         echo json_encode(["status" => "error", "message" => "Gagal mengirim ke printer"]);
//     }
// }
// private function format_struk($transaksi, $divisi) {
//     $output = "==== CETAK ". strtoupper($divisi) ." ====\n";
//     $output .= "No Transaksi: {$transaksi['no_transaksi']}\n";
//     $output .= "Tanggal: " . date('d-m-Y H:i', strtotime($transaksi['waktu_order'])) . "\n";
//     $output .= "--------------------------------\n";
//     foreach ($transaksi['items'] as $item) {
//         $output .= "{$item['jumlah']}x {$item['nama_produk']}";
//         if (!empty($item['catatan'])) $output .= " ({$item['catatan']})";
//         $output .= "\n";
//     }
//     $output .= "--------------------------------\n";
//     if ($divisi == 'kasir') {
//         $output .= "Total: Rp " . number_format($transaksi['total_pembayaran'], 0, ',', '.') . "\n";
//     }
//     $output .= "============================\n\n";
//     return $output;
// }

// private function send_to_printer($lokasi_printer, $text) {
//     $host = '127.0.0.1';
//     $port = 8989;

//     $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//     if (!$socket) return false;

//     $result = socket_connect($socket, $host, $port);
//     if (!$result) return false;

//     socket_write($socket, $text, strlen($text));
//     socket_close($socket);
//     return true;
// }


// public function cetak_pending_divisi() {
//     $transaksi_id = $this->input->post('transaksi_id');
//     $divisi_id = $this->input->post('divisi');

//     $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);
//     $printer = $this->Printer_model->get_printer_by_divisi_id($divisi_id);

//     if (!$printer) {
//         $this->session->set_flashdata('error', 'Printer tidak ditemukan untuk divisi ini.');
//         redirect('kasir');
//         return;
//     }

//     $text = $this->Kasir_model->generate_struk_divisi($transaksi, $divisi_id);

//     // Kirim ke Python service
//     $res = $this->send_to_python_service($printer['lokasi_printer'], $text);

//     if ($res === true) {
//         $this->session->set_flashdata('success', 'Berhasil mencetak ke printer ' . $printer['lokasi_printer']);
//     } else {
//         $this->session->set_flashdata('error', 'Gagal mencetak. ' . $res);
//     }

//     redirect('kasir');
// }

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
//    $text = $this->Kasir_model->generate_struk_by_printer($transaksi, $lokasi_printer);
    $text = $this->input->post('struk_text'); // langsung ambil string preview


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

    $isSudahBayar = !empty($transaksi['waktu_bayar']);

    // Periksa apakah ada item yang belum dicetak
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

    // Ambil semua printer
    $printers = $this->Printer_model->get_all();
    $hasil = [];

    foreach ($printers as $printer) {
        $lokasi = strtoupper($printer['lokasi_printer']);

        // Jika belum dibayar, hanya cetak ke BAR, KITCHEN, CHECKER
        if (!$isSudahBayar && !in_array($lokasi, ['BAR', 'KITCHEN', 'CHECKER'])) {
            continue;
        }

        // 🔍 Buat struk untuk printer ini hanya dari produk is_printed IS NULL
        $struk = $this->Kasir_model->generate_struk_by_printer($transaksi, $lokasi, true);

        // 🚫 Jika struk kosong (tidak ada produk untuk divisi ini), lewati
        if (trim($struk) === '' || strlen(trim($struk)) < 5) {
            $hasil[] = "ℹ️ Tidak ada produk untuk $lokasi, dilewati";
            continue;
        }

        $res = $this->send_to_python_service($lokasi, $struk);

        if ($res === true) {
            $hasil[] = "✅ Dicetak ke $lokasi";
        } else {
            $hasil[] = "❌ Gagal cetak $lokasi: $res";
        }
    }

    // Tandai produk yang belum dicetak sebagai sudah dicetak
    $this->db->where('pr_transaksi_id', $transaksi_id);
    $this->db->where('is_printed IS NULL');
    $this->db->update('pr_detail_transaksi', ['is_printed' => 1]);

    echo json_encode([
        'status' => 'success',
        'message' => "🖨️ Hasil cetak pesanan:\n" . implode("\n", $hasil)
    ]);
}


public function preview_struk_printer() {
    $transaksi_id = $this->input->post('transaksi_id');
    $lokasi_printer = $this->input->post('lokasi_printer');

    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);
    $printer = $this->Printer_model->get_by_lokasi($lokasi_printer);

    if (!$transaksi || !$printer) {
        show_error('Data transaksi atau printer tidak ditemukan.');
        return;
    }

    $data['transaksi'] = $transaksi;
    $data['printer'] = $printer;
    $data['preview_struk'] = $this->Kasir_model->generate_struk_by_printer($transaksi, $lokasi_printer);

    $this->load->view('kasir/preview_struk_printer', $data);
}


}