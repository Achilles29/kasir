<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Transaksi_model'); // ✅ Tambahkan model agar bisa digunakan di semua fungsi
        $this->load->model('Printer_model'); // Load model printer
        header("Content-Type: application/json");
     }

    /// ✅ **Login Pegawai**
    public function login() {
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->username) || !isset($data->password)) {
            $this->session->set_userdata("kasir_id", $user->id); // ✅ Simpan ID pegawai di session

                echo json_encode([
                    "status" => "success",
                    "message" => "Login berhasil",
                    "user" => [
                        "id" => $user->id,
                        "nama" => $user->nama,
                        "username" => $user->username
                    ]
                ]);

            return;
        }

        $username = $data->username;
        $password = $data->password;

        $query = $this->db->get_where("abs_pegawai", ["username" => $username, "is_kasir" => 1]);

        if ($query->num_rows() > 0) {
            $user = $query->row();

            if (password_verify($password, $user->password)) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Login berhasil",
                    "user" => [
                        "id" => $user->id,
                        "nama" => $user->nama,
                        "username" => $user->username
                    ]
                ]);
            } else {
                echo json_encode(["status" => "error", "message" => "Username atau password salah"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Username atau password salah"]);
        }
    }


public function get_produk() {
    $kategori_id = $this->input->get("kategori_id");
    $keyword = $this->input->get("keyword");

    $base_url = "https://dashboard.namuacoffee.com/uploads/produk/";

    $where = "WHERE 1=1";

    if ($kategori_id) {
        $where .= " AND kategori_id = $kategori_id";
    }

    if ($keyword) {
        $where .= " AND nama_produk LIKE '%$keyword%'";
    }

    $query = $this->db->query("
        SELECT id, nama_produk, harga_jual, 
               CONCAT('$base_url', foto) AS foto 
        FROM pr_produk 
        $where
    ");

    echo json_encode($query->result_array());
}



public function get_kategori() {
    $query = $this->db->query("
        SELECT id, nama_kategori 
        FROM pr_kategori 
        WHERE status = 1
        ORDER BY urutan ASC
    ");

    // Pastikan id dikembalikan sebagai integer
    $kategori = array_map(function ($item) {
        return [
            "id" => (int) $item["id"],  // Konversi ke integer
            "nama_kategori" => $item["nama_kategori"]
        ];
    }, $query->result_array());

    echo json_encode($kategori);
}


public function get_transaksi() {
    $tanggal = $this->input->get("tanggal");

    if (!$tanggal) {
        echo json_encode(["status" => "error", "message" => "Tanggal harus diisi"]);
        return;
    }

    $query = $this->db->query("
        SELECT id, no_transaksi, total_pembayaran as total, kasir_order as kasir 
        FROM pr_transaksi 
        WHERE tanggal = '$tanggal'
        ORDER BY id DESC
    ");

    echo json_encode($query->result_array());
}
public function get_detail_transaksi() {
    $transaksi_id = $this->input->get("transaksi_id");

    if (!$transaksi_id) {
        echo json_encode(["status" => "error", "message" => "ID transaksi harus diisi"]);
        return;
    }

    $query = $this->db->query("
        SELECT 
            dt.*, -- semua kolom dari detail transaksi
            p.nama_produk, p.kategori_id,
            k.nama_kategori, k.pr_divisi_id,
            d.nama_divisi
        FROM pr_detail_transaksi dt
        LEFT JOIN pr_produk p ON dt.pr_produk_id = p.id
        LEFT JOIN pr_kategori k ON p.kategori_id = k.id
        LEFT JOIN pr_divisi d ON k.pr_divisi_id = d.id
        WHERE dt.pr_transaksi_id = ?
    ", [$transaksi_id]);

    echo json_encode($query->result_array());
}

  
public function get_jenis_order() {
    $query = $this->db->query("SELECT id, jenis_order FROM pr_jenis_order");

    if ($query->num_rows() > 0) {
        echo json_encode($query->result_array());
    } else {
        echo json_encode([]);
    }
}
public function get_customer() {
    $keyword = $this->input->get('keyword');

    $query = $this->db->query("
        SELECT id, nama, telepon 
        FROM pr_customer 
        WHERE nama LIKE '%" . $this->db->escape_like_str($keyword) . "%'
        LIMIT 10
    ");

    if ($query->num_rows() > 0) {
        echo json_encode($query->result_array());
    } else {
        echo json_encode([]);
    }
}

// public function getExtra() {
//     $this->db->select('*');
//     $this->db->from('pr_extra');
//     $query = $this->db->get();
//     echo json_encode($query->result_array());
// }
// public function get_extra() {
//     $this->db->select('*');
//     $this->db->from('pr_extra');
//     $query = $this->db->get();
//     echo json_encode($query->result_array());
// }

public function getDivisi() {
    $this->db->select('id, nama_divisi, urutan_tampilan');
    $this->db->from('pr_divisi');
    $this->db->order_by('urutan_tampilan', 'ASC');
    $query = $this->db->get();
    $data = array_map(function($row) {
        $row['id'] = (int) $row['id']; // Konversi ke integer
        return $row;
    }, $query->result_array());

    echo json_encode($data);
}

public function getProduk() {
    $divisiId = $this->input->get('divisiId');
    $keyword = $this->input->get('keyword');

    $this->db->select('pr_produk.*, pr_kategori.pr_divisi_id');
    $this->db->from('pr_produk');
    $this->db->join('pr_kategori', 'pr_produk.kategori_id = pr_kategori.id', 'left');

    if ($divisiId && $divisiId != 0) {
        $this->db->where('pr_kategori.pr_divisi_id', $divisiId);
    }

    if (!empty($keyword)) {
        $this->db->like('pr_produk.nama_produk', $keyword);
    }

    $query = $this->db->get();
    $result = $query->result_array();

    // Pastikan harga dalam bentuk float
    foreach ($result as &$produk) {
        $produk['harga_jual'] = floatval($produk['harga_jual']);
    }

    echo json_encode($result);
}

public function get_produk_by_divisi() {
    $divisi_id = $this->input->get("divisi_id");

    $this->db->select('
        pr_produk.id,
        pr_produk.nama_produk,
        pr_produk.harga_jual,
        pr_produk.foto,
        pr_kategori.nama_kategori,
        pe_divisi.nama_divisi
    ');
    $this->db->from('pr_produk');
    $this->db->join('pr_kategori', 'pr_produk.kategori_id = pr_kategori.id', 'left');
    $this->db->join('pe_divisi', 'pr_kategori.pr_divisi_id = pe_divisi.id', 'left');

    if (!empty($divisi_id)) {
        $this->db->where('pe_divisi.id', $divisi_id);
    }

    $this->db->order_by('pr_produk.nama_produk', 'ASC');
    $query = $this->db->get();

    $produk = $query->result_array();

    // Format harga dan foto
    $base_url = "https://dashboard.namuacoffee.com/uploads/produk/";
    foreach ($produk as &$row) {
        $row['harga_jual'] = (float) $row['harga_jual'];
        $row['foto'] = $row['foto'] ? $base_url . $row['foto'] : null;
    }

    echo json_encode([
        "status" => "success",
        "data" => $produk
    ]);
}

public function get_divisi_list() {
    $this->db->select('id, nama_divisi, urutan_tampilan');
    $this->db->from('pe_divisi');
    $this->db->order_by('urutan_tampilan', 'ASC');
    $query = $this->db->get();

    $result = array_map(function($item) {
        return [
            "id" => (int)$item["id"],
            "nama_divisi" => $item["nama_divisi"],
            "urutan_tampilan" => (int)$item["urutan_tampilan"]
        ];
    }, $query->result_array());

    echo json_encode([
        "status" => "success",
        "data" => $result
    ]);
}


public function post_produk_by_divisi() {
    $input = json_decode(file_get_contents("php://input"), true);
    $divisi_id = $input['divisi_id'] ?? null;

    $this->db->select('
        pr_produk.id,
        pr_produk.nama_produk,
        pr_produk.harga_jual,
        pr_produk.foto,
        pr_kategori.nama_kategori,
        pe_divisi.nama_divisi
    ');
    $this->db->from('pr_produk');
    $this->db->join('pr_kategori', 'pr_produk.kategori_id = pr_kategori.id', 'left');
    $this->db->join('pe_divisi', 'pr_kategori.pr_divisi_id = pe_divisi.id', 'left');

    if (!empty($divisi_id)) {
        $this->db->where('pe_divisi.id', $divisi_id);
    }

    $this->db->order_by('pr_produk.nama_produk', 'ASC');
    $query = $this->db->get();

    $produk = $query->result_array();

    $base_url = "https://dashboard.namuacoffee.com/uploads/produk/";
    foreach ($produk as &$row) {
        $row['harga_jual'] = (float) $row['harga_jual'];
        $row['foto'] = $row['foto'] ? $base_url . $row['foto'] : null;
    }

    echo json_encode([
        "status" => "success",
        "data" => $produk
    ]);
}

/// ✅ **Simpan Order ke Database**
public function simpan_order() {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || empty($input['detail_transaksi'])) {
        $this->output->set_status_header(400);
        echo json_encode(["status" => "error", "message" => "Data tidak valid"]);
        return;
    }
    // $input["kasir_order"] = $kasir_id; // ✅ Gunakan ID pegawai dari session

    $transaksi_id = $this->Transaksi_model->simpan_transaksi($input);
        if (!empty($input['detail_transaksi'])) {
        $this->load->model('Transaksi_model'); // pastikan model diload
        foreach ($input['detail_transaksi'] as $i => $detail) {
            $detail_transaksi_id = $this->Transaksi_model->get_detail_id_by_index($transaksi_id, $i); // kamu buat fungsinya
            if (isset($detail['extra']) && is_array($detail['extra'])) {
                $this->Transaksi_model->simpan_detail_extra($detail_transaksi_id, $detail['extra']);
            }
        }
    }


    if (!$transaksi_id) {
        log_message('error', "Gagal insert pr_transaksi: " . json_encode($this->db->error()));
        $this->output->set_status_header(500);
        echo json_encode([
            "status" => "error", 
            "message" => "Gagal menyimpan transaksi",
            "db_error" => $this->db->error()
        ]);
        return;
    }
   echo json_encode(["status" => "success", "message" => "Transaksi berhasil disimpan", "transaksi_id" => $transaksi_id]);
}


    /// ✅ **Generate Nomor Transaksi**
public function generate_no_transaksi() {
    $this->load->database();
    
    $prefix = "CS/63/" . date("ymd") . "/";

    $this->db->select("MAX(SUBSTRING(no_transaksi, -4)) as max_no");
    $this->db->like('no_transaksi', $prefix, 'after');
    $query = $this->db->get("pr_transaksi");
    $result = $query->row();

    $next_no = $result && $result->max_no ? intval($result->max_no) + 1 : 1;
    $no_transaksi = $prefix . str_pad($next_no, 4, "0", STR_PAD_LEFT);

    echo json_encode(["no_transaksi" => $no_transaksi]);
}
// public function get_pesanan_belum_dibayar() {
//     $this->db->select('id, jenis_order_id, no_transaksi, customer_id, customer, nomor_meja');
//     $this->db->from('pr_transaksi');
//     $this->db->where('waktu_bayar IS NULL');
//     $this->db->order_by('id', 'DESC');
//     $query = $this->db->get();
    
//     if ($query->num_rows() > 0) {
//         echo json_encode(["status" => "success", "data" => $query->result()]);
//     } else {
//         echo json_encode(["status" => "error", "message" => "Tidak ada pesanan belum dibayar"]);
//     }
// }
public function pesanan_belum_dibayar()
{
    $tanggal = $this->input->get('tanggal'); // Ambil tanggal dari parameter

    // Ambil semua kolom
    $this->db->select('*');
    $this->db->from('pr_transaksi');
    $this->db->where('waktu_bayar IS NULL'); // Hanya yang belum dibayar

    if (!empty($tanggal)) {
        $this->db->where('DATE(tanggal)', $tanggal);
    }

    $query = $this->db->get();

    echo json_encode($query->result_array());
}


// public function get_detail_transaksi_divisi() {
//     $transaksi_id = $this->input->get("transaksi_id");

//     if (!$transaksi_id) {
//         echo json_encode(["status" => "error", "message" => "ID transaksi harus diisi"]);
//         return;
//     }

//     $this->db->select("
//         dt.id as detail_id,
//         dt.pr_transaksi_id,
//         dt.pr_produk_id,
//         p.nama_produk,
//         dt.jumlah,
//         dt.harga,
//         dt.subtotal,
//         dt.catatan,
//         d.id as divisi_id,
//         d.nama_divisi
//     ");
//     $this->db->from("pr_detail_transaksi dt");
//     $this->db->join("pr_produk p", "dt.pr_produk_id = p.id", "left");
//     $this->db->join("pr_kategori k", "p.kategori_id = k.id", "left");
//     $this->db->join("pr_divisi d", "k.pr_divisi_id = d.id", "left");
//     $this->db->where("dt.pr_transaksi_id", $transaksi_id);
//     $this->db->order_by("d.id ASC, dt.id ASC");

//     $query = $this->db->get();
//     $result = $query->result_array();

//     echo json_encode([
//         "status" => "success",
//         "data" => $result
//     ]);
// }

public function get_divisi_by_detail_id() {
    $detail_id = $this->input->get('detail_id');

    if (!$detail_id) {
        echo json_encode([
            "status" => "error",
            "message" => "detail_id diperlukan"
        ]);
        return;
    }

    $query = $this->db->query("
        SELECT 
            dt.*, -- semua kolom dari pr_detail_transaksi
            p.nama_produk, p.kategori_id, 
            k.nama_kategori, k.pr_divisi_id,
            d.id AS divisi_id, d.nama_divisi
        FROM pr_detail_transaksi dt
        LEFT JOIN pr_produk p ON dt.pr_produk_id = p.id
        LEFT JOIN pr_kategori k ON p.kategori_id = k.id
        LEFT JOIN pr_divisi d ON k.pr_divisi_id = d.id
        WHERE dt.id = ?
        LIMIT 1
    ", [$detail_id]);

    $result = $query->row_array();

    if ($result) {
        echo json_encode([
            "status" => "success",
            "data" => $result
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Data tidak ditemukan"
        ]);
    }
}


// public function simpan_pembayaran()
// {
//     header("Content-Type: application/json");
//     $data = json_decode(file_get_contents("php://input"), true);

//     $transaksi_id = $data['pr_transaksi_id'] ?? null;
//     $metode_id = $data['metode_pembayaran_id'] ?? null;
//     $jumlah = $data['jumlah'] ?? 0;
//     $keterangan = $data['keterangan'] ?? null;

//     if (!$transaksi_id || !$metode_id || $jumlah <= 0) {
//         echo json_encode([
//             "status" => "error",
//             "message" => "Data tidak lengkap"
//         ]);
//         return;
//     }

//     // Simpan pembayaran ke tabel pr_pembayaran
//     $this->db->insert('pr_pembayaran', [
//         'pr_transaksi_id'      => $transaksi_id,
//         'metode_pembayaran_id' => $metode_id,
//         'jumlah'               => $jumlah,
//         'keterangan'           => $keterangan,
//         'waktu_pembayaran'     => date('Y-m-d H:i:s')
//     ]);

//     // Hitung total pembayaran yang sudah dibayar
//     $total_dibayar = $this->db
//         ->select_sum('jumlah')
//         ->get_where('pr_pembayaran', ['pr_transaksi_id' => $transaksi_id])
//         ->row()
//         ->jumlah;

//     // Ambil total tagihan
//     $transaksi = $this->db
//         ->get_where('pr_transaksi', ['id' => $transaksi_id])
//         ->row();

//     if (!$transaksi) {
//         echo json_encode(["status" => "error", "message" => "Transaksi tidak ditemukan."]);
//         return;
//     }

//     $sisa = $transaksi->total_pembayaran - $total_dibayar;
//     $status = 'BELUM_LUNAS';
//     if ($total_dibayar >= $transaksi->total_pembayaran) {
//         $status = 'LUNAS';
//     } elseif ($total_dibayar > 0 && $total_dibayar < $transaksi->total_pembayaran) {
//         $status = 'DP';
//     }

//     // Update transaksi
//     $this->db->where('id', $transaksi_id)->update('pr_transaksi', [
//         'sisa_pembayaran'    => $sisa,
//         'status_pembayaran'  => $status
//     ]);

//     echo json_encode([
//         "status" => "success",
//         "message" => "Pembayaran disimpan",
//         "total_dibayar" => (int) $total_dibayar,
//         "sisa" => (int) $sisa,
//         "status_pembayaran" => $status
//     ]);
// }

// public function simpan_pembayaran() {
//     $data = json_decode(file_get_contents("php://input"), true);

//     $transaksi_id   = $data['transaksi_id'] ?? null;
//     $metode_id      = $data['metode_id'] ?? null;
//     $jumlah         = $data['jumlah'] ?? 0;
//     $keterangan     = $data['keterangan'] ?? null;
//     $kasir_id       = $data['kasir_id'] ?? null;
//     $kode_voucher   = $data['kode_voucher'] ?? null;
//     $diskon         = $data['diskon'] ?? 0;

//     if (!$transaksi_id || !$metode_id || !$kasir_id || $jumlah <= 0) {
//         echo json_encode([
//             "status" => "error",
//             "message" => "Data tidak lengkap"
//         ]);
//         return;
//     }

//     $waktu_bayar = date("Y-m-d H:i:s");

//     // Simpan ke pr_pembayaran
//     $this->db->insert('pr_pembayaran', [
//         'transaksi_id'  => $transaksi_id,
//         'metode_id'     => $metode_id,
//         'jumlah'        => $jumlah,
//         'waktu_bayar'   => $waktu_bayar,
//         'keterangan'    => $keterangan,
//         'kasir_id'      => $kasir_id
//     ]);

//     // Hitung total dibayar
//     $total_dibayar = $this->db
//         ->select_sum('jumlah')
//         ->get_where('pr_pembayaran', ['transaksi_id' => $transaksi_id])
//         ->row()
//         ->jumlah;

//     // Ambil transaksi utama
//     $trx = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row();

//     if (!$trx) {
//         echo json_encode(["status" => "error", "message" => "Transaksi tidak ditemukan."]);
//         return;
//     }

//     $sisa = $trx->total_penjualan - $total_dibayar;

//     // Tentukan status pembayaran
//     $status = 'BELUM_LUNAS';
//     if ($total_dibayar >= $trx->total_penjualan) {
//         $status = 'LUNAS';
//     } elseif ($total_dibayar > 0) {
//         $status = 'DP';
//     }

//     // Update transaksi
//     $this->db->where('id', $transaksi_id)->update('pr_transaksi', [
//         'waktu_bayar'       => $waktu_bayar,
//         'kasir_bayar'       => $kasir_id,
//         'kode_voucher'      => $kode_voucher,
//         'diskon'            => $diskon,
//         'total_pembayaran'  => $total_dibayar,
//         'sisa_pembayaran'   => $sisa,
//         'status_pembayaran' => $status,
//         'updated_at'        => date('Y-m-d H:i:s')
//     ]);

//     // ✅ Update status pr_detail_transaksi jika LUNAS
//     if ($status == 'LUNAS') {
//         $this->db->where('pr_transaksi_id', $transaksi_id)->update('pr_detail_transaksi', [
//             'status' => 'berhasil'
//         ]);
//     }

//     echo json_encode([
//         "status" => "success",
//         "message" => "Pembayaran berhasil disimpan",
//         "status_pembayaran" => $status,
//         "total_dibayar" => (int) $total_dibayar,
//         "sisa" => (int) $sisa
//     ]);
// }


public function simpan_pembayaran() {
    $data = json_decode(file_get_contents("php://input"), true);
    $transaksi_id = $data['transaksi_id'] ?? null;
    $no_transaksi = $data['no_transaksi'] ?? null;
    $metode_id = $data['metode_id'] ?? null;
    $jumlah = $data['jumlah'] ?? 0;
    $keterangan = is_array($data['keterangan']) ? json_encode($data['keterangan']) : $data['keterangan'];
    $kasir_id = $data['kasir_id'] ?? null;
    $kode_voucher = $data['kode_voucher'] ?? null;
    $diskon = $data['diskon'] ?? 0;
    $waktu_bayar = $data['waktu_bayar'] ?? date("Y-m-d H:i:s");

    if (!$transaksi_id || !$metode_id || !$kasir_id || $jumlah <= 0) {
        echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
        return;
    }

    // Ambil data transaksi
    $trx = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row();

    //$trx = $this->db->get_where('pr_transaksi', ['no_transaksi' => $no_transaksi])->row();
    if (!$trx) {
        echo json_encode(["status" => "error", "message" => "Transaksi tidak ditemukan"]);
        return;
    }

    // Simpan ke pr_pembayaran
    $this->db->insert('pr_pembayaran', [
        'transaksi_id' => $trx->id,
        'metode_id' => $metode_id,
        'jumlah' => $jumlah,
        'waktu_bayar' => $waktu_bayar,
        'keterangan' => $keterangan,
        'kasir_id' => $kasir_id
    ]);

    // Hitung total yang sudah dibayar
    $total_dibayar = $this->db->select_sum('jumlah')->get_where('pr_pembayaran', ['transaksi_id' => $trx->id])->row()->jumlah;
    $total_tagihan = $trx->total_penjualan - $diskon;
    $sisa = $total_tagihan - $total_dibayar;

    // Status pembayaran
    $status = 'BELUM_LUNAS';
    if ($total_dibayar >= $total_tagihan) {
        $status = 'LUNAS';
    } elseif ($total_dibayar > 0) {
        $status = 'DP';
    }

    // Update pr_transaksi
    $this->db->where('id', $trx->id)->update('pr_transaksi', [
        'waktu_bayar' => $waktu_bayar,
        'kasir_bayar' => $kasir_id,
        'kode_voucher' => $kode_voucher,
        'diskon' => $diskon,
        'total_pembayaran' => $total_dibayar,
        'sisa_pembayaran' => $sisa,
        'status_pembayaran' => $status,
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    // Tandai semua detail transaksi sebagai BERHASIL
    $this->db->where('pr_transaksi_id', $trx->id)->update('pr_detail_transaksi', ['status' => 'BERHASIL']);

    // Tambah poin ke pr_customer_point jika memenuhi
    if ($trx->customer_id) {
        $poin_rules = $this->db->get('pr_poin')->result();
        foreach ($poin_rules as $rule) {
            if ($rule->jenis_point === 'per_pembelian' && $total_tagihan >= $rule->min_pembelian) {
                $this->db->insert('pr_customer_point', [
                    'customer_id' => $trx->customer_id,
                    'transaksi_id' => $trx->id,
                    'jumlah_poin' => $rule->nilai_point,
                    'jenis' => 'per_pembelian',
                    'sumber' => 'total_pembelian',
                    'tanggal_kadaluwarsa' => date('Y-m-d', strtotime('+6 months')),
                    'status' => 'aktif'
                ]);
            }

            if ($rule->jenis_point === 'per_produk') {
                $produk_terjual = $this->db->get_where('pr_detail_transaksi', ['pr_transaksi_id' => $trx->id])->result();
                foreach ($produk_terjual as $item) {
                    if ($item->pr_produk_id == $rule->produk_id) {
                        $this->db->insert('pr_customer_point', [
                            'customer_id' => $trx->customer_id,
                            'transaksi_id' => $trx->id,
                            'jumlah_poin' => $rule->nilai_point,
                            'jenis' => 'per_produk',
                            'sumber' => 'produk_id:' . $rule->produk_id,
                            'tanggal_kadaluwarsa' => date('Y-m-d', strtotime('+6 months')),
                            'status' => 'aktif'
                        ]);
                    }
                }
            }
        }
    }

    echo json_encode([
        "status" => "success",
        "message" => "Pembayaran berhasil disimpan",
        "status_pembayaran" => $status,
        "total_dibayar" => (int) $total_dibayar,
        "sisa" => (int) $sisa
    ]);
}


public function update_order() {
    $data = json_decode(file_get_contents("php://input"), true);
    $transaksi_id = $data['transaksi_id'];
    $items = $data['detail_transaksi']; // array

    // Ambil semua detail lama
    $existing = $this->db->get_where('pr_detail_transaksi', [
        'pr_transaksi_id' => $transaksi_id,
        'status IS NULL' => null // hanya aktif
    ])->result_array();

    $oldMap = [];
    foreach ($existing as $row) {
        $oldMap[$row['pr_produk_id']] = $row;
    }

    foreach ($items as $item) {
        $pr_produk_id = $item['pr_produk_id'];
        $jumlah_baru = intval($item['jumlah']);
        $catatan = $item['catatan'] ?? null;

        if (isset($oldMap[$pr_produk_id])) {
            $lama = $oldMap[$pr_produk_id];

            // Jika jumlah dikurangi
            if ($jumlah_baru < $lama['jumlah']) {
                $jumlah_void = $lama['jumlah'] - $jumlah_baru;
                $this->db->insert('pr_void', [
                    'pr_transaksi_id' => $transaksi_id,
                    'no_transaksi' => $this->get_no_transaksi($transaksi_id),
                    'detail_transaksi_id' => $lama['id'],
                    'pr_produk_id' => $lama['pr_produk_id'],
                    'nama_produk' => $this->get_nama_produk($lama['pr_produk_id']),
                    'jumlah' => $jumlah_void,
                    'harga' => $lama['harga'],
                    'subtotal' => $jumlah_void * $lama['harga'],
                    'catatan' => $lama['catatan'],
                    'alasan' => 'Pengurangan item saat edit pesanan',
                    'void_by' => 0,
                    'waktu' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            // Update detail
            $this->db->where('id', $lama['id'])->update('pr_detail_transaksi', [
                'jumlah' => $jumlah_baru,
                'subtotal' => $jumlah_baru * $lama['harga'],
                'catatan' => $catatan
            ]);
        } else {
            // Tambah item baru
            $this->db->insert('pr_detail_transaksi', [
                'pr_transaksi_id' => $transaksi_id,
                'pr_produk_id' => $pr_produk_id,
                'jumlah' => $jumlah_baru,
                'harga' => $item['harga'],
                'subtotal' => $item['harga'] * $jumlah_baru,
                'catatan' => $catatan
            ]);
        }
    }

    echo json_encode(["status" => "success", "message" => "Pesanan berhasil diubah"]);
}

private function get_no_transaksi($id) {
    $row = $this->db->get_where('pr_transaksi', ['id' => $id])->row();
    return $row ? $row->no_transaksi : '-';
}


public function getMetodePembayaran()
{
    $this->load->database();

    $result = $this->db->get('pr_metode_pembayaran');
    echo json_encode([
        "status" => "success",
        "data" => $result->result_array()
    ]);
}

// API untuk mengambil semua poin
public function getPoinRules()
{
    $this->load->database();

    $this->db->select('p.id, p.jenis_point, p.produk_id, p.min_pembelian, p.nilai_point, 
        IF(p.jenis_point = "per_produk", pr.nama_produk, "-") as nama_produk');
    $this->db->from('pr_poin p');
    $this->db->join('pr_produk pr', 'p.produk_id = pr.id', 'left');
    $query = $this->db->get();

    echo json_encode([
        "status" => "success",
        "data" => $query->result_array()
    ]);
}

public function getPromoVoucher()
{
    header("Content-Type: application/json");

    $today = date('Y-m-d');

    $this->db->select('*');
    $this->db->from('pr_voucher');
    $this->db->where('status', 'aktif');
    $this->db->where('tanggal_mulai <=', $today);
    $this->db->where('tanggal_berakhir >=', $today);

    $query = $this->db->get();
    $result = $query->result_array();

    echo json_encode([
        "status" => "success",
        "data" => $result
    ]);
}
public function getProdukExtra()
{
    $this->db->select('id, nama_extra, harga, sku, satuan, hpp');
    $query = $this->db->get('pr_produk_extra');

    if ($query->num_rows() > 0) {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($query->result()));
    } else {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([]));
    }
}



public function simpan_detail_extra($detail_transaksi_id, $extras) {
    foreach ($extras as $extra) {
        $produkExtraId = intval($extra['pr_produk_extra_id']);
        $jumlah = intval($extra['jumlah']);
        $harga = intval($extra['harga']);
        $subtotal = intval($extra['subtotal']);

        $info = $this->db->get_where('pr_produk_extra', ['id' => $produkExtraId])->row_array();

        $this->db->insert('pr_detail_extra', [
            'detail_transaksi_id' => $detail_transaksi_id,
            'pr_produk_extra_id' => $produkExtraId,
            'jumlah' => $jumlah,
            'harga' => $harga,
            'subtotal' => $subtotal,
            'sku' => $info['sku'] ?? '',
            'satuan' => $info['satuan'] ?? '',
            'hpp' => $info['hpp'] ?? 0
        ]);
    }
}

public function mark_as_printed() {
    $data = json_decode(file_get_contents("php://input"), true);
    $transaksi_id = $data['transaksi_id'] ?? null;

    if (!$transaksi_id) {
        echo json_encode([
            "status" => "error",
            "message" => "transaksi_id diperlukan"
        ]);
        return;
    }

    // Update semua detail transaksi yang belum dicetak (is_printed IS NULL)
    $this->db->where('pr_transaksi_id', $transaksi_id);
    $this->db->where('is_printed IS NULL', null, false);
    $this->db->update('pr_detail_transaksi', ['is_printed' => 1]);

    echo json_encode([
        "status" => "success",
        "message" => "Produk ditandai sebagai sudah dicetak"
    ]);
}


public function get_detail_belum_dicetak() {
    $transaksi_id = $this->input->get('transaksi_id');

    $query = $this->db->query("
        SELECT dt.*, p.nama_produk 
        FROM pr_detail_transaksi dt
        JOIN pr_produk p ON dt.pr_produk_id = p.id
        WHERE dt.pr_transaksi_id = ? AND dt.is_printed IS NULL
    ", [$transaksi_id]);

    echo json_encode([
        "status" => "success",
        "data" => $query->result_array()
    ]);
}




public function ubah_pesanan() {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input || !isset($input["transaksi_id"])) {
        echo json_encode(["status" => "error", "message" => "Data tidak valid"]);
        return;
    }

    $transaksi_id = $input["transaksi_id"];

    // Update data transaksi
    $transaksi = [
        "jenis_order_id" => $input["jenis_order_id"],
        "customer_id" => !empty($input["customer_id"]) ? $input["customer_id"] : NULL,
        "customer" => $input["customer"],
        "nomor_meja" => $input["nomor_meja"],
        "total_penjualan" => $input["total_penjualan"],
        "updated_at" => date("Y-m-d H:i:s")
    ];

    $this->db->where("id", $transaksi_id);
    $this->db->update("pr_transaksi", $transaksi);

    // Hapus detail transaksi sebelumnya
    $this->db->where("pr_transaksi_id", $transaksi_id)->delete("pr_detail_extra");
    $this->db->where("pr_transaksi_id", $transaksi_id)->delete("pr_detail_transaksi");

    // Simpan ulang detail transaksi baru
    foreach ($input["detail_transaksi"] as $index => $item) {
        $detail = [
            "pr_transaksi_id" => $transaksi_id,
            "pr_produk_id" => intval($item["pr_produk_id"]),
            "jumlah" => intval($item["jumlah"]),
            "harga" => intval($item["harga"]),
            "subtotal" => intval($item["subtotal"]),
            "catatan" => $item["catatan"]
        ];
        $this->db->insert("pr_detail_transaksi", $detail);
        $detail_id = $this->db->insert_id();

        // Simpan detail extra jika ada
        if (isset($item["extra"]) && is_array($item["extra"])) {
            foreach ($item["extra"] as $extra) {
                $produkExtraId = intval($extra['pr_produk_extra_id']);
                $jumlah = intval($extra['jumlah']);
                $harga = intval($extra['harga']);
                $subtotal = intval($extra['subtotal']);

                $info = $this->db->get_where('pr_produk_extra', ['id' => $produkExtraId])->row_array();

                $this->db->insert('pr_detail_extra', [
                    'detail_transaksi_id' => $detail_id,
                    'pr_produk_extra_id' => $produkExtraId,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'subtotal' => $subtotal,
                    'sku' => $info['sku'] ?? '',
                    'satuan' => $info['satuan'] ?? '',
                    'hpp' => $info['hpp'] ?? 0
                ]);
            }
        }
    }

    echo json_encode(["status" => "success", "message" => "Pesanan berhasil diubah"]);
}
public function batal_pesanan() {
    $data = json_decode(file_get_contents("php://input"), true);
    $transaksi_id = $data['transaksi_id'];
    $alasan = $data['alasan'] ?? 'Dibatalkan oleh admin';

    // Ambil transaksi
    $transaksi = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row_array();

    // Ambil semua detail transaksi
    $details = $this->db->get_where('pr_detail_transaksi', ['pr_transaksi_id' => $transaksi_id])->result_array();

    foreach ($details as $item) {
        $this->db->insert('pr_void', [
            'pr_transaksi_id' => $transaksi['id'],
            'no_transaksi' => $transaksi['no_transaksi'],
            'detail_transaksi_id' => $item['id'],
            'pr_produk_id' => $item['pr_produk_id'],
            'nama_produk' => $this->get_nama_produk($item['pr_produk_id']),
            'jumlah' => $item['jumlah'],
            'harga' => $item['harga'],
            'subtotal' => $item['subtotal'],
            'catatan' => $item['catatan'],
            'alasan' => $alasan,
            'void_by' => 0,
            'waktu' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Update status detail transaksi
        $this->db->where('id', $item['id'])->update('pr_detail_transaksi', [
            'status' => 'batal'
        ]);
    }

    // Update transaksi menjadi batal
    $this->db->where('id', $transaksi_id)->update('pr_transaksi', [
        'status_pembayaran' => 'BATAL'
    ]);

    echo json_encode(["status" => "success", "message" => "Pesanan berhasil dibatalkan"]);
}

private function get_nama_produk($id) {
    $row = $this->db->get_where('pr_produk', ['id' => $id])->row();
    return $row ? $row->nama_produk : '-';
}


public function pesanan_lunas()
{
    $tanggal = $this->input->get('tanggal'); // Opsional: filter berdasarkan tanggal

    $this->db->select('
        pr_transaksi.id,
        pr_transaksi.no_transaksi,
        pr_transaksi.tanggal,
        pr_transaksi.waktu_order,
        pr_transaksi.waktu_bayar,
        pr_transaksi.jenis_order_id,
        pr_transaksi.customer_id,
        pr_transaksi.customer,
        pr_transaksi.nomor_meja,
        pr_transaksi.total_penjualan,
        pr_transaksi.diskon,
        pr_transaksi.total_pembayaran,
        pr_transaksi.sisa_pembayaran,
        pr_transaksi.status_pembayaran,
        pr_transaksi.kasir_bayar,
        pr_transaksi.kode_voucher,
        pr_transaksi.created_at,
        pr_transaksi.updated_at
    ');
    $this->db->from('pr_transaksi');
    $this->db->where('status_pembayaran', 'LUNAS');

    // Tambahan filter tanggal jika diperlukan
    if (!empty($tanggal)) {
        $this->db->where('DATE(tanggal)', $tanggal);
    }

    $query = $this->db->get();
    echo json_encode([
        "status" => "success",
        "data" => $query->result_array()
    ]);
}
public function pesanan_semua_status()
{
    $tanggal = $this->input->get('tanggal'); // format: YYYY-MM-DD

    $this->db->select('
        t.id,
        t.no_transaksi,
        t.tanggal,
        t.waktu_order,
        t.waktu_bayar,
        t.jenis_order_id,
        t.customer_id,
        t.customer,
        t.nomor_meja,
        t.total_penjualan,
        t.diskon,
        t.total_pembayaran,
        t.sisa_pembayaran,
        t.status_pembayaran,
        t.kasir_bayar,
        t.kode_voucher,
        t.created_at,
        t.updated_at,
        p.nama AS nama_kasir
    ');
    $this->db->from('pr_transaksi t');
    $this->db->join('abs_pegawai p', 'p.id = t.kasir_bayar', 'left');

    if (!empty($tanggal)) {
        $this->db->where('DATE(t.tanggal)', $tanggal);
    }

    $this->db->order_by('t.waktu_order', 'DESC');
    $query = $this->db->get();

    echo json_encode([
        "status" => "success",
        "data" => $query->result_array()
    ]);
}


public function pesanan_by_status()
{
    $status = $this->input->get('status');   // contoh: LUNAS, DP, BATAL, REFUND
    $tanggal = $this->input->get('tanggal'); // opsional: filter berdasarkan tanggal

    if (!$status) {
        echo json_encode([
            "status" => "error",
            "message" => "Parameter status diperlukan"
        ]);
        return;
    }

    $this->db->select('
        t.id,
        t.no_transaksi,
        t.tanggal,
        t.waktu_order,
        t.waktu_bayar,
        t.jenis_order_id,
        t.customer_id,
        t.customer,
        t.nomor_meja,
        t.total_penjualan,
        t.diskon,
        t.total_pembayaran,
        t.sisa_pembayaran,
        t.status_pembayaran,
        t.kasir_bayar,
        t.kode_voucher,
        t.created_at,
        t.updated_at,
        p.nama AS nama_kasir
    ');
    $this->db->from('pr_transaksi t');
    $this->db->join('abs_pegawai p', 'p.id = t.kasir_bayar', 'left');
    $this->db->where('t.status_pembayaran', $status);

    if (!empty($tanggal)) {
        $this->db->where('DATE(t.tanggal)', $tanggal);
    }

    $query = $this->db->get();

    echo json_encode([
        "status" => "success",
        "data" => $query->result_array()
    ]);
}



/// ✅ **1. Ambil daftar lokasi printer**
    public function getLokasiPrinter() {
        echo json_encode($this->Printer_model->get_lokasi_printer());
    }

    /// ✅ **2. Ambil daftar printer**
    public function getPrinters() {
        echo json_encode($this->Printer_model->get_all_printers());
    }

    /// ✅ **3. Tambah printer baru**
    public function addPrinter() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !isset($data['nama_printer'], $data['alamat_mac'], $data['lokasi_id'])) {
            echo json_encode(["message" => "Data tidak lengkap"]);
            return;
        }

        $insert = $this->Printer_model->insert_printer($data);
        echo json_encode(["message" => $insert ? "Printer berhasil ditambahkan" : "Gagal menambahkan printer"]);
    }

    /// ✅ **4. Hapus printer**
    public function deletePrinter($id) {
        $delete = $this->Printer_model->delete_printer($id);
        echo json_encode(["message" => $delete ? "Printer berhasil dihapus" : "Gagal menghapus printer"]);
    }

public function simpanCustomerPoin() {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['customer_id']) || !isset($data['jumlah_poin']) || $data['jumlah_poin'] == 0) {
        echo json_encode(["status" => "error", "message" => "Data tidak lengkap atau jumlah poin 0"]);
        return;
    }

    $this->db->insert("pr_customer_poin", [
        "customer_id" => $data["customer_id"],
        "transaksi_id" => $data["transaksi_id"] ?? null,
        "jumlah_poin" => $data["jumlah_poin"],
        "jenis" => $data["jenis"] ?? 'per_produk',
        "sumber" => $data["sumber"] ?? null,
        "tanggal_kedaluwarsa" => $data["tanggal_kedaluwarsa"] ?? date("Y-m-d", strtotime("+6 months")),
        "status" => "aktif",
        "created_at" => date("Y-m-d H:i:s"),
        "updated_at" => date("Y-m-d H:i:s")
    ]);

    echo json_encode(["status" => "success", "message" => "Poin berhasil disimpan"]);
}

public function getTotalPoin() {
    $customer_id = $this->input->get("customer_id");

    if (!$customer_id) {
        echo json_encode(["status" => "error", "message" => "Customer ID diperlukan"]);
        return;
    }

    $this->db->select_sum("jumlah_poin");
    $this->db->where("customer_id", $customer_id);
    $this->db->where("status", "aktif");
    $this->db->where("tanggal_kedaluwarsa >=", date("Y-m-d"));
    $query = $this->db->get("pr_customer_poin");

    $total = (int) $query->row()->jumlah_poin;

    echo json_encode(["status" => "success", "total_poin" => $total]);
}

public function getRiwayatPoin() {
    $customer_id = $this->input->get("customer_id");

    if (!$customer_id) {
        echo json_encode(["status" => "error", "message" => "Customer ID diperlukan"]);
        return;
    }

    $this->db->where("customer_id", $customer_id);
    $this->db->order_by("created_at", "DESC");
    $query = $this->db->get("pr_customer_poin");

    echo json_encode(["status" => "success", "data" => $query->result_array()]);
}

// public function refund_pesanan()
// {
//     $data = json_decode(file_get_contents("php://input"), true);

//     $transaksi_id = $data['pr_transaksi_id'] ?? null;
//     $detail_id = $data['pr_detail_transaksi_id'] ?? null;
//     $alasan = $data['alasan'] ?? null;
//     $refund_by = $data['refund_by'] ?? null;

//     if (!$transaksi_id || !$detail_id || !$alasan || !$refund_by) {
//         echo json_encode([
//             "status" => "error",
//             "message" => "Data tidak lengkap"
//         ]);
//         return;
//     }

//     // Ambil detail transaksi
//     $detail = $this->db->get_where('pr_detail_transaksi', ['id' => $detail_id])->row_array();
//     if (!$detail) {
//         echo json_encode([
//             "status" => "error",
//             "message" => "Detail transaksi tidak ditemukan"
//         ]);
//         return;
//     }

//     // Ambil data produk
//     $produk = $this->db->get_where('pr_produk', ['id' => $detail['pr_produk_id']])->row_array();
//     $transaksi = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row_array();

//     // Simpan ke pr_refund
//     $this->db->insert('pr_refund', [
//         'pr_transaksi_id' => $transaksi_id,
//         'pr_detail_transaksi_id' => $detail_id,
//         'no_transaksi' => $transaksi['no_transaksi'] ?? '',
//         'nama_produk' => $produk['nama_produk'] ?? '',
//         'jumlah' => $detail['jumlah'],
//         'harga' => $detail['harga'],
//         'subtotal' => $detail['subtotal'],
//         'catatan' => $detail['catatan'],
//         'alasan' => $alasan,
//         'refund_by' => $refund_by,
//         'waktu_refund' => date('Y-m-d H:i:s')
//     ]);

//     // Update status di pr_detail_transaksi
//     $this->db->where('id', $detail_id)->update('pr_detail_transaksi', ['status' => 'REFUND']);

//     // Update status pembayaran pr_transaksi
//     $this->db->where('id', $transaksi_id)->update('pr_transaksi', ['status_pembayaran' => 'REFUND']);

//     echo json_encode([
//         "status" => "success",
//         "message" => "Pesanan berhasil direfund"
//     ]);
// }


public function refund_pesanan()
{
    $data = json_decode(file_get_contents("php://input"), true);

    $transaksi_id = $data['pr_transaksi_id'] ?? null;
    $detail_id = $data['pr_detail_transaksi_id'] ?? null;
    $alasan = $data['alasan'] ?? null;
    $refund_by = $data['refund_by'] ?? null;

    if (!$transaksi_id || !$detail_id || !$alasan || !$refund_by) {
        echo json_encode([
            "status" => "error",
            "message" => "Data tidak lengkap"
        ]);
        return;
    }

    // Ambil detail transaksi
    $detail = $this->db->get_where('pr_detail_transaksi', ['id' => $detail_id])->row_array();
    if (!$detail) {
        echo json_encode([
            "status" => "error",
            "message" => "Detail transaksi tidak ditemukan"
        ]);
        return;
    }

    // Ambil data produk dan transaksi
    $produk = $this->db->get_where('pr_produk', ['id' => $detail['pr_produk_id']])->row_array();
    $transaksi = $this->db->get_where('pr_transaksi', ['id' => $transaksi_id])->row_array();

    // Simpan ke pr_refund
    $this->db->insert('pr_refund', [
        'pr_transaksi_id' => $transaksi_id,
        'pr_detail_transaksi_id' => $detail_id,
        'no_transaksi' => $transaksi['no_transaksi'] ?? '',
        'nama_produk' => $produk['nama_produk'] ?? '',
        'jumlah' => $detail['jumlah'],
        'harga' => $detail['harga'],
        'subtotal' => $detail['subtotal'],
        'catatan' => $detail['catatan'],
        'alasan' => $alasan,
        'refund_by' => $refund_by,
        'waktu_refund' => date('Y-m-d H:i:s')
    ]);

    // Update status produk ke REFUND
    $this->db->where('id', $detail_id)->update('pr_detail_transaksi', ['status' => 'REFUND']);

    // Cek apakah semua item sudah REFUND
    $this->db->where('pr_transaksi_id', $transaksi_id);
    $this->db->where('status !=', 'REFUND');
    $sisa_item = $this->db->get('pr_detail_transaksi')->num_rows();

    if ($sisa_item === 0) {
        // Semua item di-refund → ubah status transaksi
        $this->db->where('id', $transaksi_id)->update('pr_transaksi', ['status_pembayaran' => 'REFUND']);
    }

    echo json_encode([
        "status" => "success",
        "message" => "Pesanan berhasil direfund"
    ]);
}

public function refund_transaksi() {
    $data = json_decode(file_get_contents("php://input"), true);

    $transaksi_id = $data['transaksi_id'] ?? null;
    $alasan = $data['alasan'] ?? 'Refund';
    $refund_by = $data['refund_by'] ?? null;

    if (!$transaksi_id || !$refund_by) {
        echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
        return;
    }

    // Ambil data transaksi
    $trx = $this->db->get_where("pr_transaksi", ["id" => $transaksi_id])->row_array();
    if (!$trx) {
        echo json_encode(["status" => "error", "message" => "Transaksi tidak ditemukan"]);
        return;
    }

    // Ambil semua detail transaksi
    $detailList = $this->db
        ->where("pr_transaksi_id", $transaksi_id)
        ->get("pr_detail_transaksi")
        ->result_array();

    if (empty($detailList)) {
        echo json_encode(["status" => "error", "message" => "Detail transaksi tidak ditemukan"]);
        return;
    }

    foreach ($detailList as $detail) {
        // Ambil nama produk
        $produk = $this->db->get_where("pr_produk", ["id" => $detail['pr_produk_id']])->row_array();

        $this->db->insert("pr_refund", [
            "pr_transaksi_id"        => $transaksi_id,
            "pr_detail_transaksi_id" => $detail['id'],
            "no_transaksi"           => $trx['no_transaksi'],
            "nama_produk"            => $produk['nama_produk'] ?? '(Tidak diketahui)',
            "jumlah"                 => $detail['jumlah'],
            "harga"                  => $detail['harga'],
            "subtotal"               => $detail['subtotal'],
            "catatan"                => $detail['catatan'],
            "alasan"                 => $alasan,
            "refund_by"              => $refund_by,
            "waktu_refund"           => date("Y-m-d H:i:s"),
            "created_at"             => date("Y-m-d H:i:s"),
            "updated_at"             => date("Y-m-d H:i:s")
        ]);

        // Update status detail transaksi
        $this->db->where("id", $detail['id'])->update("pr_detail_transaksi", [
            "status" => "REFUND",
            "updated_at" => date("Y-m-d H:i:s")
        ]);
    }

    // Update status pembayaran transaksi
    $this->db->where("id", $transaksi_id)->update("pr_transaksi", [
        "status_pembayaran" => "REFUND",
        "updated_at" => date("Y-m-d H:i:s")
    ]);

    echo json_encode(["status" => "success", "message" => "Seluruh item dalam transaksi telah direfund"]);
}
public function get_printer_setting($lokasi_id = null)
{
    if (!$lokasi_id) {
        echo json_encode(["status" => "error", "message" => "Lokasi printer tidak ditemukan"]);
        return;
    }

    // Ambil lokasi
    $lokasi = $this->db->get_where('pr_lokasi_printer', ['id' => $lokasi_id])->row_array();
    if (!$lokasi) {
        echo json_encode(["status" => "error", "message" => "Data lokasi tidak valid"]);
        return;
    }

    // Ambil pengaturan tampilan
    $tampilan = $this->db->get_where('pr_struk_tampilan', ['pr_lokasi_printer_id' => $lokasi_id])->row_array();

    // Ambil data outlet
    $struk = $this->db->get('pr_struk')->row_array();

    $logo_url = !empty($struk['logo']) 
        ? base_url('uploads/' . $struk['logo']) 
        : null;

    echo json_encode([
        "status" => "success",
        "lokasi" => $lokasi['nama_lokasi'],
        "pengaturan" => $tampilan,
        "data_struk" => [
            "nama_outlet" => $struk['nama_outlet'],
            "alamat" => $struk['alamat'],
            "email" => $struk['email'],
            "no_telepon" => $struk['no_telepon'],
            "custom_header" => $struk['custom_header'],
            "custom_footer" => $struk['custom_footer']
        ],
        "logo_url" => $logo_url
    ]);
}

}


?>
