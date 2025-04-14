<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {
    public function __construct() {
        parent::__construct();
        check_login();
        $this->load->model('Produk_model');
        $this->load->model('Kategori_model');
        $this->load->library('pagination');

    }
    
public function index() {
    $data['title'] = 'Daftar Produk';

    // Default jumlah baris 30
    $per_page = $this->input->get('per_page') ?? 30;
    $per_page = ($per_page == "all") ? $this->Produk_model->count_all_produk() : $per_page;
    
    $offset = $this->uri->segment(3, 0);
    $search = $this->input->get('search');
    $kategori = $this->input->get('kategori');
    $status = $this->input->get('status');

    // Konfigurasi pagination
    $this->load->library('pagination');
    $config['base_url'] = site_url('produk/index');
    $config['total_rows'] = $this->Produk_model->count_filtered($search, $kategori, $status);
    $config['per_page'] = ($per_page == "all") ? $config['total_rows'] : $per_page;
    $this->pagination->initialize($config);

    // Ambil data produk
    $data['produk'] = $this->Produk_model->get_filtered($config['per_page'], $offset, $search, $kategori, $status);
    $data['pagination'] = ($per_page == "all") ? "" : $this->pagination->create_links();
    $data['kategori'] = $this->Kategori_model->get_all_kategori();

    $this->load->view('templates/header', $data);
    $this->load->view('produk/index', $data);
    $this->load->view('templates/footer');
}

public function load_data() {
    $page = $this->input->get('page') ?: 1;
    $per_page = $this->input->get('per_page') ?? 30;
    $per_page = ($per_page == "all") ? $this->Produk_model->count_all_produk() : $per_page;
    $offset = ($page - 1) * $per_page;

    $kategori_id = $this->input->get('kategori_id');
    $status = $this->input->get('status');
    $search = $this->input->get('search');

    $produk = $this->Produk_model->get_filtered_produk($per_page, $offset, $kategori_id, $status, $search);
    $total_rows = $this->Produk_model->count_filtered_produk($kategori_id, $status, $search);

    // Konfigurasi pagination
    $this->load->library('pagination');
    $config['base_url'] = "#";
    $config['total_rows'] = $total_rows;
    $config['per_page'] = $per_page;
    $config['use_page_numbers'] = true;
    $config['attributes'] = array('class' => 'page-link');
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['prev_link'] = '&laquo; Sebelumnya';
    $config['next_link'] = 'Selanjutnya &raquo;';
    
    $this->pagination->initialize($config);
    $pagination = ($per_page == "all") ? "" : $this->pagination->create_links();

    echo json_encode([
        'produk' => $produk,
        'pagination' => $pagination
    ]);
}
public function tambah() {
    $data['title'] = ' Tambah Produk';
    $this->load->model('Kategori_model');
    $data['kategori'] = $this->Kategori_model->get_all_kategori();

    if ($this->input->post()) {
        $nama_file = strtolower(str_replace(' ', '_', $this->input->post('nama_produk')));

        $config['upload_path'] = './uploads/produk/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['file_name'] = $nama_file; // Gunakan nama produk sebagai nama file

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto')) {
            $foto = '';
        } else {
            $foto = $this->upload->data('file_name');
        }

        $data_insert = [
            'sku' => $this->input->post('sku'),
            'nama_produk' => $this->input->post('nama_produk'),
            'deskripsi' => $this->input->post('deskripsi'),
            'kategori_id' => $this->input->post('kategori_id'),
            'satuan' => $this->input->post('satuan'),
            'hpp' => $this->input->post('hpp'),
            'harga_jual' => $this->input->post('harga_jual'),
            'monitor_persediaan' => $this->input->post('monitor_persediaan'),
            'tampil' => $this->input->post('tampil'),
            'foto' => $foto
        ];
        $this->Produk_model->insert_produk($data_insert);
        redirect('produk');
    } else {
        $this->load->view('templates/header',$data);
        $this->load->view('produk/tambah', $data);
        $this->load->view('templates/footer');
    }
}
public function edit($id) {
    $data['title'] = 'Edit Produk';
    $this->load->model('Kategori_model');
    $data['kategori'] = $this->Kategori_model->get_all_kategori();
    $data['produk'] = $this->Produk_model->get_produk_by_id($id);
    
    if (empty($data['produk'])) {
        show_404();
    }

    if ($this->input->post()) {
        // Konversi nama produk ke format nama file standar
        $nama_file = strtolower(str_replace(' ', '_', $this->input->post('nama_produk')));
        
        $config['upload_path'] = './uploads/produk/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['file_name'] = $nama_file; // Gunakan nama produk sebagai nama file
        $config['overwrite'] = true; // Timpa file jika ada upload ulang
        
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('foto')) {
            $foto = $this->upload->data('file_name');
        } else {
            $foto = $data['produk']['foto']; // Gunakan foto lama jika tidak diupload
        }

        $data_update = [
            'sku' => $this->input->post('sku'),
            'nama_produk' => $this->input->post('nama_produk'),
            'deskripsi' => $this->input->post('deskripsi'),
            'kategori_id' => $this->input->post('kategori_id'),
            'satuan' => $this->input->post('satuan'),
//            'hpp' => $this->input->post('hpp'),
            'harga_jual' => $this->input->post('harga_jual'),
            'monitor_persediaan' => $this->input->post('monitor_persediaan'),
            'tampil' => $this->input->post('tampil'),
            'foto' => $foto
        ];
        
        $this->Produk_model->update_produk($id, $data_update);
        redirect('produk');
    } else {
        $this->load->view('templates/header', $data);
        $this->load->view('produk/edit', $data);
        $this->load->view('templates/footer');
    }
}
    public function hapus($id) {
        $this->Produk_model->delete_produk($id);
        redirect('produk');
    }


}
