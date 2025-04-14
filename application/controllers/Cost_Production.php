<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cost_Production extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // Load the necessary models
        $this->load->model('Cost_Production_model');
    }

    public function index() {
        // Get the selected month, default is the current month
        $bulan = $this->input->get('bulan') ?: date('Y-m'); // Get the month from the URL parameter or use the current month
        $data['bulan'] = $bulan;
        $data['title'] = 'Cost Production';

        // Get the data for cost production
        // Calling the model method to get the cost production data for the selected month
        $data['cost_data'] = $this->Cost_Production_model->get_cost_production_data($bulan);

        // Load the view and pass the data to it
        $this->load->view('templates/header', $data);
        $this->load->view('cost_production/index', $data);
        $this->load->view('templates/footer');
    }
    public function cost_umum() {
        // Get the selected month, default is the current month
        $bulan = $this->input->get('bulan') ?: date('Y-m'); // Get the month from the URL parameter or use the current month
        $data['bulan'] = $bulan;

        // Get the data for cost production
        // Calling the model method to get the cost production data for the selected month
        $data['cost_data'] = $this->Cost_Production_model->get_cost_production_data($bulan);

        // Load the view and pass the data to it
//        $this->load->view('templates/header', $data);
        $this->load->view('cost_umum', $data);
//  /       $this->load->view('templates/footer');
    }
}
