<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    public function get_user_by_username($username) {
        return $this->db->get_where('users', ['username' => $username])->row_array();
    }
}
