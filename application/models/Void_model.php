<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Void_model extends CI_Model {
    public function log_void($item, $jumlah_void, $alasan) {
        $user_id = $this->session->userdata('pegawai_id'); // Ambil kasir/pegawai yang login

        $this->db->insert('pr_void', [
            'pr_transaksi_id'     => $item['pr_transaksi_id'],
            'no_transaksi'        => $item['no_transaksi'], // Tambahkan dari parent transaksi
            'detail_transaksi_id' => $item['id'],
            'pr_produk_id'        => $item['pr_produk_id'],
            'nama_produk'         => $item['nama_produk'],
            'jumlah'              => $jumlah_void,
            'harga'               => $item['harga'],
            'subtotal'            => $item['harga'] * $jumlah_void,
            'catatan'             => $item['catatan'],
            'alasan'              => $alasan,
            'void_by'             => $user_id,
            'waktu'               => date('Y-m-d H:i:s'),
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s')
        ]);
    }

}