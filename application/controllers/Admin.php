<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session_lib');
        $this->load->library('secure_lib');

        $this->session_lib->check_session_age();
    }

	public function index() {
        $this->load->model('main_model');
        // echo json_encode($data);
        $data['title'] = 'RTES';
		$this->load->view('foundation_view/admin_header_view', $data);
		$this->load->view('admin_view/admin_index', $data);
		$this->load->view('foundation_view/admin_footer_view');
    }
    
    public function ajax_create_room() {
        $this->load->model('admin_model');

        $data['room_name']  = $this->input->post('room_name');
        $data['address']    = $this->input->post('address');

        $num = $this->admin_model->check_dup_room($data);

        if ($num->num_rows() == 0) {
            $insert = $this->admin_model->insert_room($data);

            if ($insert) {
                $result['status']   = true;
                $result['text']     = "บันทึกข้อมูลเรียบร้อย";
            } else {
                $result['status']   = false;
                $result['text']     = "บันทึกข้อมูลไม่ได้";
            }            
        } else {
            $result['status']   = false;
            $result['text']     = "ชื่อห้องสอบซ้ำ บันทึกข้อมูลไม่ได้";
        }

        echo json_encode($result);
    }

    public function ajax_update_room() {
        $this->load->model('admin_model');

        $data['room_name']  = $this->input->post('edit_room_name');
        $data['address']    = $this->input->post('edit_address');
        $data['row_id']     = $this->secure_lib->makeSecure($this->input->post('edit_enc_id'), 'dec');

        $num = $this->admin_model->check_room_before_update($data);

        if ($num->num_rows() == 0) {
            $update = $this->admin_model->update_room($data);

            if ($update) {
                $result['status']   = true;
                $result['text']     = "บันทึกข้อมูลเรียบร้อย";
            } else {
                $result['status']   = false;
                $result['text']     = "บันทึกข้อมูลไม่ได้";
            }            
        } else {
            $result['status']   = false;
            $result['text']     = "ชื่อห้องสอบซ้ำ บันทึกข้อมูลไม่ได้";
        }

        echo json_encode($result);
    }

    public function ajax_list_rooms() {
        $this->load->model('admin_model');

        $rooms = $this->admin_model->list_rooms()->result_array();
        foreach ($rooms as $r) {
            $r['enc_id'] = $this->secure_lib->makeSecure($r['row_id'], 'enc');
            $info[] = $r;
        }

        $info = ( isset($info) ) ? $info = $info : $info = [];
        echo json_encode($info); 
    }

    public function ajax_delete_room() {
        $this->load->model('admin_model');
        $row_id = $this->secure_lib->makeSecure($this->input->post('enc_id'), 'dec');

        $num = $this->admin_model->check_room_in_round($row_id)->num_rows();
        if ($num == 0) {
            $delete = $this->admin_model->delete_room($row_id);

            if ($delete) {
                $result['status']   = true;
                $result['text']     = "ลบห้องสอบเรียบร้อย";
            } else {
                $result['status']   = false;
                $result['text']     = "ลบห้องสอบ ไม่ได้";
            }
            
        } else {
            $result['status']   = false;
            $result['text']     = "ลบห้องสอบไม่ได้ เนื่องจากมีการใช้ห้องนี้";
        }
        
        echo json_encode($result);
    }

    public function manage_round() {
        $this->load->model('main_model');
        // echo json_encode($data);
        $data['title'] = 'RTES';
		$this->load->view('foundation_view/admin_header_view', $data);
		$this->load->view('admin_view/admin_manage_round', $data);
		$this->load->view('foundation_view/admin_footer_view');
    }
}
