<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Extra extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Extra_model'); // ✅ Load model
    }

    public function getExtra() {
        $extra = $this->Extra_model->getAllExtra();
        if ($extra) {
            echo json_encode($extra);
        } else {
            echo json_encode([]);
        }
    }
}
?>
