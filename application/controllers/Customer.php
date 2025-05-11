<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("Customer_model");
    }
    public function update_poin_status()
    {
        $this->load->model('Customer_model');
        $this->Customer_model->update_poin_kadaluarsa();
        echo "Status poin kedaluwarsa diperbarui dan disinkronkan.";
    }

    public function index() {
        $data['title'] = "Daftar Pelanggan";
        $this->load->view("templates/header", $data);
        $this->load->view("customer/index", $data);
        $this->load->view("templates/footer");
    }
public function load_data() {
    $page = $this->input->get('page') ?: 1;
    $per_page = $this->input->get('per_page') ?: 10;
    $search = $this->input->get('search');
    $offset = ($page - 1) * $per_page;

    $total_rows = $this->Customer_model->count_customers($search);

    $this->load->library('pagination');

    $config['base_url'] = '#';
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $per_page;
    $config['use_page_numbers'] = true;
    $config['page_query_string'] = false;

    $config['full_tag_open'] = '';
    $config['full_tag_close'] = '';
    $config['first_link'] = false;
    $config['last_link'] = false;
    $config['next_link'] = '&raquo;';
    $config['prev_link'] = '&laquo;';

    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
    $config['cur_tag_close'] = '</a></li>';

    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';

    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';

    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';

    $config['attributes'] = ['class' => 'page-link'];

    $this->pagination->initialize($config);
    $this->pagination->cur_page = $page; // 🟢 PENTING!

    $pagination = $this->pagination->create_links();

    // 🧠 Tambahkan atribut data-page secara manual
    $pagination = preg_replace_callback('/<a([^>]*)>(.*?)<\/a>/', function ($match) {
        $href = $match[1];
        $label = trim(strip_tags($match[2]));

        // Hanya proses angka atau simbol panah
        if (is_numeric($label) || $label === '»' || $label === '«') {
            return '<a' . $href . ' data-page="' . $label . '">' . $label . '</a>';
        }
        return $match[0];
    }, $pagination);

    $data = $this->Customer_model->get_all_customers($per_page, $offset, $search);

    echo json_encode([
        'data' => $data,
        'pagination' => $pagination
    ]);
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

public function poin($id) {
    $this->load->model('Customer_model');
    $data['title'] = "Riwayat Poin Pelanggan";
    $data['customer'] = $this->Customer_model->get_customer_by_id($id);
    $data['poin'] = $this->Customer_model->get_riwayat_poin($id);
    $data['poin_aktif'] = $this->Customer_model->get_total_poin($id);
    $data['poin_terpakai'] = $this->Customer_model->get_total_poin_terpakai($id);
    $data['poin_kadaluarsa'] = $this->Customer_model->get_total_poin_kadaluarsa($id);
    $data['poin_akan_kadaluarsa'] = $this->Customer_model->get_poin_akan_kadaluarsa($id);

    $this->load->view('templates/header', $data);
    $this->load->view('customer/poin', $data);
    $this->load->view('templates/footer');
}


public function get_transaksi_detail_ajax() {
    $customer_id = $this->input->get('customer_id');
    $start = $this->input->get('start');
    $end = $this->input->get('end');
    $search = $this->input->get('search');

    $this->load->model('Customer_model');
    $result = $this->Customer_model->get_transaksi_with_detail($customer_id, $start, $end, $search);

    echo json_encode($result);
}

public function stamp($customer_id)
{
    $this->load->model('Customer_model');
    $data['title'] = 'Riwayat Stamp';
    $data['customer'] = $this->Customer_model->get_customer_by_id($customer_id);
    $data['stamp'] = $this->Customer_model->get_stamp_by_customer($customer_id);

    $this->load->view('templates/header', $data);
    $this->load->view('customer/stamp', $data);
    $this->load->view('templates/footer');
}



}