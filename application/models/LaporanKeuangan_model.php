<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanKeuangan_model extends CI_Model {

    public function get_laporan_keuangan($bulan, $tahun) {
        $tanggal_awal = "$tahun-$bulan-01";
        $tanggal_akhir = date("Y-m-t", strtotime($tanggal_awal));

    // public function get_laporan_keuangan($bulan) {
    //     $tanggal_awal = $bulan . '-01';
    //     $tanggal_akhir = date("Y-m-t", strtotime($tanggal_awal));

        // Query Penjualan
        $this->db->select('bl_penjualan_majoo.tanggal AS tanggal, 
                        SUM(bl_penjualan_majoo.penjualan) AS total_penjualan');
        $this->db->from('bl_penjualan_majoo');
        $this->db->where('bl_penjualan_majoo.tanggal >=', $tanggal_awal);
        $this->db->where('bl_penjualan_majoo.tanggal <=', $tanggal_akhir);
        $this->db->group_by('bl_penjualan_majoo.tanggal');
        $penjualan = $this->db->get()->result_array();

        // Query Refund
        $this->db->select('bl_refund.tanggal AS tanggal, SUM(bl_refund.nilai) AS total_refund');
        $this->db->from('bl_refund');
        $this->db->where('bl_refund.tanggal >=', $tanggal_awal);
        $this->db->where('bl_refund.tanggal <=', $tanggal_akhir);
        $this->db->group_by('bl_refund.tanggal');
        $refund = $this->db->get()->result_array();

        // Query Pengeluaran
        $this->db->select('bl_purchase.tanggal AS tanggal, SUM(bl_purchase.total_harga) AS total_pengeluaran');
        $this->db->from('bl_purchase');
        $this->db->where('bl_purchase.tanggal >=', $tanggal_awal);
        $this->db->where('bl_purchase.tanggal <=', $tanggal_akhir);
        $this->db->group_by('bl_purchase.tanggal');
        $pengeluaran = $this->db->get()->result_array();

        // Query Estimasi Gaji

        // Gaji Pokok
        $this->db->select('tanggal, SUM(total_gaji) AS total_gaji');
        $this->db->from('abs_rekap_absensi');
        $this->db->where('tanggal >=', $tanggal_awal);
        $this->db->where('tanggal <=', $tanggal_akhir);
        $this->db->group_by('tanggal');
        $gaji_pokok = $this->db->get()->result_array();

        // Lembur
        $this->db->select('tanggal, SUM(total_gaji_lembur) AS total_lembur');
        $this->db->from('abs_lembur');
        $this->db->where('tanggal >=', $tanggal_awal);
        $this->db->where('tanggal <=', $tanggal_akhir);
        $this->db->group_by('tanggal');
        $lembur = $this->db->get()->result_array();

        // Tambahan Lain
        $this->db->select('tanggal, SUM(nilai_tambahan) AS total_tambahan');
        $this->db->from('abs_tambahan_lain');
        $this->db->where('tanggal >=', $tanggal_awal);
        $this->db->where('tanggal <=', $tanggal_akhir);
        $this->db->group_by('tanggal');
        $tambahan_lain = $this->db->get()->result_array();

        // Potongan
        $this->db->select('tanggal, SUM(nilai) AS total_potongan');
        $this->db->from('abs_potongan');
        $this->db->where('tanggal >=', $tanggal_awal);
        $this->db->where('tanggal <=', $tanggal_akhir);
        $this->db->group_by('tanggal');
        $potongan = $this->db->get()->result_array();

        // Deposit (jenis = setor)
        $this->db->select('tanggal, SUM(nilai) AS total_deposit');
        $this->db->from('abs_deposit');
        $this->db->where('jenis', 'setor');
        $this->db->where('tanggal >=', $tanggal_awal);
        $this->db->where('tanggal <=', $tanggal_akhir);
        $this->db->group_by('tanggal');
        $deposit = $this->db->get()->result_array();

        // Kasbon (jenis = bayar)
        $this->db->select('tanggal, SUM(nilai) AS total_kasbon');
        $this->db->from('abs_kasbon');
        $this->db->where('jenis', 'bayar');
        $this->db->where('tanggal >=', $tanggal_awal);
        $this->db->where('tanggal <=', $tanggal_akhir);
        $this->db->group_by('tanggal');
        $kasbon = $this->db->get()->result_array();

        // Ambil total tunjangan, dihitung hanya sekali
        $this->db->select('SUM(tunjangan) AS total_tunjangan');
        $this->db->from('abs_pegawai');
        $tunjangan = $this->db->get()->row()->total_tunjangan ?? 0;

        // Gabungkan data berdasarkan tanggal
        $laporan = [];
        for ($date = strtotime($tanggal_awal); $date <= strtotime($tanggal_akhir); $date += 86400) {
            $tanggal = date('Y-m-d', $date);

            // Get each component values for the given date
            $total_penjualan = $this->get_value_by_date($penjualan, $tanggal, 'total_penjualan');
            $total_refund = $this->get_value_by_date($refund, $tanggal, 'total_refund');
            $total_pengeluaran = $this->get_value_by_date($pengeluaran, $tanggal, 'total_pengeluaran');
            $total_gaji = $this->get_value_by_date($gaji_pokok, $tanggal, 'total_gaji');
            $total_gaji_lembur = $this->get_value_by_date($lembur, $tanggal, 'total_lembur');
            $total_tambahan = $this->get_value_by_date($tambahan_lain, $tanggal, 'total_tambahan');
            $total_potongan = $this->get_value_by_date($potongan, $tanggal, 'total_potongan');
            $total_deposit = $this->get_value_by_date($deposit, $tanggal, 'total_deposit');
            $total_kasbon = $this->get_value_by_date($kasbon, $tanggal, 'total_kasbon');

            // Calculate total gaji (Salary) - Add tunjangan only for the first day of the month
            if ($tanggal == $tanggal_awal) {
                $total_gaji = $total_gaji + $tunjangan; // Add tunjangan only on the first day of the month
            }
            $total_gaji = $total_gaji + $total_gaji_lembur + $total_tambahan - $total_potongan - $total_deposit - $total_kasbon;

            // Calculate Pendapatan Kotor and Estimasi Pendapatan Final
            $pendapatan_kotor = $total_penjualan - $total_refund - $total_pengeluaran;
            $estimasi_pendapatan_final = $pendapatan_kotor - $total_gaji;

            $laporan[] = [
                'tanggal' => $tanggal,
                'penjualan' => $total_penjualan,
                'refund' => $total_refund,
                'pengeluaran' => $total_pengeluaran,
                'pendapatan_kotor' => $pendapatan_kotor,
                'estimasi_gaji' => $total_gaji,
                'estimasi_pendapatan_final' => $estimasi_pendapatan_final
            ];
        }

        return $laporan;
    }

    private function get_value_by_date($data, $tanggal, $key) {
        foreach ($data as $row) {
            if ($row['tanggal'] == $tanggal) {
                return $row[$key] ?? 0;
            }
        }
        return 0;
    }
}
