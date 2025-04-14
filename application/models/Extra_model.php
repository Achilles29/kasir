<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Extra_model extends CI_Model {

    public function getAllExtra() {
        return $this->db->get('pr_extra')->result_array();
    }
}
?>
