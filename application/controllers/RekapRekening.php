<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RekapRekening extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('RekapRekening_model');
        $this->load->model('Rekening_model');
    }

public function index() {
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');

        // Dapatkan daftar rekening
        $rekening_list = $this->RekapRekening_model->get_rekening_list();

        // Dapatkan jumlah hari dalam bulan
        $jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $tanggal_list = [];
        for ($i = 1; $i <= $jumlah_hari; $i++) {
            $tanggal_list[] = sprintf('%04d-%02d-%02d', $tahun, $bulan, $i);
        }

        // Ambil saldo awal dari tabel bl_kas pada tanggal 1 bulan bersangkutan
        $saldo_awal = $this->RekapRekening_model->get_saldo_awal_tanggal_1($tahun, $bulan);

        // Ambil data rekap dari database
        $rekap_data = $this->RekapRekening_model->get_rekap_data_by_date($tahun, $bulan);

        // Inisialisasi saldo awal untuk akumulasi
        $akumulatif_saldo = [];
        foreach ($rekening_list as $rekening) {
            $akumulatif_saldo[$rekening['id']] = $saldo_awal[$rekening['id']] ?? 0;
        }

        // Susun data rekap per tanggal
        $rekap = [];
        $rekap['Saldo Awal'] = $akumulatif_saldo; // Tambahkan baris saldo awal

        foreach ($tanggal_list as $tanggal) {
            $rekap[$tanggal] = [];
            foreach ($rekening_list as $rekening) {
                $rekening_id = $rekening['id'];
                $nilai_harian = $rekap_data[$tanggal][$rekening_id] ?? 0;
                $akumulatif_saldo[$rekening_id] += $nilai_harian;
                $rekap[$tanggal][$rekening_id] = $akumulatif_saldo[$rekening_id];
            }
        }

        $data['rekap'] = $rekap;
        $data['rekening_list'] = $rekening_list;
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $this->load->view('templates/header', $data);
        $this->load->view('rekap_rekening/index', $data);
        $this->load->view('templates/footer');
    }
public function generate_rekap() {
    $start_date = $this->input->get('start_date') ?: date('Y-m-01');
    $end_date = $this->input->get('end_date') ?: date('Y-m-t');

    // Ambil data rekap baru
    $rekap_data_baru = $this->RekapRekening_model->get_rekap_data($start_date, $end_date);

    // Ambil data rekap lama dari database
    $rekap_data_lama = $this->RekapRekening_model->get_existing_rekap_data($start_date, $end_date);

    // Buat array indexed untuk kemudahan pengecekan
    $data_baru_indexed = [];
    foreach ($rekap_data_baru as $row) {
        $key = $row['tanggal'] . '-' . $row['rekening_id'];
        $data_baru_indexed[$key] = $row;
    }

    $data_lama_indexed = [];
    foreach ($rekap_data_lama as $row) {
        $key = $row['tanggal'] . '-' . $row['rekening_id'];
        $data_lama_indexed[$key] = $row;
    }

    // Periksa dan hapus data lama yang tidak ada di data baru
    foreach ($data_lama_indexed as $key => $row_lama) {
        if (!isset($data_baru_indexed[$key])) {
            $this->RekapRekening_model->delete_rekap_row($row_lama['tanggal'], $row_lama['rekening_id']);
        }
    }

    // Simpan atau perbarui data baru ke database
    foreach ($rekap_data_baru as $row_baru) {
        $data_to_insert = [
            'rekening_id' => $row_baru['rekening_id'],
            'tanggal' => $row_baru['tanggal'],
            'nilai' => $row_baru['penjualan'] 
                      + $row_baru['mutasi_masuk'] 
                      - $row_baru['mutasi_keluar'] 
                      - $row_baru['pembelian'] 
                      - $row_baru['refund']
                      + $row_baru['mutasi_rekening_tujuan'] 
                      - $row_baru['mutasi_rekening_sumber'],
        ];

        $this->RekapRekening_model->insert_rekap_data($data_to_insert);
    }

    echo json_encode(['success' => true, 'message' => 'Data rekap berhasil di-generate.']);
}
   
public function generate_rekap_bulan_sebelumnya() {
    // Hitung bulan dan tahun sebelumnya
    $previous_month = date('m', strtotime('first day of last month'));
    $previous_year = date('Y', strtotime('first day of last month'));

    // Tentukan tanggal awal dan akhir bulan sebelumnya
    $start_date = "$previous_year-$previous_month-01";
    $end_date = date('Y-m-t', strtotime($start_date));

    // Ambil data rekap baru untuk bulan sebelumnya
    $rekap_data_baru = $this->RekapRekening_model->get_rekap_data($start_date, $end_date);

    // Ambil data rekap lama dari database
    $rekap_data_lama = $this->RekapRekening_model->get_existing_rekap_data($start_date, $end_date);

    // Buat array indexed untuk kemudahan pengecekan
    $data_baru_indexed = [];
    foreach ($rekap_data_baru as $row) {
        $key = $row['tanggal'] . '-' . $row['rekening_id'];
        $data_baru_indexed[$key] = $row;
    }

    $data_lama_indexed = [];
    foreach ($rekap_data_lama as $row) {
        $key = $row['tanggal'] . '-' . $row['rekening_id'];
        $data_lama_indexed[$key] = $row;
    }

    // Periksa dan hapus data lama yang tidak ada di data baru
    foreach ($data_lama_indexed as $key => $row_lama) {
        if (!isset($data_baru_indexed[$key])) {
            $this->RekapRekening_model->delete_rekap_row($row_lama['tanggal'], $row_lama['rekening_id']);
        }
    }

    // Simpan atau perbarui data baru ke database
    foreach ($rekap_data_baru as $row_baru) {
        $data_to_insert = [
            'rekening_id' => $row_baru['rekening_id'],
            'tanggal' => $row_baru['tanggal'],
            'nilai' => $row_baru['penjualan'] 
                      + $row_baru['mutasi_masuk'] 
                      - $row_baru['mutasi_keluar'] 
                      - $row_baru['pembelian'] 
                      - $row_baru['refund']
                      + $row_baru['mutasi_rekening_tujuan'] 
                      - $row_baru['mutasi_rekening_sumber'],
        ];

        $this->RekapRekening_model->insert_rekap_data($data_to_insert);
    }

    echo json_encode(['success' => true, 'message' => 'Rekap bulan sebelumnya berhasil digenerate.']);
}


public function generate_saldo_awal()
{
    $input = json_decode(file_get_contents('php://input'), true);
    $bulan = $input['bulan'];
    $tahun = $input['tahun'];

    // Hitung bulan dan tahun berikutnya
    $bulan_berikutnya = $bulan == 12 ? 1 : $bulan + 1;
    $tahun_berikutnya = $bulan == 12 ? $tahun + 1 : $tahun;

    $this->load->model('SaldoKas_model');
    $result = $this->RekapRekening_model->generate_saldo_awal($bulan, $tahun, $bulan_berikutnya, $tahun_berikutnya);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Saldo awal berhasil digenerate.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal generate saldo awal.']);
    }
}
public function generate_saldo_awal_bulan_ini()
{
    $input = json_decode(file_get_contents('php://input'), true);
    $bulan = $input['bulan'];
    $tahun = $input['tahun'];

    // Hitung bulan dan tahun sebelumnya
    $bulan_sebelumnya = $bulan == 1 ? 12 : $bulan - 1;
    $tahun_sebelumnya = $bulan == 1 ? $tahun - 1 : $tahun;

    $this->load->model('SaldoKas_model');
    $result = $this->RekapRekening_model->generate_saldo_awal_bulan_ini($bulan, $tahun, $bulan_sebelumnya, $tahun_sebelumnya);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Saldo awal bulan ini berhasil digenerate.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal generate saldo awal bulan ini.']);
    }
}

}
