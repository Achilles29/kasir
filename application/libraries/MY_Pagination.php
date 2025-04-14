<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Pagination extends CI_Pagination {
    public function create_links() {
        $output = parent::create_links();

        // Debugging output
        log_message('debug', 'Pagination links output: ' . print_r($output, true));

        return $output;
    }
}
