<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Score extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session_lib');
        $this->load->library('secure_lib');

        $this->session_lib->check_session_age();
    }

	public function index() {
        $this->load->model('score_model');
                
        $data['title'] = 'RTES';
		$this->load->view('foundation_view/admin_header_view', $data);
		$this->load->view('score_view/score_index');
        $this->load->view('foundation_view/admin_footer_view');
    } 
    
    public function ajax_upload_score() {
        $this->load->helper('string');
        $this->load->library('upload');

        $config['upload_path']      = './assets/score_file';
        $config['allowed_types']    = 'xls|xlsx|csv';
        $config['max_size']         = '20480';
        $config['file_name']        = random_string('alnum', 16);

        $this->upload->initialize($config);

        if($this->upload->do_upload('file_upload')) {
            $this->load->model('score_model');

            $data['file']['status'] = true;
            $data['file']['detail'] = $this->upload->data();
            $data['file']['text']   = "Upload File สำเร็จ";

            $inputFileName = $data['file']['detail']['full_path'];

            $reader = new Xlsx();
            $spreadsheet = $reader->load($inputFileName);
            $scoreData = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);
            array_shift($scoreData);
            $data['file']['score_data'] = $scoreData;
            foreach ($scoreData as $r) {
                // $mixRound = explode('/', $r['B']);
                // $round  = "{$mixRound[1]}/{$mixRound[0]}";

                $idp    = $r['A'];
                $score  = $r['C'];
                $round  = $r['B'];

                $person = $this->score_model->get_person_detail($idp)->row_array();

                $num = $this->score_model->check_in_registered($idp, $round)->num_rows();
                if ($num == 1) {
                    $setScore = $this->score_model->insert_score($idp, $round, $score);
                    if ($setScore) {
                        $person['SCORE']    = $score;
                        $person['ROUND']    = $round;
                        $person['TEXT']     = 'บันทึกข้อมูลเรียบร้อย';

                        $data['pass'][]     = $person;
                    } else {
                        $person['TEXT']     = 'บันทึกไม่ได้';
                        $person['ROUND']    = $round;

                        $data['fail'][]     = $person;
                    }
                    
                } else {
                    $person['TEXT']     = 'ไม่มีรายชื่อ ในรายการลงทะเบียน';
                    $person['ROUND']    = $round;

                    $data['fail'][]     = $person;
                }
            }

            unlink($inputFileName);
        } else {
            $data['file']['status'] = false;
            $data['file']['text']   = $this->upload->display_errors();
        }

        echo json_encode($data);
    }
}
