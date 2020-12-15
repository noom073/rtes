<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Search extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session_lib');
        $this->load->library('secure_lib');

        $this->session_lib->check_session_age();
    }

	public function index() {
        $this->load->model('search_model');

        $data['unitname'] = $this->search_model->get_unitname()->result_array();
        $data['time_test'] = $this->search_model->get_time_test()->result_array();
                
        $data['title'] = 'RTES';
		$this->load->view('foundation_view/admin_header_view', $data);
		$this->load->view('search_view/search_index');
        $this->load->view('foundation_view/admin_footer_view');
    } 

    public function ajax_search_score() {
        $this->load->model('search_model');

        $searchData['idp']      = $this->input->post('idp');
        $searchData['unitname'] = $this->input->post('unit');
        $searchData['date']     = $this->input->post('date');
        $searchData['time']     = $this->input->post('time');

        $searchResult = $this->search_model->search_score($searchData)->result();
        echo json_encode($searchResult);
    }
    
}
