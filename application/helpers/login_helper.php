<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function check_login() {
    $CI =& get_instance();
    if (!$CI->session->userdata('logged_in')) {
        redirect('auth');
    }
}

function check_role($allowed_roles) {
    $CI =& get_instance();
    $divisi_id = $CI->session->userdata('divisi_id');

    if (!in_array($divisi_id, $allowed_roles)) {
        $CI->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
        redirect('auth');
    }
}
