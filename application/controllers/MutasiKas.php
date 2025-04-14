<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MutasiKas extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('MutasiKas_model');
        $this->load->model('Rekening_model');
    }

    public function index() {
        $data['title'] = 'Data Mutasi Kas';
        $data['mutasi_kas'] = $this->MutasiKas_model->get_all();
        $data['rekening'] = $this->Rekening_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('mutasi_kas/index', $data);
        $this->load->view('templates/footer');
    }

    public function add_mutasi_kas() {
        $data = [
            'tanggal' => $this->input->post('tanggal'),
            'bl_rekening_id' => $this->input->post('bl_rekening_id'),
            'jenis_mutasi' => $this->input->post('jenis_mutasi'),
            'jumlah' => $this->input->post('jumlah'),
            'keterangan' => $this->input->post('keterangan'),
        ];

        if ($this->MutasiKas_model->insert($data)) {
            $this->session->set_flashdata('success', 'Mutasi kas berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan mutasi kas.');
        }

        redirect('mutasi_kas');
    }

    public function update_mutasi_kas() {
        $id = $this->input->post('id');
        $data = [
            'tanggal' => $this->input->post('tanggal'),
            'bl_rekening_id' => $this->input->post('bl_rekening_id'),
            'jenis_mutasi' => $this->input->post('jenis_mutasi'),
            'jumlah' => $this->input->post('jumlah'),
            'keterangan' => $this->input->post('keterangan'),
        ];

        if ($this->MutasiKas_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Mutasi kas berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui mutasi kas.');
        }

        redirect('mutasi_kas');
    }

    public function delete_mutasi_kas($id) {
        if ($this->MutasiKas_model->delete($id)) {
            $this->session->set_flashdata('success', 'Mutasi kas berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus mutasi kas.');
        }

        redirect('mutasi_kas');
    }
}
