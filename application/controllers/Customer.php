<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("Customer_model");
    }

    public function index() {
        $data['title'] = "Daftar Pelanggan";
        $this->load->view("templates/header", $data);
        $this->load->view("customer/index", $data);
        $this->load->view("templates/footer");
    }

    // Load data pelanggan dengan AJAX
    public function load_customers() {
        $limit = $this->input->get("limit") ?? 10;
        $start = $this->input->get("start") ?? 0;
        $search = $this->input->get("search") ?? '';

        $data['customers'] = $this->Customer_model->get_all_customers($limit, $start, $search);
        $data['total'] = $this->Customer_model->count_customers($search);

        echo json_encode($data);
    }
public function get_customer($id) {
    $customer = $this->Customer_model->get_customer_by_id($id);
    echo json_encode($customer);
}

public function detail($id) {
    $data['title'] = "Detail Pelanggan";
    $data['customer'] = $this->Customer_model->get_customer_by_id($id);
    $this->load->view("templates/header", $data);
    $this->load->view("customer/detail", $data);
    $this->load->view("templates/footer");
}

private function _upload_foto() {
    if (!empty($_FILES['foto']['name'])) {
        $nama_depan = strtolower(explode(" ", $this->input->post("nama"))[0]);
        $telepon = preg_replace('/\D/', '', $this->input->post("telepon"));
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $filename = $nama_depan . '_' . $telepon . '.' . $ext;

        $config['upload_path']   = './uploads/foto_pelanggan/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = $filename;
        $config['overwrite']     = TRUE;
        $config['encrypt_name']  = FALSE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('foto')) {
            return $this->upload->data('file_name');
        }
    }
    return null;
}

public function add_customer() {
    $foto = $this->_upload_foto();
    $data = [
        'nama'           => $this->input->post('nama'),
        'tanggal_lahir'  => $this->input->post('tanggal_lahir'),
        'jenis_kelamin'  => $this->input->post('jenis_kelamin'),
        'telepon'        => $this->input->post('telepon'),
        'alamat'         => $this->input->post('alamat'),
        'email'          => $this->input->post('email'),
        'foto'           => $foto
    ];
    $this->Customer_model->insert_customer($data);
    echo json_encode(['status' => 'success']);
}

public function edit_customer($id) {
    $existing = $this->Customer_model->get_customer_by_id($id);
    $fotoBaru = $this->_upload_foto();

    if ($fotoBaru && $existing['foto']) {
        @unlink('./uploads/foto_pelanggan/' . $existing['foto']);
    }

    $data = [
        'nama'           => $this->input->post('nama'),
        'tanggal_lahir'  => $this->input->post('tanggal_lahir'),
        'jenis_kelamin'  => $this->input->post('jenis_kelamin'),
        'telepon'        => $this->input->post('telepon'),
        'alamat'         => $this->input->post('alamat'),
        'email'          => $this->input->post('email'),
    ];
    if ($fotoBaru) $data['foto'] = $fotoBaru;

    $this->Customer_model->update_customer($id, $data);
    echo json_encode(['status' => 'success']);
}

public function delete_customer($id) {
    $existing = $this->Customer_model->get_customer_by_id($id);
    if ($existing['foto']) {
        @unlink('./uploads/foto_pelanggan/' . $existing['foto']);
    }
    $this->Customer_model->delete_customer($id);
    echo json_encode(['status' => 'success']);
}

public function transaksi($id) {
    $data['title'] = "Transaksi Pelanggan";
    $data['customer'] = $this->Customer_model->get_customer_by_id($id);
    $this->load->view("templates/header", $data);
    $this->load->view("customer/transaksi", $data);
    $this->load->view("templates/footer");
}

public function get_transaksi_ajax() {
    $customer_id = $this->input->get('customer_id');
    $start = $this->input->get('start');
    $end = $this->input->get('end');
    $search = $this->input->get('search');

    $data = $this->Customer_model->get_transaksi_by_customer($customer_id, $start, $end, $search);
    echo json_encode($data);
}


}
