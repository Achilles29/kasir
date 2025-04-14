<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model {
    public function create_transaksi($data, $items) {
        $this->db->trans_start();

        // Simpan transaksi utama
        $this->db->insert("pr_transaksi", $data);
        $transaksi_id = $this->db->insert_id();

        // Simpan detail transaksi
        foreach ($items as $item) {
            $item["pr_transaksi_id"] = $transaksi_id;
            $this->db->insert("pr_detail_transaksi", $item);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function generate_no_transaksi() {
        $date = date("ymd"); // YYMMDD
        $this->db->select("MAX(RIGHT(no_transaksi, 4)) AS max_id");
        $this->db->from("pr_transaksi");
        $this->db->where("tanggal", date("Y-m-d"));
        $query = $this->db->get()->row();

        $new_id = isset($query->max_id) ? $query->max_id + 1 : 1;
        $new_id = str_pad($new_id, 4, "0", STR_PAD_LEFT);
        return "CS/63/" . $date . "/" . $new_id;
    }

// public function simpan_transaksi($data) {
//     if (!isset($data['customer_id']) || empty($data['customer_id'])) {
//         $data['customer_id'] = NULL; // Set customer_id ke NULL jika tidak ada
//     }

//     return $this->db->insert('pr_transaksi', $data) ? $this->db->insert_id() : false;
// }


public function get_transaksi($id) {
    $this->db->select("id, no_transaksi, customer, nomor_meja, total_pembayaran");
    $this->db->from("pr_transaksi");
    $this->db->where("id", $id);
    $transaksi = $this->db->get()->row_array();

    if ($transaksi) {
        $this->db->select("pr_produk.nama_produk, pr_detail_transaksi.jumlah, pr_detail_transaksi.harga, pr_detail_transaksi.subtotal, pr_detail_transaksi.catatan");
        $this->db->from("pr_detail_transaksi");
        $this->db->join("pr_produk", "pr_detail_transaksi.pr_produk_id = pr_produk.id");
        $this->db->where("pr_detail_transaksi.pr_transaksi_id", $id);
        $transaksi["items"] = $this->db->get()->result_array();
    }

    return $transaksi;
}
public function get_divisi_items($transaksi_id, $divisi_id) {
    $this->db->select('pr_produk.nama_produk, pr_detail_transaksi.jumlah, pr_detail_transaksi.catatan');
    $this->db->from('pr_detail_transaksi');
    $this->db->join('pr_produk', 'pr_produk.id = pr_detail_transaksi.pr_produk_id', 'left');
    $this->db->join('pr_kategori', 'pr_produk.kategori_id = pr_kategori.id', 'left');
    $this->db->where('pr_kategori.pr_divisi_id', $divisi_id);
    $this->db->where('pr_detail_transaksi.pr_transaksi_id', $transaksi_id);
    return $this->db->get()->result_array();
}
public function get_transaction_details($transaksi_id) {
    $this->db->select('pr_produk.nama_produk, pr_detail_transaksi.jumlah, pr_detail_transaksi.harga, pr_detail_transaksi.subtotal, pr_detail_transaksi.catatan');
    $this->db->from('pr_detail_transaksi');
    $this->db->join('pr_produk', 'pr_produk.id = pr_detail_transaksi.pr_produk_id', 'left');
    $this->db->where('pr_detail_transaksi.pr_transaksi_id', $transaksi_id);
    return $this->db->get()->result_array();
}
// public function get_transaksi_by_id($id) {
//     $this->db->select('pr_transaksi.*,
//                        pr_jenis_order.jenis_order, 
//                        pr_metode_pembayaran.metode_pembayaran AS metode_pembayaran,
//                        p1.username AS kasir_order_username, 
//                        p2.username AS kasir_bayar_username');
//     $this->db->from('pr_transaksi');
//     $this->db->join('pr_jenis_order', 'pr_transaksi.jenis_order_id = pr_jenis_order.id', 'left');
//     $this->db->join('pr_metode_pembayaran', 'pr_transaksi.metode_pembayaran_id = pr_metode_pembayaran.id', 'left');
//     $this->db->join('abs_pegawai AS p1', 'pr_transaksi.kasir_order = p1.id', 'left');
//     $this->db->join('abs_pegawai AS p2', 'pr_transaksi.kasir_bayar = p2.id', 'left');
//     $this->db->where('pr_transaksi.id', $id);
//     return $this->db->get()->row_array();
// }


    // Ambil semua transaksi yang belum dibayar
    public function get_pending_orders() {
        $this->db->select('id, no_transaksi, customer, total_pembayaran');
        $this->db->from('pr_transaksi');
        $this->db->where('waktu_bayar IS NULL'); // Hanya transaksi yang belum dibayar
        $this->db->order_by('waktu_order', 'DESC');
        return $this->db->get()->result_array();
    }

    // Ambil transaksi berdasarkan ID
    public function get_transaksi_by_id($id) {
        $this->db->select('*');
        $this->db->from('pr_transaksi');
        $this->db->where('id', $id);
        $transaksi = $this->db->get()->row_array();

        if (!$transaksi) {
            return null;
        }

        // Ambil detail item transaksi
        $this->db->select('pr_produk.nama_produk, pr_detail_transaksi.jumlah, pr_detail_transaksi.harga, pr_detail_transaksi.subtotal, pr_detail_transaksi.catatan');
        $this->db->from('pr_detail_transaksi');
        $this->db->join('pr_produk', 'pr_detail_transaksi.pr_produk_id = pr_produk.id');
        $this->db->where('pr_detail_transaksi.pr_transaksi_id', $id);
        $transaksi['items'] = $this->db->get()->result_array();

        return $transaksi;
    }

    /// ✅ **Ambil ID Transaksi Terakhir**
    public function get_last_transaction_id() {
        $this->db->select("MAX(id) as last_id");
        $this->db->from("pr_transaksi");
        $query = $this->db->get();

        $row = $query->row();
        return $row ? (int) $row->last_id : 0;
    }

    /// ✅ **Simpan Transaksi ke Database**
public function simpan_transaksi($data) {
    $this->db->insert('pr_transaksi', [
        'no_transaksi' => $data['no_transaksi'],
        'tanggal' => $data['tanggal'],
        'waktu_order' => $data['waktu_order'],
        'jenis_order_id' => $data['jenis_order_id'],
        'customer_id' => isset($data['customer_id']) ? $data['customer_id'] : NULL,
        'customer' => !empty($data['customer']) ? $data['customer'] : 'Walk-in Customer',
        'nomor_meja' => !empty($data['nomor_meja']) ? $data['nomor_meja'] : NULL, // ✅ PASTIKAN TERSIMPAN
        'total_penjualan' => $data['total_penjualan'],
        'kasir_order' => isset($data['kasir_order']) ? $data['kasir_order'] : NULL, // ✅ CEK KASIR ORDER
        'waktu_bayar' => NULL,
        'metode_pembayaran' => NULL,
        'kasir_bayar' => NULL,
        'kode_voucher' => NULL,
        'diskon' => NULL,
        'total_pembayaran' => NULL
    ]);

    if (!$this->db->affected_rows()) {
        log_message('error', 'Gagal menyimpan transaksi: ' . json_encode($this->db->error()));
        return false;
    }

    $transaksi_id = $this->db->insert_id();

    foreach ($data['detail_transaksi'] as $item) {
        $this->db->insert('pr_detail_transaksi', [
            'pr_transaksi_id' => $transaksi_id,
            'pr_produk_id' => intval($item['pr_produk_id']),
            'jumlah' => intval($item['jumlah']),
            'harga' => intval($item['harga']),
            'subtotal' => intval($item['subtotal']),
            'catatan' => $item['catatan']
        ]);

        if (!$this->db->affected_rows()) {
            log_message('error', 'Gagal menyimpan detail transaksi: ' . json_encode($this->db->error()));
        }
    }

    return $transaksi_id;
}

    /// ✅ **Ambil ID Transaksi Terakhir**
    public function get_last_transaction_id() {
        $this->db->select("MAX(id) as last_id");
        $this->db->from("pr_transaksi");
        $query = $this->db->get();

        $row = $query->row();
        return $row ? (int) $row->last_id : 0;
    }

    /// ✅ **Simpan Transaksi ke Database**
   public function simpan_transaksi($data) {
    $this->db->insert('pr_transaksi', [
        'no_transaksi' => $data['no_transaksi'],
        'tanggal' => $data['tanggal'],
        'waktu_order' => $data['waktu_order'],
        'jenis_order_id' => $data['jenis_order_id'],
        'customer_id' => isset($data['customer_id']) ? $data['customer_id'] : NULL,
        'customer' => !empty($data['customer']) ? $data['customer'] : 'Walk-in Customer',
        'nomor_meja' => !empty($data['nomor_meja']) ? $data['nomor_meja'] : NULL,
        'total_penjualan' => $data['total_penjualan'],
        'kasir_order' => $data['kasir_order'],
        'waktu_bayar' => NULL,
        'metode_pembayaran' => NULL,
        'kasir_bayar' => NULL,
        'kode_voucher' => NULL,
        'diskon' => NULL,
        'total_pembayaran' => NULL
    ]);

    if (!$this->db->affected_rows()) {
        log_message('error', 'Gagal menyimpan transaksi: ' . json_encode($this->db->error()));
        return false;
    }

    $transaksi_id = $this->db->insert_id();

    foreach ($data['detail_transaksi'] as $item) {
        $this->db->insert('pr_detail_transaksi', [
            'pr_transaksi_id' => $transaksi_id,
            'pr_produk_id' => intval($item['pr_produk_id']), // ✅ Konversi ke INT
            'jumlah' => intval($item['jumlah']),
            'harga' => intval($item['harga']),
            'subtotal' => intval($item['subtotal']),
            'catatan' => $item['catatan']
        ]);

        if (!$this->db->affected_rows()) {
            log_message('error', 'Gagal menyimpan detail transaksi: ' . json_encode($this->db->error()));
        }
    }

    return $transaksi_id;
}

}

