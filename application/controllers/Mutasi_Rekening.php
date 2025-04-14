<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi_Rekening extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Mutasi_Rekening_model');
        $this->load->model('Rekening_model'); // Assuming there's a Rekening model
    }

    public function index() {
        $bulan = $this->input->get('bulan') ?: date('Y-m');
        $data['bulan'] = $bulan;
        
        // Fetch mutasi rekening data with rekening names
        $data['mutasi_rekening_data'] = $this->Mutasi_Rekening_model->get_mutasi_rekening_data($bulan);
        $data['rekening_options'] = $this->Rekening_model->get_rekening_list();
        $this->load->view('templates/header', $data);
        $this->load->view('mutasi_rekening/index', $data);
        $this->load->view('templates/footer');
    }

public function add() {
    // Fetch the list of rekening from the Rekening model
    $data['rekening_options'] = $this->Rekening_model->get_rekening_list();

    // Get the input data for the new mutasi rekening
    $data_input = [
        'tanggal' => $this->input->post('tanggal'),
        'bl_rekening_id_sumber' => $this->input->post('bl_rekening_id_sumber'),
        'bl_rekening_id_tujuan' => $this->input->post('bl_rekening_id_tujuan'),
        'jumlah' => $this->input->post('jumlah'),
        'keterangan' => $this->input->post('keterangan')
    ];

    // Insert the data into the database
    $this->Mutasi_Rekening_model->add_mutasi_rekening($data_input);

    redirect('mutasi_rekening');
}

public function edit($id) {
    // Fetch the list of rekening from the Rekening model
    $data['rekening_options'] = $this->Rekening_model->get_rekening_list();

    // Get the input data for the specific record
    $data_input = [
        'tanggal' => $this->input->post('tanggal'),
        'bl_rekening_id_sumber' => $this->input->post('bl_rekening_id_sumber'),
        'bl_rekening_id_tujuan' => $this->input->post('bl_rekening_id_tujuan'),
        'jumlah' => $this->input->post('jumlah'),
        'keterangan' => $this->input->post('keterangan')
    ];

    // Update the record
    $this->Mutasi_Rekening_model->update_mutasi_rekening($id, $data_input);

    redirect('mutasi_rekening');
}

    public function delete($id) {
        // Delete the specific record
        $this->Mutasi_Rekening_model->delete_mutasi_rekening($id);

        redirect('mutasi_rekening');
    }
}
