<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_Brankas_model extends CI_Model {

    public function get_branks_report_data($bulan) {
        // Start and end dates for the selected month
        $start_date = $bulan . '-01';
        $end_date = date("Y-m-t", strtotime($start_date));

        // Query to fetch saldo awal brankas (bl_kas)
        $this->db->select('tanggal, jumlah as saldo_awal');
        $this->db->from('bl_kas');
        $this->db->where('bl_rekening_id', 1);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $query = $this->db->get();
        $result_kas = $query->result_array();

        // Initialize dates_in_month array
        $dates_in_month = [];
        for ($date = strtotime($start_date); $date <= strtotime($end_date); $date += 86400) {
            $formatted_date = date('Y-m-d', $date);
            $dates_in_month[$formatted_date] = [
                'tanggal' => $formatted_date,
                'saldo_awal' => 0,
                'pendapatan' => 0,
                'mutasi_kas' => 0,
                'refund' => 0,
                'belanja' => 0,
                'total_brankas' => 0
            ];
        }

        // Populate saldo_awal based on bl_kas
        foreach ($result_kas as $row) {
            $dates_in_month[$row['tanggal']]['saldo_awal'] = $row['saldo_awal'];
        }

        // Query to get pendapatan (bl_penjualan_majoo)
        $this->db->select('tanggal, SUM(penjualan) AS pendapatan');
        $this->db->from('bl_penjualan_majoo');
        $this->db->where('rekening_id', 1);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->group_by('tanggal');
        $query = $this->db->get();
        $result_pendapatan = $query->result_array();

        // Populate dates_in_month with pendapatan
        foreach ($result_pendapatan as $row) {
            $dates_in_month[$row['tanggal']]['pendapatan'] = $row['pendapatan'];
        }

        // Query for mutasi kas (bl_mutasi_kas)
        $this->db->select('tanggal, 
            SUM(CASE WHEN jenis_mutasi = "masuk" THEN jumlah ELSE 0 END) AS mutasi_masuk,
            SUM(CASE WHEN jenis_mutasi = "keluar" THEN jumlah ELSE 0 END) AS mutasi_keluar');
        $this->db->from('bl_mutasi_kas');
        $this->db->where('bl_rekening_id', 1);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->group_by('tanggal');
        $query = $this->db->get();
        $result_mutasi_kas = $query->result_array();

        // Query for mutasi rekening (bl_mutasi_kas_rekening)
        $this->db->select('tanggal, 
            SUM(CASE WHEN bl_rekening_id_tujuan = 1 THEN jumlah ELSE 0 END) AS mutasi_rekening_masuk,
            SUM(CASE WHEN bl_rekening_id_sumber = 1 THEN jumlah ELSE 0 END) AS mutasi_rekening_keluar');
        $this->db->from('bl_mutasi_kas_rekening');
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->group_by('tanggal');
        $query = $this->db->get();
        $result_mutasi_rekening = $query->result_array();

        // Gabungkan data berdasarkan tanggal
        $mutasi_data = [];

        foreach ($result_mutasi_kas as $row) {
            $mutasi_data[$row['tanggal']] = [
                'mutasi_kas' => $row['mutasi_masuk'] - $row['mutasi_keluar'],
                'mutasi_rekening' => 0
            ];
        }

        foreach ($result_mutasi_rekening as $row) {
            if (isset($mutasi_data[$row['tanggal']])) {
                $mutasi_data[$row['tanggal']]['mutasi_rekening'] = $row['mutasi_rekening_masuk'] - $row['mutasi_rekening_keluar'];
            } else {
                $mutasi_data[$row['tanggal']] = [
                    'mutasi_kas' => 0,
                    'mutasi_rekening' => $row['mutasi_rekening_masuk'] - $row['mutasi_rekening_keluar']
                ];
            }
        }

        // Populate dates_in_month with combined mutasi data
        foreach ($dates_in_month as $date => &$data) {
            if (isset($mutasi_data[$date])) {
                $data['mutasi_kas'] = $mutasi_data[$date]['mutasi_kas'] + $mutasi_data[$date]['mutasi_rekening'];
            }
        }

        // Query to get refund (bl_refund)
        $this->db->select('tanggal, SUM(nilai) AS refund');
        $this->db->from('bl_refund');
        $this->db->where('rekening', 1);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->group_by('tanggal');
        $query = $this->db->get();
        $result_refund = $query->result_array();

        // Populate dates_in_month with refund data
        foreach ($result_refund as $row) {
            $dates_in_month[$row['tanggal']]['refund'] = $row['refund'];
        }

        // Query to get belanja (bl_purchase)
        $this->db->select('tanggal, SUM(total_harga) AS belanja');
        $this->db->from('bl_purchase');
        $this->db->where('metode_pembayaran', 1);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->group_by('tanggal');
        $query = $this->db->get();
        $result_belanja = $query->result_array();

        // Populate dates_in_month with belanja data
        foreach ($result_belanja as $row) {
            $dates_in_month[$row['tanggal']]['belanja'] = $row['belanja'];
        }

        // Calculate total brankas
        foreach ($dates_in_month as $date => &$data) {
            $data['total_brankas'] = $data['saldo_awal'] + $data['mutasi_kas'] + $data['pendapatan'] - $data['refund'] - $data['belanja'];
        }

        return $dates_in_month;
    }
}
