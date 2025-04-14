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
        $this->db->where('bl_rekening_id', 1); // Saldo awal for rekening_id = 1
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
        $this->db->where('rekening_id', 1); // Filter for rekening_id = 1
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->group_by('tanggal');
        $query = $this->db->get();
        $result_pendapatan = $query->result_array();

        // Populate dates_in_month with pendapatan
        foreach ($result_pendapatan as $row) {
            $dates_in_month[$row['tanggal']]['pendapatan'] = $row['pendapatan'];
        }

        // Query to get mutasi kas (masuk/keluar) and mutasi kas rekening
$this->db->select('
    bl_mutasi_kas.tanggal AS tanggal,
    SUM(CASE WHEN bl_mutasi_kas.jenis_mutasi = "masuk" THEN bl_mutasi_kas.jumlah ELSE 0 END) AS mutasi_masuk,
    SUM(CASE WHEN bl_mutasi_kas.jenis_mutasi = "keluar" THEN bl_mutasi_kas.jumlah ELSE 0 END) AS mutasi_keluar,
    SUM(CASE WHEN bl_mutasi_kas_rekening_tujuan.bl_rekening_id_tujuan = 1 THEN bl_mutasi_kas_rekening_tujuan.jumlah ELSE 0 END) AS mutasi_kas_rekening_tujuan,
    SUM(CASE WHEN bl_mutasi_kas_rekening_sumber.bl_rekening_id_sumber = 1 THEN bl_mutasi_kas_rekening_sumber.jumlah ELSE 0 END) AS mutasi_kas_rekening_sumber
');
$this->db->from('bl_mutasi_kas');

// Join with alias for two different cases
$this->db->join('bl_mutasi_kas_rekening AS bl_mutasi_kas_rekening_tujuan', 'bl_mutasi_kas_rekening_tujuan.bl_rekening_id_tujuan = bl_mutasi_kas.bl_rekening_id', 'left');
$this->db->join('bl_mutasi_kas_rekening AS bl_mutasi_kas_rekening_sumber', 'bl_mutasi_kas_rekening_sumber.bl_rekening_id_sumber = bl_mutasi_kas.bl_rekening_id', 'left');

$this->db->where('bl_mutasi_kas.bl_rekening_id', 1); // For rekening_id = 1
$this->db->where('bl_mutasi_kas.tanggal >=', $start_date);
$this->db->where('bl_mutasi_kas.tanggal <=', $end_date);
$this->db->group_by('bl_mutasi_kas.tanggal');
$query = $this->db->get();
$result_mutasi = $query->result_array();



        // Populate dates_in_month with mutasi data
        foreach ($result_mutasi as $row) {
            $dates_in_month[$row['tanggal']]['mutasi_kas'] = $row['mutasi_masuk'] - $row['mutasi_keluar'] + $row['mutasi_kas_rekening_tujuan'] - $row['mutasi_kas_rekening_sumber'];
        }

        // Query to get refund (bl_refund)
        $this->db->select('tanggal, SUM(nilai) AS refund');
        $this->db->from('bl_refund');
        $this->db->where('rekening', 1); // For rekening = 1
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
        $this->db->where('metode_pembayaran', 1); // For metode_pembayaran = 1
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
