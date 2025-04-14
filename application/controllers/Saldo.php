<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Saldo extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Saldo_model');
    }

    public function index() {
        
        // Ambil input filter bulan dan tahun
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $rekening_id = $this->input->get('rekening_id') ?: null;

        // Hitung tanggal awal dan akhir
        $start_date = "$tahun-$bulan-01";
        $end_date = date("Y-m-t", strtotime($start_date)); // Akhir bulan

        // Ambil daftar rekening
        $data['rekening_list'] = $this->Saldo_model->get_rekening_list();

        if ($rekening_id) {
            // Ambil saldo awal rekening
            $saldo_awal = $this->Saldo_model->get_saldo_awal($rekening_id, $start_date);

            // Ambil transaksi harian
            $transaksi_harian = $this->Saldo_model->get_transaksi_harian($start_date, $end_date, $rekening_id);

            // Hitung saldo berjalan
            $saldo_berjalan = [];
            $saldo_sekarang = $saldo_awal;
            foreach ($transaksi_harian as $transaksi) {
                $saldo_sekarang += ($transaksi['penjualan'] ?? 0) 
                                + ($transaksi['mutasi_masuk'] ?? 0) 
                                - ($transaksi['mutasi_keluar'] ?? 0) 
                                - ($transaksi['pembelian'] ?? 0);

                $saldo_berjalan[] = [
                    'tanggal' => $transaksi['tanggal'],
                    'penjualan' => $transaksi['penjualan'],
                    'mutasi_masuk' => $transaksi['mutasi_masuk'],
                    'mutasi_keluar' => $transaksi['mutasi_keluar'],
                    'pembelian' => $transaksi['pembelian'],
                    'saldo' => $saldo_sekarang
                ];
            }

            $data['saldo_awal'] = $saldo_awal;
            $data['saldo_berjalan'] = $saldo_berjalan;
        } else {
            $data['saldo_awal'] = 0;
            $data['saldo_berjalan'] = [];
        }

        $data['rekening_id'] = $rekening_id;
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $this->load->view('templates/header', $data);
        $this->load->view('saldo/index', $data);
        $this->load->view('templates/footer');
    }
}
