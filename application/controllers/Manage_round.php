<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Manage_round extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session_lib');
        $this->load->library('secure_lib');

        $this->session_lib->check_session_age();
    }

	public function index() {

        $data['thisYear'] = date("Y")+543;
        $data['title'] = 'RTES';
		$this->load->view('foundation_view/admin_header_view', $data);
		$this->load->view('manage_round_view/manage_round_index', $data);
		$this->load->view('foundation_view/admin_footer_view');
    }
    
    public function ajax_list_rounds() {
        $this->load->model('manage_round_model');

        $rounds = $this->manage_round_model->list_rounds()->result_array();
        foreach ($rounds as $r) {
            $r['enc_id'] = $this->secure_lib->makeSecure($r['row_id'], 'enc');
            $info[] = $r;
        }

        $info = ( isset($info) ) ? $info = $info : $info = [];
        echo json_encode($info); 
    }

    public function ajax_create_round() {
        $this->load->model('manage_round_model');

        $mixDate            = explode("-", $this->input->post('date'));
        $date               = $mixDate[2]-543 ."-$mixDate[1]-$mixDate[0]";
        $data['date']       = $date;
        $hour               = ( strlen($this->input->post('hour')) == 1) ? "0{$this->input->post('hour')}" : $this->input->post('hour');
        $minute             = ( strlen($this->input->post('minute')) == 1) ? "0{$this->input->post('minute')}" : $this->input->post('minute');
        $data['time']       = "$hour:$minute:00";
        $data['room_id']    = $this->secure_lib->makeSecure($this->input->post('room'), 'dec');
        $year               = $this->input->post('year');
        $round              = $this->input->post('round');
        $data['round']      = "$year/$round";
        $data['amountSeat'] = $this->input->post('amount_seat');

        $num = $this->manage_round_model->check_dup_round($data)->num_rows();
        if ($num == 0) {
            $insert = $this->manage_round_model->insert_round($data);
            if ($insert) {
                $result['status']   = true;
                $result['text']     = 'บันทึกข้อมูลเรียบร้อย';
            } else {
                $result['status']   = false;
                $result['text']     = 'บันทึกข้อมูลไม่ได้';
            }
            
        } else {
            $result['status']   = false;
            $result['text']     = 'บันทึกข้อมูลไม่ได้ มีรอบการทดสอบนี้แล้ว';
        }
        
        echo json_encode($result);
    }

    public function ajax_list_room() {
        $this->load->model('manage_round_model');
        $room = $this->manage_round_model->list_room()->result_array();
        foreach ($room as $r) {
            $r['enc_id'] = $this->secure_lib->makeSecure($r['row_id'], 'enc');
            $info[] = $r;
        }

        $info = ( isset($info) ) ? $info = $info : $info = [];

        echo json_encode($info);
    }

    public function round_detail($id) {
        $this->load->model('manage_round_model');

        $dec_id = $this->secure_lib->makeSecure($id, 'dec');
        $data['round_detail'] = $this->manage_round_model->get_round_detail($dec_id)->row();
    }

    public function ajax_update_round() {
        $this->load->model('manage_round_model');

        $d = substr($this->input->post('date'), 0,2);
        $m = substr($this->input->post('date'), 3, 2);
        $y = substr($this->input->post('date'), 6,4) - 543;

        $data['date']       = "{$y}-{$m}-{$d}";
        $data['hour']       = $retVal = (strlen($this->input->post('hour')) == 1) ? "0".$this->input->post('hour') : $this->input->post('hour');
        $data['minute']     = $retVal = (strlen($this->input->post('minute')) == 1) ? "0".$this->input->post('minute') : $this->input->post('minute');
        $data['round']      = $this->input->post('round');
        $data['room']       = $this->secure_lib->makeSecure($this->input->post('room'), 'dec');
        $data['round_id']   = $this->secure_lib->makeSecure($this->input->post('round_id'), 'dec');

        $update = $this->manage_round_model->update_round($data);
        if ($update) {
            $result['status']   = true;
            $result['text']     = 'บันทึกข้อมูลเรียบร้อย';
        } else {
            $result['status']   = false;
            $result['text']     = 'บันทึกข้อมูลไม่ได้';
        }
        
        echo json_encode($result);
    }

    public function ajax_get_registered_data() {
        $this->load->model('manage_round_model');

        $dec_round_id = $this->secure_lib->makeSecure($this->input->post('round'), 'dec');
        $registered = $this->manage_round_model->get_registered_data($dec_round_id)->result_array();
        foreach ($registered as $r) {
            $r['enc_id'] = $this->secure_lib->makeSecure($r['row_id'], 'enc');
            $info[] = $r;
        }

        $info = ( isset($info) ) ? $info = $info : $info = [];

        echo json_encode($info);

    }

    public function ajax_close_seat() {
        $this->load->model('manage_round_model');

        $seat = $this->input->post('seat');
        if ( $seat != null ) {
            foreach ($seat as $id) {
                $seat_id = $this->secure_lib->makeSecure($id, 'dec');
                $update = $this->manage_round_model->disable_seat($seat_id);
    
                if ($update) {
                    $data['pass'][] = $seat_id;
                } else {
                    $data['fail'][] = $seat_id;
                }            
            }
        } else {
            $data['pass'][] = '';
            $data['fail'][] = '';
        }
        
        
        echo json_encode($data);
    }

    public function ajax_open_seat() {
        $this->load->model('manage_round_model');

        $seat = $this->input->post('seat');
        if ( $seat != null ) {            
            foreach ($seat as $id) {
                $seat_id = $this->secure_lib->makeSecure($id, 'dec');
                $update = $this->manage_round_model->enable_seat($seat_id);
    
                if ($update) {
                    $data['pass'][] = $seat_id;
                } else {
                    $data['fail'][] = $seat_id;
                }            
            }

        } else {
            $data['pass'][] = '';
            $data['fail'][] = '';
        }
        
        echo json_encode($data);
    }

    public function ajax_clear_seat() {
        $this->load->model('manage_round_model');

        $seat = $this->input->post('seat');
        if ( $seat != null ) {            
            foreach ($seat as $id) {
                $seat_id = $this->secure_lib->makeSecure($id, 'dec');
                $this->write_clear_seat($seat_id);
                $update = $this->manage_round_model->clear_seat($seat_id);
    
                if ($update) {
                    $data['pass'][] = $seat_id;
                } else {
                    $data['fail'][] = $seat_id;
                }            
            }

        } else {
            $data['pass'][] = '';
            $data['fail'][] = '';
        }
        
        echo json_encode($data);
    }

    public function ajax_disable_round() {
        $this->load->model('manage_round_model');

        $round_id = $this->input->post('round_id');
        $id = $this->secure_lib->makeSecure($round_id, 'dec');
        $update = $this->manage_round_model->disable_round($id);
        
        echo json_encode($update);
    }

    public function ajax_enable_round() {
        $this->load->model('manage_round_model');

        $round_id = $this->input->post('round_id');
        $id = $this->secure_lib->makeSecure($round_id, 'dec');
        $update = $this->manage_round_model->enable_round($id);
        
        echo json_encode($update);
    }

    private function write_clear_seat($id) {
        $this->load->model('manage_round_model');
        $detail = $this->manage_round_model->get_register_detail($id)->row_array();
        $filename = FCPATH."assets/log/".date("Y-m") ."-clear-seat.log";
        $file = fopen($filename, "a");
        $txt = date("Y-m-d H:i:s") .",{$detail['idp']},{$detail['name']},{$detail['unit_name']},{$detail['seat_number']}";
        $txt .= ",{$detail['date_test']},{$detail['time_test']},{$detail['round']},{$detail['room_name']}";
        $txt .= ",{$this->session->username}#{$this->input->ip_address()} clear";
        $txt .= PHP_EOL;
        fwrite($file, $txt);
        fclose($file);
    }

    public function generate_excel($round_id) {
        $this->load->model('manage_round_model');

        $dec_round_id   = $this->secure_lib->makeSecure($round_id, 'dec');
        $registered     = $this->manage_round_model->get_registered_data($dec_round_id)->result_array();

        foreach ($registered as $r) {
            $data['seat_number']    = $r['seat_number'];
            $data['idp']            = $r['idp'];
            $data['name']           = $r['name'];
            $data['unit_name']      = $r['unit_name'];
            $data['room_name']      = $r['room_name'];
            $data['round']          = $r['round'];
            $data['date_test']      = $r['date_test'];
            $data['time_test']      = $r['time_test'];
            $member[] = $data;
        }
        
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray($member, null, 'A1');
        $filename = 'Register.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file  
    }

    public function ajax_delete_round() {
        $this->load->model('manage_round_model');

        $round_id       = $this->input->post('round_id');
        $dec_round_id   = $this->secure_lib->makeSecure($round_id, 'dec');
        $num = $this->manage_round_model->check_round_in_register($dec_round_id)->num_rows();
        if ($num == 0) {
            $delete = $this->manage_round_model->delete_round($dec_round_id);
            if ($delete) {
                $result['status']   = true;
                $result['text']     = 'ลบข้อมูลเรียบร้อย';
            } else {
                $result['status']   = false;
                $result['text']     = 'ลบข้อมูลไม่ได้';
            }
        } else {
            $result['status']   = false;
            $result['text']     = 'ลบข้อมูลไม่ได้ เนื่องจากมีผู้ลงทะเบียน';
        }

        echo json_encode($result);
    }

}
