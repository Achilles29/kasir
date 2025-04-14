<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refund extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Refund_model');
        $this->load->model('Rekening_model');
    }

    public function index() {
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-d');

        $data['refunds'] = $this->Refund_model->get_filtered_refunds($start_date, $end_date);
        $data['rekening_list'] = $this->Rekening_model->get_all();
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['title'] = 'Refund';

        $this->load->view('templates/header', $data);
        $this->load->view('refund/index', $data);
        $this->load->view('templates/footer');
    }

    public function add() {
        $data = $this->input->post();
        $this->Refund_model->insert_refund($data);
        $this->session->set_flashdata('success', 'Data refund berhasil ditambahkan.');
        redirect('refund');
    }

    public function edit($id) { 
        $data = $this->input->post();
        $this->Refund_model->update_refund($id, $data);
        $this->session->set_flashdata('success', 'Data refund berhasil diperbarui.');
        redirect('refund');
    }

    public function delete($id) {
        $this->Refund_model->delete_refund($id);
        $this->session->set_flashdata('success', 'Data refund berhasil dihapus.');
        redirect('refund');
    }
    public function refund_umum() {
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-d');

        $data['refunds'] = $this->Refund_model->get_filtered_refunds($start_date, $end_date);
        $data['rekening_list'] = $this->Rekening_model->get_all();
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $this->load->view('templates/header', $data);
        $this->load->view('refund_umum', $data);
        $this->load->view('templates/footer');
    }


}
