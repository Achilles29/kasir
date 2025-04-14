<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kas extends CI_Controller {
    public function __construct() {
        parent::__construct();
        check_login(); // pastikan user sudah login
        $this->load->helper('url');
        $this->load->model('Kas_model');
        $this->load->model('MutasiKas_model');
        $this->load->model('Rekening_model');
        $this->load->model('Purchase_model');
    }

    public function index() {
        $data['title'] = 'Data Kas Awal';
        $data['kas'] = $this->Kas_model->get_all();
        $data['rekening'] = $this->Rekening_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('kas/index', $data);
        $this->load->view('templates/footer');
    }

    public function add_kas() {
        $data = [
            'bl_rekening_id' => $this->input->post('bl_rekening_id'),
            'tanggal' => $this->input->post('tanggal'),
            'jumlah' => $this->input->post('jumlah'),
        ];

        if ($this->Kas_model->insert($data)) {
            $this->session->set_flashdata('success', 'Data kas berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data kas.');
        }

        redirect('kas');
    }

    public function update_kas() {
        $id = $this->input->post('id');
        $data = [
            'bl_rekening_id' => $this->input->post('bl_rekening_id'),
            'tanggal' => $this->input->post('tanggal'),
            'jumlah' => $this->input->post('jumlah'),
        ];

        if ($this->Kas_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Data kas berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data kas.');
        }

        redirect('kas');
    }

    public function delete_kas($id) {
        if ($this->Kas_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data kas berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data kas.');
        }

        redirect('kas');
    }

    // public function add_mutasi_kas() {
    //     $data = [
    //         'tanggal' => $this->input->post('tanggal'),
    //         'bl_rekening_id' => $this->input->post('bl_rekening_id'),
    //         'jenis_mutasi' => $this->input->post('jenis_mutasi'),
    //         'jumlah' => $this->input->post('jumlah'),
    //         'keterangan' => $this->input->post('keterangan'),
    //     ];

    //     if ($this->MutasiKas_model->insert($data)) {
    //         $this->session->set_flashdata('success', 'Data mutasi kas berhasil ditambahkan.');
    //     } else {
    //         $this->session->set_flashdata('error', 'Gagal menambahkan data mutasi kas.');
    //     }

    //     redirect('kas');
    // }


public function rekapitulasi_metode_pembayaran() {
    $this->load->model('Purchase_model');

    $tanggal_awal = $this->input->get('tanggal_awal') ?? date('Y-m-01');
    $tanggal_akhir = $this->input->get('tanggal_akhir') ?? date('Y-m-d');

    $rekapitulasi_data = $this->Purchase_model->get_rekapitulasi_metode_pembayaran($tanggal_awal, $tanggal_akhir);

    
    // Format data untuk tampilan tabel
    $rekapitulasi = [];
    foreach ($rekapitulasi_data as $row) {
        $tanggal = $row['tanggal'];
        $rekening = $row['nama_rekening'];
        $total = $row['total'];

        if (!isset($rekapitulasi[$tanggal])) {
            $rekapitulasi[$tanggal] = [];
        }

        $rekapitulasi[$tanggal][$rekening] = $total;
    }

    // Ambil daftar rekening
    $this->load->model('Rekening_model');
    $rekening_list = $this->Rekening_model->get_all();

    $data = [
        'rekapitulasi' => $rekapitulasi,
        'rekening_list' => $rekening_list,
        'tanggal_awal' => $tanggal_awal,
        'tanggal_akhir' => $tanggal_akhir,
    ];
    $data['title'] = 'Rekap Pembayaran Purchase';

    $this->load->view('templates/header', $data);
    $this->load->view('kas/rekapitulasi_metode_pembayaran', $data);
    $this->load->view('templates/footer');
}

public function rekapitulasi_penjualan() {
    $this->load->model('Penjualan_model');

    $tanggal_awal = $this->input->get('tanggal_awal') ?? date('Y-m-01');
    $tanggal_akhir = $this->input->get('tanggal_akhir') ?? date('Y-m-d');

    $rekapitulasi_data = $this->Penjualan_model->get_rekapitulasi_penjualan($tanggal_awal, $tanggal_akhir);

    // Format data untuk tampilan tabel
    $rekapitulasi = [];
    foreach ($rekapitulasi_data as $row) {
        $tanggal = $row['tanggal'];
        $rekening = $row['nama_rekening'];
        $total = $row['total'];

        if (!isset($rekapitulasi[$tanggal])) {
            $rekapitulasi[$tanggal] = [];
        }

        $rekapitulasi[$tanggal][$rekening] = $total;
    }

    // Ambil daftar rekening
    $this->load->model('Rekening_model');
    $rekening_list = $this->Rekening_model->get_all();

    $data = [
        'rekapitulasi' => $rekapitulasi,
        'rekening_list' => $rekening_list,
        'tanggal_awal' => $tanggal_awal,
        'tanggal_akhir' => $tanggal_akhir,
    ];
    $data['title'] = 'Rekap Rekening Sales';

    $this->load->view('templates/header', $data);
    $this->load->view('kas/rekapitulasi_penjualan', $data);
    $this->load->view('templates/footer');
}


}
