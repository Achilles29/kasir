<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }

    public function index() {
        // Jika sudah login, redirect ke beranda
        if ($this->session->userdata('logged_in')) {
            redirect('beranda');
        }

        $this->load->view('auth/login');
    }

    public function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->Auth_model->check_user($username, $password);

        if ($user) {
            // Set session untuk Web
            $session_data = [
                'pegawai_id' => $user['id'],
                'username' => $user['username'],
                'nama' => $user['nama'],
                'divisi_id' => $user['divisi_id'],
                'logged_in' => TRUE
            ];
            $this->session->set_userdata($session_data);

            // Jika request dari Flutter (JSON Response)
            if ($this->input->is_ajax_request()) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login berhasil',
                    'pegawai_id' => $user['id'],
                    'username' => $user['username'],
                    'nama' => $user['nama'],
                    'divisi_id' => $user['divisi_id'],
                    'token' => bin2hex(random_bytes(16)) // Simpan token jika perlu
                ]);
                return;
            }

            // Redirect ke dashboard jika bukan request API
            redirect('beranda');
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Username atau password salah']);
                return;
            }

            $this->session->set_flashdata('error', 'Username atau Password salah!');
            redirect('auth');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
