<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StokOpname extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('StokOpname_model');
    }

    public function index() {
        // Get month and year from GET request or use current date
        $month = $this->input->get('month') ?: date('m');
        $year = $this->input->get('year') ?: date('Y');

        $data['title'] = 'Stok Opname';
        
        // Handle sorting and pagination
        $sort_criteria = [
            'sort_1' => $this->input->get('sort_1') ?: 'kategori',
            'sort_2' => $this->input->get('sort_2') ?: 'nama_barang',
            'sort_3' => $this->input->get('sort_3') ?: 'nama_bahan_baku',
            'sort_4' => $this->input->get('sort_4') ?: 'tipe',
        ];

        // Handle pagination limit and page
        $limit = $this->input->get('limit') ?: 20;
        if ($limit == 'all') {
            $limit = 999999;  // A very high number to show all records
        }
        $page = (int) $this->input->get('page') ?: 1;  // Ensure page is treated as an integer
        $start = ($page - 1) * $limit;

        // Handle sorting criteria
        $sort_params = [
            $sort_criteria['sort_1'] => 'ASC',
            $sort_criteria['sort_2'] => 'ASC',
            $sort_criteria['sort_3'] => 'ASC',
            $sort_criteria['sort_4'] => 'ASC',
        ];

        // Get filtered stock opname data
        $data['stok_opname_data'] = $this->StokOpname_model->get_all($month, $year, $limit, $start, $sort_params);

        // Count total rows for pagination
        $total_rows = $this->StokOpname_model->count_all($month, $year);
        
        // Pagination configuration
        $this->load->library('pagination');
        $config['base_url'] = base_url('stok_opname/index?month=' . $month . '&year=' . $year . '&limit=' . $limit);
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $limit;
        $config['use_page_numbers'] = true;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        // Pass data to the view
        $data['pagination'] = $this->pagination->create_links();
        $data['month'] = $month;
        $data['year'] = $year;
        $data['limit'] = $limit;
        $data['start'] = $start;

        // Calculate total nilai_total
        $total_nilai_total = 0;
        foreach ($data['stok_opname_data'] as $item) {
            $total_nilai_total += $item['nilai_total'];
        }
        $data['total_nilai_total'] = $total_nilai_total;

        // Load view
        $this->load->view('templates/header', $data);
        $this->load->view('stok_opname/index', $data);
        $this->load->view('templates/footer');
    }



    // AJAX search function for stock opname
public function search() {
    $keyword = $this->input->get('keyword');
    $month = $this->input->get('month');
    $year = $this->input->get('year');
    $limit = $this->input->get('limit') ?: 20;
    
    // Call the search method in the model to get filtered results
    $data = $this->StokOpname_model->search($keyword, $month, $year, $limit);
    
    // Return the data as JSON to the frontend for the table update
    echo json_encode($data);
}

}
?>
