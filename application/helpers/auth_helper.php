<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function check_login() {
    $ci =& get_instance();
    
    // Pastikan user sudah login & session username ada
    if (!$ci->session->userdata('logged_in') || !$ci->session->userdata('username')) {
        redirect('auth'); // Redirect ke login jika tidak ada session
    }
}

