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
    $page = (int) ($this->input->get('page') ?: 1);
    $per_page = $this->input->get('per_page') ?? 30;
    $per_page = ($per_page == "all") ? $this->Produk_model->count_all_produk() : $per_page;
    $offset = ($page - 1) * $per_page;

    $kategori_id = $this->input->get('kategori_id');
    $status = $this->input->get('status');
    $search = $this->input->get('search');

    $produk = $this->Produk_model->get_filtered_produk($per_page, $offset, $kategori_id, $status, $search);
    $total_rows = $this->Produk_model->count_filtered_produk($kategori_id, $status, $search);

    $total_pages = ceil($total_rows / $per_page);
    $pagination = '';

    if ($total_pages > 1) {
        if ($page > 1) {
            $pagination .= '<li class="page-item"><a class="page-link" href="#" data-page="'.($page - 1).'">&laquo;</a></li>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            $active = $i == $page ? 'active' : '';
            $pagination .= '<li class="page-item '.$active.'"><a class="page-link" href="#" data-page="'.$i.'">'.$i.'</a></li>';
        }

        if ($page < $total_pages) {
            $pagination .= '<li class="page-item"><a class="page-link" href="#" data-page="'.($page + 1).'">&raquo;</a></li>';
        }
    }

    echo json_encode([
        'produk' => $produk,
        'pagination' => $pagination
    ]);
}

public function get_by_id() {
    $id = $this->input->get('id');
    $data = $this->Produk_model->get_produk_by_id($id);
    echo json_encode($data);
}

public function get_produk_by_id($id) {
    $produk = $this->Produk_model->get_produk_by_id($id);
    echo json_encode($produk);
}

public function simpan() {
    $id = $this->input->post('id');
    $data = [
        'sku' => $this->input->post('sku'),
        'nama_produk' => $this->input->post('nama_produk'),
        'deskripsi' => $this->input->post('deskripsi'),
        'kategori_id' => $this->input->post('kategori_id'),
        'satuan' => $this->input->post('satuan'),
        'hpp' => $this->input->post('hpp') ?: 0,
        'harga_jual' => $this->input->post('harga_jual'),
        'monitor_persediaan' => $this->input->post('monitor_persediaan'),
        'tampil' => $this->input->post('tampil')
    ];

    if (!empty($_FILES['foto']['name'])) {
        $config['upload_path'] = './uploads/produk/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['file_name'] = strtolower(str_replace(' ', '_', $data['nama_produk']));
        $config['overwrite'] = true;

        $this->load->library('upload', $config);
        if ($this->upload->do_upload('foto')) {
            $data['foto'] = $this->upload->data('file_name');
        }
    }

    if ($id) {
        $this->Produk_model->update_produk($id, $data);
    } else {
        $this->Produk_model->insert_produk($data);
    }

    echo json_encode(['status' => 'success']);
}

public function insert_ajax() {
    $this->_save_produk(); // buat fungsi privat untuk simpan
}

public function update_ajax() {
    $this->_save_produk(true);
}

private function _save_produk($is_update = false) {
    $id = $this->input->post('id');
    $data = [
        'sku' => $this->input->post('sku'),
        'nama_produk' => $this->input->post('nama_produk'),
        'deskripsi' => $this->input->post('deskripsi'),
        'kategori_id' => $this->input->post('kategori_id'),
        'satuan' => $this->input->post('satuan'),
        'hpp' => $this->input->post('hpp'),
        'harga_jual' => $this->input->post('harga_jual'),
        'monitor_persediaan' => $this->input->post('monitor_persediaan'),
        'tampil' => $this->input->post('tampil')
    ];

    // Upload foto jika ada
    if (!empty($_FILES['foto']['name'])) {
        $config['upload_path'] = './uploads/produk/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['file_name'] = strtolower(str_replace(' ', '_', $this->input->post('nama_produk')));
        $config['overwrite'] = true;
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('foto')) {
            $data['foto'] = $this->upload->data('file_name');
        }
    }

    if ($is_update) {
        $this->Produk_model->update_produk($id, $data);
        echo json_encode(['status' => 'success', 'message' => 'Produk berhasil diperbarui']);
    } else {
        $this->Produk_model->insert_produk($data);
        echo json_encode(['status' => 'success', 'message' => 'Produk berhasil ditambahkan']);
    }
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