<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View_log extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->helper('directory');
        $this->load->helper('file');
        $this->load->library('session_lib');
        $this->load->library('secure_lib');

        $this->session_lib->check_session_age();
    }

	public function index() {
        $this->load->model('main_model');
        // echo json_encode($data);
        $data['title'] = 'RTES';
		$this->load->view('foundation_view/admin_header_view', $data);
		$this->load->view('log_view/log_index', $data);
		$this->load->view('foundation_view/admin_footer_view');
    }

    public function ajax_list_log() {
        $pathLog    = FCPATH .'assets/log';
        $directory  = directory_map($pathLog);
        foreach ($directory as $r) {
            $array['filename'] = $r;
            $array['info'] = get_file_info("$pathLog/$r", array('size', 'date'));
            $info[] = $array;
        }
        $info = ( isset($info) ) ? $info = $info : $info = [];
        echo json_encode($info);
        // echo '[["2019-07-clear-seat.log"],["2019-07-send-mail.log"]]';
    }

    public function ajax_get_log_detail() {
        $filename   = $this->input->post('file'); 
        $pathLog    = FCPATH .'assets/log';
        $myfile     = fopen("$pathLog/$filename", "r");
        while(!feof($myfile)) {
            $text['text'] = fgets($myfile);
            $info[] = $text;
        }
        fclose($myfile);

        echo json_encode($info);
    }
    
}
