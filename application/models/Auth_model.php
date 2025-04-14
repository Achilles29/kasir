<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {
    public function check_user($username, $password) {
        $this->db->where('username', $username);
        $user = $this->db->get('abs_pegawai')->row_array();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
