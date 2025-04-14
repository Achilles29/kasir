<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class RekapRekening_model extends CI_Model {
    public function get_saldo_awal($tahun, $bulan) {
        $this->db->select('bl_rekening_id AS rekening_id, SUM(jumlah) AS saldo_awal');
        $this->db->from('bl_kas');
        $this->db->where('YEAR(tanggal) <', $tahun);
        $this->db->or_where('(YEAR(tanggal) = '.$tahun.' AND MONTH(tanggal) < '.$bulan.')');
        $this->db->group_by('bl_rekening_id');
        $query = $this->db->get();
        $result = $query->result_array();

        $saldo_awal = [];
        foreach ($result as $row) {
            $saldo_awal[$row['rekening_id']] = $row['saldo_awal'];
        }
        return $saldo_awal;
    }

    public function get_transaksi_harian($bulan, $tahun) {
        // Penjualan
        $this->db->select('tanggal, rekening_id, SUM(penjualan) AS total_penjualan');
        $this->db->from('bl_penjualan_majoo');
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->group_by(['tanggal', 'rekening_id']);
        $penjualan = $this->db->get()->result_array();

        // Mutasi
        $this->db->select('tanggal, bl_rekening_id, jenis_mutasi, SUM(jumlah) AS total_mutasi');
        $this->db->from('bl_mutasi_kas');
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->group_by(['tanggal', 'bl_rekening_id', 'jenis_mutasi']);
        $mutasi = $this->db->get()->result_array();

        // Pembelian
        $this->db->select('tanggal, metode_pembayaran AS rekening_id, SUM(total_harga) AS total_pembelian');
        $this->db->from('bl_purchase');
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->group_by(['tanggal', 'metode_pembayaran']);
        $pembelian = $this->db->get()->result_array();

        return [
            'penjualan' => $penjualan,
            'mutasi' => $mutasi,
            'pembelian' => $pembelian
        ];
    }
    public function get_rekening_list() {
        $this->db->select('id, nama_rekening');
        $this->db->from('bl_rekening');
        return $this->db->get()->result_array();
    }

    public function get_rekap_data_by_date($tahun, $bulan) {
        $this->db->select('tanggal, rekening_id, nilai');
        $this->db->from('bl_rekap_rekening');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where('MONTH(tanggal)', $bulan);
        $query = $this->db->get();
        $result = $query->result_array();

        $rekap_data = [];
        foreach ($result as $row) {
            $rekap_data[$row['tanggal']][$row['rekening_id']] = $row['nilai'];
        }
        return $rekap_data;
    }

public function get_rekap_data($start_date, $end_date) {
    $this->db->select('r.id AS rekening_id, r.nama_rekening, t.tanggal, 
        SUM(IF(t.jenis_transaksi = "penjualan", t.nilai, 0)) AS penjualan,
        SUM(IF(t.jenis_transaksi = "pembelian", t.nilai, 0)) AS pembelian,
        SUM(IF(t.jenis_transaksi = "mutasi_masuk", t.nilai, 0)) AS mutasi_masuk,
        SUM(IF(t.jenis_transaksi = "mutasi_keluar", t.nilai, 0)) AS mutasi_keluar,
        SUM(IF(t.jenis_transaksi = "refund", t.nilai, 0)) AS refund,
        SUM(IF(t.jenis_transaksi = "mutasi_rekening_tujuan", t.nilai, 0)) AS mutasi_rekening_tujuan,
        SUM(IF(t.jenis_transaksi = "mutasi_rekening_sumber", t.nilai, 0)) AS mutasi_rekening_sumber
    ');
    $this->db->from('bl_rekening r');
    
    // Combine all transactions (penjualan, pembelian, mutasi, refund)
    $this->db->join('(SELECT 
                        rekening_id AS rekening_id,
                        tanggal, 
                        "penjualan" AS jenis_transaksi, 
                        penjualan AS nilai
                      FROM bl_penjualan_majoo
                      UNION ALL
                      SELECT 
                        metode_pembayaran AS rekening_id,
                        tanggal, 
                        "pembelian" AS jenis_transaksi, 
                        total_harga AS nilai
                      FROM bl_purchase
                      UNION ALL
                      SELECT 
                        bl_rekening_id AS rekening_id,
                        tanggal, 
                        "mutasi_masuk" AS jenis_transaksi, 
                        jumlah AS nilai
                      FROM bl_mutasi_kas
                      WHERE jenis_mutasi = "masuk"
                      UNION ALL
                      SELECT 
                        bl_rekening_id AS rekening_id,
                        tanggal, 
                        "mutasi_keluar" AS jenis_transaksi, 
                        jumlah AS nilai
                      FROM bl_mutasi_kas
                      WHERE jenis_mutasi = "keluar"
                      UNION ALL
                      SELECT 
                        rekening AS rekening_id,
                        tanggal,
                        "refund" AS jenis_transaksi,
                        nilai AS nilai
                      FROM bl_refund
                      UNION ALL
                      SELECT 
                        bl_rekening_id_sumber AS rekening_id,
                        tanggal, 
                        "mutasi_rekening_sumber" AS jenis_transaksi, 
                        jumlah AS nilai
                      FROM bl_mutasi_kas_rekening
                      UNION ALL
                      SELECT 
                        bl_rekening_id_tujuan AS rekening_id,
                        tanggal, 
                        "mutasi_rekening_tujuan" AS jenis_transaksi, 
                        jumlah AS nilai
                      FROM bl_mutasi_kas_rekening
                      ) t', 
        'r.id = t.rekening_id', 
        'left');
    
    $this->db->where('t.tanggal >=', $start_date);
    $this->db->where('t.tanggal <=', $end_date);
    $this->db->group_by(['r.id', 't.tanggal']);
    $this->db->order_by('t.tanggal', 'ASC');
    return $this->db->get()->result_array();
}

public function get_rekap_data_bulan_sebelumnya() {
    // Hitung bulan dan tahun sebelumnya
    $previous_month = date('m', strtotime('first day of last month'));
    $previous_year = date('Y', strtotime('first day of last month'));

    // Tentukan tanggal awal dan akhir bulan sebelumnya
    $start_date = "$previous_year-$previous_month-01";
    $end_date = date('Y-m-t', strtotime($start_date));

    return $this->get_rekap_data($start_date, $end_date);
}

    public function get_saldo_awal_tanggal_1($tahun, $bulan) {
        $tanggal_awal = sprintf('%04d-%02d-01', $tahun, $bulan);

        $this->db->select('bl_rekening_id AS rekening_id, SUM(jumlah) AS saldo_awal');
        $this->db->from('bl_kas');
        $this->db->where('tanggal', $tanggal_awal);
        $this->db->group_by('bl_rekening_id');
        $query = $this->db->get();
        $result = $query->result_array();

        $saldo_awal = [];
        foreach ($result as $row) {
            $saldo_awal[$row['rekening_id']] = $row['saldo_awal'];
        }
        return $saldo_awal;
    }
    // public function insert_rekap_data($data) {
    //     $insert_query = $this->db->insert_string('bl_rekap_rekening', $data) . 
    //                     ' ON DUPLICATE KEY UPDATE nilai = VALUES(nilai)';
    //     $this->db->query($insert_query);
    // }
public function insert_rekap_data($data) {
    // Pastikan hanya memasukkan kolom yang ada di tabel
    $data_to_insert = [
        'rekening_id' => $data['rekening_id'],
        'tanggal' => $data['tanggal'],
        'nilai' => $data['nilai'], // Pastikan nilai sudah dihitung sebelumnya
    ];
    
    $insert_query = $this->db->insert_string('bl_rekap_rekening', $data_to_insert) . 
                    ' ON DUPLICATE KEY UPDATE nilai = VALUES(nilai)';
    $this->db->query($insert_query);
}

public function get_existing_rekap_data($start_date, $end_date) {
    $this->db->select('tanggal, rekening_id, nilai');
    $this->db->from('bl_rekap_rekening');
    $this->db->where('tanggal >=', $start_date);
    $this->db->where('tanggal <=', $end_date);
    return $this->db->get()->result_array();
}

public function delete_rekap_row($tanggal, $rekening_id) {
    $this->db->where('tanggal', $tanggal);
    $this->db->where('rekening_id', $rekening_id);
    $this->db->delete('bl_rekap_rekening');
}

public function update_rekap_data($data) {
    $this->db->where('tanggal', $data['tanggal']);
    $this->db->where('rekening_id', $data['rekening_id']);
    $this->db->update('bl_rekap_rekening', ['nilai' => $data['nilai']]);
}

public function generate_saldo_awal($bulan, $tahun, $bulan_berikutnya, $tahun_berikutnya)
{
    $tanggal_awal = "$tahun-$bulan-01";
    $tanggal_akhir = date("Y-m-t", strtotime($tanggal_awal));

    $rekening_list = $this->db->get('bl_rekening')->result_array();

    foreach ($rekening_list as $rekening) {
        $rekening_id = $rekening['id'];

        // Hitung saldo awal dari tabel bl_kas
        $saldo_awal = $this->db->select_sum('jumlah', 'saldo_awal')
            ->where('bl_rekening_id', $rekening_id)
            ->where('tanggal', $tanggal_awal)
            ->get('bl_kas')
            ->row()
            ->saldo_awal ?? 0;

        // Hitung total rekap dalam bulan berjalan
        $rekap_bulanan = $this->db->select_sum('nilai', 'total_rekap')
            ->from('bl_rekap_rekening')
            ->where('rekening_id', $rekening_id)
            ->where('tanggal >=', $tanggal_awal)
            ->where('tanggal <=', $tanggal_akhir)
            ->get()
            ->row()
            ->total_rekap ?? 0;

        // Hitung saldo akumulasi
        $saldo_akhir = $saldo_awal + $rekap_bulanan;

        // Simpan atau timpa saldo awal untuk bulan berikutnya
        $data = [
            'tanggal' => "$tahun_berikutnya-$bulan_berikutnya-01",
            'bl_rekening_id' => $rekening_id,
            'jumlah' => $saldo_akhir,
        ];

        $this->db->replace('bl_kas', $data); // Gunakan REPLACE untuk menimpa data jika ada
    }

    return true;
}
public function generate_saldo_awal_bulan_ini($bulan, $tahun, $bulan_sebelumnya, $tahun_sebelumnya)
{
    $tanggal_awal_bulan_ini = "$tahun-$bulan-01";
    $tanggal_akhir_bulan_sebelumnya = date("Y-m-t", strtotime("$tahun_sebelumnya-$bulan_sebelumnya-01"));

    $rekening_list = $this->db->get('bl_rekening')->result_array();

    foreach ($rekening_list as $rekening) {
        $rekening_id = $rekening['id'];

        // Ambil saldo akhir dari bulan sebelumnya
        $saldo_akhir_bulan_sebelumnya = $this->db->select_sum('nilai', 'saldo_akhir')
            ->from('bl_rekap_rekening')
            ->where('rekening_id', $rekening_id)
            ->where('tanggal', $tanggal_akhir_bulan_sebelumnya)
            ->get()
            ->row()
            ->saldo_akhir ?? 0;

        // Simpan atau timpa saldo awal untuk bulan ini
        $data = [
            'tanggal' => $tanggal_awal_bulan_ini,
            'bl_rekening_id' => $rekening_id,
            'jumlah' => $saldo_akhir_bulan_sebelumnya,
        ];

        $this->db->replace('bl_kas', $data); // Gunakan REPLACE untuk menimpa data jika ada
    }

    return true;
}


}

