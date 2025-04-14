<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SaldoKas_model extends CI_Model {

    public function get_saldo_kas_berjalan($bulan, $tahun) {
        // Ambil saldo awal berdasarkan bulan dan tahun
        $this->db->select('bl_kas.bl_rekening_id, bl_rekening.nama_rekening, SUM(bl_kas.jumlah) AS saldo_awal');
        $this->db->from('bl_kas');
        $this->db->join('bl_rekening', 'bl_kas.bl_rekening_id = bl_rekening.id', 'left');
        $this->db->where('MONTH(bl_kas.tanggal)', $bulan);
        $this->db->where('YEAR(bl_kas.tanggal)', $tahun);
        $this->db->group_by('bl_kas.bl_rekening_id');
        $saldo_awal = $this->db->get()->result_array();

        // Ambil transaksi berdasarkan bulan dan tahun
        $this->db->select('DATE(tanggal) AS tanggal, rekening_id, SUM(penjualan) AS penjualan');
        $this->db->from('bl_penjualan_majoo');
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->group_by(['DATE(tanggal)', 'rekening_id']);
        $penjualan = $this->db->get()->result_array();

        $this->db->select('DATE(tanggal) AS tanggal, metode_pembayaran AS bl_rekening_id, SUM(total_harga) AS pembelian');
        $this->db->from('bl_purchase');
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->group_by(['DATE(tanggal)', 'metode_pembayaran']);
        $pembelian = $this->db->get()->result_array();

        $this->db->select('DATE(tanggal) AS tanggal, bl_rekening_id, jenis_mutasi, SUM(jumlah) AS jumlah');
        $this->db->from('bl_mutasi_kas');
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->group_by(['DATE(tanggal)', 'bl_rekening_id', 'jenis_mutasi']);
        $mutasi = $this->db->get()->result_array();

        // Susun data saldo berjalan berdasarkan tanggal
        $tanggal_awal = date('Y-m-d', strtotime("$tahun-$bulan-01"));
        $tanggal_akhir = date('Y-m-t', strtotime("$tahun-$bulan-01"));

        $saldo_kas = [];
        foreach ($saldo_awal as $rekening) {
            $saldo_kas[$rekening['bl_rekening_id']] = [
                'nama_rekening' => $rekening['nama_rekening'],
                'saldo_awal' => $rekening['saldo_awal'],
                'transaksi' => []
            ];

            $saldo = $rekening['saldo_awal'];
            for ($tanggal = strtotime($tanggal_awal); $tanggal <= strtotime($tanggal_akhir); $tanggal = strtotime('+1 day', $tanggal)) {
                $tanggal_str = date('Y-m-d', $tanggal);

                $penjualan_harian = array_sum(array_column(array_filter($penjualan, function($p) use ($tanggal_str, $rekening) {
                    return $p['tanggal'] === $tanggal_str && $p['bl_rekening_id'] == $rekening['bl_rekening_id'];
                }), 'penjualan'));

                $pembelian_harian = array_sum(array_column(array_filter($pembelian, function($p) use ($tanggal_str, $rekening) {
                    return $p['tanggal'] === $tanggal_str && $p['bl_rekening_id'] == $rekening['bl_rekening_id'];
                }), 'pembelian'));

                $mutasi_masuk = array_sum(array_column(array_filter($mutasi, function($m) use ($tanggal_str, $rekening) {
                    return $m['tanggal'] === $tanggal_str && $m['bl_rekening'] == $rekening['bl_rekening_id'] && $m['jenis_mutasi'] === 'masuk';
                }), 'jumlah'));

                $mutasi_keluar = array_sum(array_column(array_filter($mutasi, function($m) use ($tanggal_str, $rekening) {
                    return $m['tanggal'] === $tanggal_str && $m['bl_rekening'] == $rekening['bl_rekening_id'] && $m['jenis_mutasi'] === 'keluar';
                }), 'jumlah'));

                $saldo += $penjualan_harian - $pembelian_harian + $mutasi_masuk - $mutasi_keluar;

                $saldo_kas[$rekening['bl_rekening_id']]['transaksi'][$tanggal_str] = $saldo;
            }
        }

        return $saldo_kas;
    }
}


