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
        $data['printer'] = $this->Setting_model->get_all_printer();
        $data['struk']  = $this->Setting_model->get_data_struk();

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

        // upload logo jika ada
        if (!empty($_FILES['logo']['name'])) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['file_name'] = 'logo_' . time();
            $config['overwrite'] = true;

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('logo')) {
                $data['logo'] = $this->upload->data('file_name');
            } else {
                echo json_encode(['status' => 'error', 'message' => $this->upload->display_errors()]);
                return;
            }
        }

        $this->Setting_model->simpan_data_struk($data);
        redirect('setting');
    }

    public function form_tampilan_struk($printer_id)
    {
        $data['title'] = "Tampilan Struk per Printer";
        $data['printer'] = $this->Setting_model->get_printer($printer_id);
        $data['tampilan'] = $this->Setting_model->get_tampilan_struk($printer_id);

        $this->load->view('templates/header', $data);
        $this->load->view('setting/form_tampilan_struk', $data);
        $this->load->view('templates/footer');
    }


    public function simpan_tampilan_struk()
    {
        $post = $this->input->post();
        $printer_id = $post['printer_id'];

        $all_fields = [
            'show_logo', 'show_outlet', 'show_alamat', 'show_no_telepon',
            'show_custom_header', 'show_invoice', 'show_kasir_order', 'show_kasir_bayar',
            'show_no_transaksi', 'show_customer', 'show_nomor_meja',
            'show_waktu_order', 'show_waktu_bayar', 'show_custom_footer'
        ];

        $data = ['printer_id' => $printer_id];
        foreach ($all_fields as $field) {
            $data[$field] = isset($post[$field]) ? 1 : 0;
        }

        $this->Setting_model->simpan_tampilan_struk($data);
        redirect('setting');
    }


    public function preview($printer_id)
    {
        $data['title'] = "Preview Struk";
        $data['printer'] = $this->Setting_model->get_printer($printer_id);
        $data['struk'] = $this->Setting_model->get_data_struk();
        $data['tampilan'] = $this->Setting_model->get_tampilan_struk($printer_id);
        $this->load->view('setting/preview', $data);
    }


    public function preview_ajax()
    {
        $post = $this->input->post();
        $printer_id = $post['printer_id'] ?? null;

        if (!$printer_id) {
            echo "Printer tidak ditemukan.";
            return;
        }

        $fields = [
            'show_logo', 'show_outlet', 'show_alamat', 'show_no_telepon',
            'show_custom_header', 'show_invoice', 'show_kasir_order', 'show_kasir_bayar',
            'show_no_transaksi', 'show_customer', 'show_nomor_meja',
            'show_waktu_order', 'show_waktu_bayar', 'show_custom_footer'
        ];

        foreach ($fields as $field) {
            if (!isset($post[$field])) {
                $post[$field] = 0;
            }
        }

        $data['struk'] = $this->Setting_model->get_data_struk();
        $data['tampilan'] = $post;
        $data['printer'] = $this->Setting_model->get_printer($printer_id);
        $this->load->view('setting/preview', $data);
    }
}