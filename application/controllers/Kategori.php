<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Kategori_model');
        $this->load->model('Divisi_model');
        $this->load->library('pagination');
        
    }
    
    public function index() {
        $data['title'] = 'Daftar Kategori';
        $data['divisi'] = $this->Divisi_model->get_all_divisi();
        $this->load->view('templates/header', $data);
        $this->load->view('kategori/index', $data);
        $this->load->view('templates/footer');
    }
    
public function load_data() {
    $page = $this->input->get('page') ?: 1;
    $per_page = $this->input->get('per_page') ?: 10;
    $search = $this->input->get('search');
    $offset = ($page - 1) * $per_page;

    $total_rows = $this->Kategori_model->count_filtered($search);

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
    $this->pagination->cur_page = $page; // 👈 ini penting!

    $pagination = $this->pagination->create_links();

    // ✅ Tambahkan data-page ke semua <a> yang merupakan angka atau simbol
    $pagination = preg_replace_callback('/<a([^>]*)>(.*?)<\/a>/', function ($match) {
        $href = $match[1];
        $label = $match[2];

        // Pastikan hanya angka &raquo; &laquo; yang diproses
        $label_clean = trim(strip_tags($label));
        if (is_numeric($label_clean) || $label_clean === '»' || $label_clean === '«') {
            return '<a' . $href . ' data-page="' . $label_clean . '">' . $label . '</a>';
        }
        return $match[0];
    }, $pagination);

    $data = $this->Kategori_model->get_filtered($per_page, $offset, $search);

    echo json_encode([
        'data' => $data,
        'pagination' => $pagination
    ]);
}



    public function get($id) {
        $kategori = $this->Kategori_model->get_kategori_by_id($id);
        if ($kategori) {
            echo json_encode(['status' => 'success', 'data' => $kategori]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }

    public function save() {
        $data = $this->input->post();
        unset($data['id']);

        $data['updated_at'] = date('Y-m-d H:i:s');

        if ($this->input->post('id')) {
            $this->Kategori_model->update_kategori($this->input->post('id'), $data);
            $message = "Data berhasil diperbarui!";
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->Kategori_model->insert_kategori($data);
            $message = "Data berhasil ditambahkan!";
        }

        echo json_encode(['status' => 'success', 'message' => $message]);
    }

    public function delete() {
        $id = $this->input->post('id');
        if ($this->Kategori_model->delete_kategori($id)) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
        }
    }

public function tambah() {
    $data['title'] = 'Tambah Kategori';
    $data['divisi'] = $this->Divisi_model->get_all_divisi();
    
    if ($this->input->post()) {
        $urutan = $this->input->post('urutan');
        if ($this->Kategori_model->check_duplicate_urutan($urutan)) {
            $this->session->set_flashdata('error', 'Urutan tampilan sudah digunakan!');
            redirect('kategori/tambah');
        }

        $data_insert = [
            'nama_kategori' => $this->input->post('nama_kategori'),
            'urutan' => $urutan,
            'pr_divisi_id' => $this->input->post('pr_divisi_id'),
            'status' => $this->input->post('status')
        ];
        $this->db->insert('pr_kategori', $data_insert);
        redirect('kategori');
    }
    
    $this->load->view('templates/header', $data);
    $this->load->view('kategori/tambah', $data);
    $this->load->view('templates/footer');
}

public function edit($id) {
    $data['title'] = 'Edit Kategori';
    $data['kategori'] = $this->Kategori_model->get_kategori_by_id($id);
    $data['divisi'] = $this->Divisi_model->get_all_divisi();

    if ($this->input->post()) {
        $urutan = $this->input->post('urutan');
        if ($this->Kategori_model->check_duplicate_urutan($urutan, $id)) {
            $this->session->set_flashdata('error', 'Urutan tampilan sudah digunakan!');
            redirect('kategori/edit/'.$id);
        }

        $data_update = [
            'nama_kategori' => $this->input->post('nama_kategori'),
            'urutan' => $urutan,
            'pr_divisi_id' => $this->input->post('pr_divisi_id'),
            'status' => $this->input->post('status')
        ];
        $this->db->where('id', $id);
        $this->db->update('pr_kategori', $data_update);
        redirect('kategori');
    }
    
    $this->load->view('templates/header', $data);
    $this->load->view('kategori/edit', $data);
    $this->load->view('templates/footer');
}    
    public function hapus($id) {
        $this->Kategori_model->delete_kategori($id);
        redirect('kategori');
    }
}