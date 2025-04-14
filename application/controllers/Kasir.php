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

    $is_edit = isset($order_data['transaksi_id']) && !empty($order_data['transaksi_id']);
    $transaksi_id = $is_edit ? intval($order_data['transaksi_id']) : null;

    $customer_id = ($order_data['customer_type'] !== 'walkin' && !empty($order_data['customer_id']))
        ? intval($order_data['customer_id']) : null;

    $kode_voucher = $order_data['kode_voucher'] ?? null;
    $diskon = intval($order_data['diskon'] ?? 0);

    $total_penjualan = 0;
    foreach ($order_data['items'] as $item) {
        $subtotal_produk = $item['harga'] * $item['jumlah'];
        $subtotal_extra = 0;

        if (!empty($item['extra'])) {
            foreach ($item['extra'] as $extra) {
                $subtotal_extra += $extra['harga'] * $extra['jumlah'];
            }
        }

        $total_penjualan += $subtotal_produk + $subtotal_extra;
    }


    $total_pembayaran = $total_penjualan - $diskon;

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
            'diskon' => $diskon,
            'kode_voucher' => $kode_voucher,
            'total_pembayaran' => $total_pembayaran,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $transaksi_id)->update('pr_transaksi', $update_data);
        $this->Kasir_model->update_detail_transaksi($transaksi_id, $order_data['items'], $transaksi);

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
            'kode_voucher' => $kode_voucher,
            'diskon' => $diskon,
            'total_pembayaran' => $total_pembayaran,
            'kasir_order' => $kasir_id,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $transaksi_id = $this->Kasir_model->simpan_transaksi($data_transaksi, $order_data['items']);
    }

    $this->db->trans_complete();

    if ($this->db->trans_status()) {
        echo json_encode([
            'status' => 'success',
            'message' => $is_edit ? 'Pesanan berhasil diperbarui' : 'Pesanan berhasil disimpan'
        ]);
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

public function get_detail_transaksi() {
    $transaksi_id = $this->input->post('transaksi_id');
    $transaksi = $this->Kasir_model->get_transaksi_by_id($transaksi_id);

    if ($transaksi) {
        foreach ($transaksi['items'] as &$item) {
            $extras = $this->db->get_where('pr_detail_extra', [
                'detail_transaksi_id' => $item['id']
            ])->result_array();

            foreach ($extras as &$ex) {
                $extra_info = $this->db->get_where('pr_produk_extra', ['id' => $ex['pr_produk_extra_id']])->row_array();
                $ex['nama_extra'] = $extra_info['nama_extra'] ?? $ex['sku'];
            }

            $item['extra'] = $extras;
        }
        echo json_encode($transaksi);
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
                // Jika jumlah berubah (berkurang)
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

    echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil diperbarui.']);
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
public function get_extra_list() {
    $this->db->select('id, sku, nama_extra, satuan, harga, hpp');
    $this->db->from('pr_produk_extra');
    $this->db->where('status', 1);
    echo json_encode($this->db->get()->result_array());
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

}