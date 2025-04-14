<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Setting_model');
    }

    public function index()
    {
        $data['title'] = "Manajemen Struk";
        $data['divisi'] = $this->Setting_model->get_all_divisi();
        $data['struk']  = $this->Setting_model->get_data_struk(); // ambil info outlet + logo
        // $data['struk'] = $this->Setting_model->get_all_struk_tampilan();
//        $data['struk'] = $this->db->get('pr_struk')->row_array(); // Ambil data dari tabel pr_struk

        $this->load->view('templates/header', $data);
        $this->load->view('setting/index', $data);
        $this->load->view('templates/footer');
    }

    public function form_data_struk()
    {
        $data['title'] = "Data Perusahaan";
        $data['struk'] = $this->Setting_model->get_data_struk();
        $this->load->view('templates/header', $data);
        $this->load->view('setting/form_data_struk', $data);
        $this->load->view('templates/footer');
    }

public function simpan_data_struk()
{
    $data = [
        'nama_outlet'     => $this->input->post('nama_outlet'),
        'alamat'          => $this->input->post('alamat'),
        'email'           => $this->input->post('email'),
        'no_telepon'      => $this->input->post('no_telepon'),
        'custom_header'   => $this->input->post('custom_header'),
        'custom_footer'   => $this->input->post('custom_footer'),
        'updated_at'      => date('Y-m-d H:i:s')
    ];

    // ✅ Upload logo jika ada
    if (!empty($_FILES['logo']['name'])) {
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png|webp';
        $config['file_name']     = 'logo_' . time();
        $config['overwrite']     = true;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('logo')) {
            $uploaded = $this->upload->data();
            $data['logo'] = $uploaded['file_name'];
        } else {
            echo json_encode(['status' => 'error', 'message' => $this->upload->display_errors()]);
            return;
        }
    }

    // Cek jika sudah ada, update. Jika belum, insert
    $cek = $this->db->get('pr_struk')->row();
    if ($cek) {
        $this->db->update('pr_struk', $data);
    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('pr_struk', $data);
    }

    redirect('setting');
}

    public function form_tampilan_struk($divisi_id)
    {
        $data['title'] = "Tampilan Struk Divisi";
        $data['divisi'] = $this->Setting_model->get_divisi($divisi_id);
        $data['tampilan'] = $this->Setting_model->get_tampilan_struk($divisi_id);
        $this->load->view('templates/header', $data);
        $this->load->view('setting/form_tampilan_struk', $data);
        $this->load->view('templates/footer');
    }

public function simpan_tampilan_struk()
{
    $post = $this->input->post();

    $all_fields = [
        'show_logo', 'show_outlet', 'show_alamat', 'show_no_telepon',
        'show_custom_header', 'show_invoice', 'show_kasir_order', 'show_kasir_bayar',
        'show_no_transaksi', 'show_customer', 'show_nomor_meja',
        'show_waktu_order', 'show_waktu_bayar', 'show_custom_footer'
    ];

    $data = ['pr_divisi_id' => $post['pr_divisi_id']];
    foreach ($all_fields as $field) {
        $data[$field] = isset($post[$field]) ? 1 : 0;
    }

    $this->Setting_model->simpan_tampilan_struk($data);
    redirect('setting');
}


    public function preview($divisi_id)
    {
        $data['title'] = "Preview Struk";
        $data['divisi'] = $this->Setting_model->get_divisi($divisi_id);
        $data['struk'] = $this->Setting_model->get_data_struk();
        $data['tampilan'] = $this->Setting_model->get_tampilan_struk($divisi_id);
        $this->load->view('setting/preview', $data);
    }
public function preview_ajax()
{
    $post = $this->input->post();
    $divisi_id = $post['pr_divisi_id'] ?? null;
    if (!$divisi_id) {
        echo "Divisi tidak ditemukan.";
        return;
    }

    // Daftar semua key checkbox
    $fields = [
        'show_logo', 'show_outlet', 'show_alamat', 'show_no_telepon',
        'show_custom_header', 'show_invoice', 'show_kasir_order', 'show_kasir_bayar',
        'show_no_transaksi', 'show_customer', 'show_nomor_meja',
        'show_waktu_order', 'show_waktu_bayar', 'show_custom_footer'
    ];

    // Isi default semua field dengan 0 jika tidak dikirim dari form
    foreach ($fields as $field) {
        if (!isset($post[$field])) {
            $post[$field] = 0;
        }
    }

    $data['struk'] = $this->Setting_model->get_data_struk();
    $data['tampilan'] = $post;
    $data['divisi'] = $this->Setting_model->get_divisi($divisi_id);

    $this->load->view('setting/preview', $data);
}


}
