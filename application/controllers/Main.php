<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Main extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('secure_lib');
    }

	public function index() {
        $this->load->model('main_model');
        
        $num = $this->main_model->get_round_test()->num_rows();
        if ($num > 0) {
            $data['round'] = $this->main_model->get_round_test()->row();
        } else {
            $data['round'] = (object) array('round' => '--');
        }

        $avaiableRound = $this->main_model->check_avaiable_round()->num_rows();
        
        $data['avaiable'] = $avaiableRound; //($avaiableRound == 0) ? '' : 'ปิดการลงทะเบียนแล้ว';
        $data['title'] = 'RTES';
		$this->load->view('foundation_view/header_view', $data);
		$this->load->view('main_view/main_index');
        $this->load->view('foundation_view/footer_view');
    }
    
    public function ajax_check_member_register() {
        $this->load->model('main_model');

        $idp    = $this->input->post('idp');
        $round  = $this->input->post('round');

        $check_member = $this->main_model->check_member($idp)->num_rows();
        if ($check_member == 1) {  //check person in oracle
            $data = $this->main_model->check_member($idp)->row();

            $member['idp']     = $data->BIOG_IDP;
            $member['name']     = $data->BIOG_NAME;
            $member['unitname'] = $data->BIOG_UNITNAME;
            $member['unit']     = $data->BIOG_UNIT;
            $member['email']    = "{$data->REG_USERNAME}@rtarf.mi.th";
            $member['valid']    = 'correct';

        } else if ($check_member > 1){
            $data 	= $this->main_model->check_member($idp)->result();
			$email 	= array();
			
			foreach($data as $r) {
				$email[] = "{$r->REG_USERNAME}@rtarf.mi.th";
			}
			$member['valid']    = 'multi-email';
			
        } else {
            $member['valid']    = false;
        }
		
        
        if ($member['valid'] == 'correct') {  //when person was in oracle
            $num = $this->main_model->check_registered($idp, $round)->num_rows();            
            if ($num == 0) {  //when person has not exist in current register 
                $result['status']   = 'not-register';
                $result['room']     = $this->main_model->list_round_can_register($round)->result(); 
                $result['member']   = $member;
                $result['text']     = 'ยังไม่ได้ลงทะเบียน';
            } else {
                $result['status']   = 'registered';
                $result['text']     = 'มีการลงทะเบียนแล้ว';
                $registeredData     =  $this->main_model->check_registered($idp, $round)->row();
                $result['registered'] = new stdClass; 
                $result['registered']->date_test      =  $registeredData->date_test;
                $result['registered']->idp            =  $registeredData->idp;
                $result['registered']->name           =  $registeredData->name;
                $result['registered']->room_name      =  $registeredData->room_name;
                $result['registered']->round_id       =  $registeredData->round_id;
                $result['registered']->row_id         =  $this->secure_lib->makeSecure($registeredData->row_id, 'enc');
                $result['registered']->seat_number    =  $registeredData->seat_number;
                $result['registered']->time_test      =  $registeredData->time_test;
                $result['registered']->unit_name      =  $registeredData->unit_name;

                $data_register  = $this->main_model->get_seat_detail_registered($result['registered']->seat_number, $result['registered']->round_id)->row();
                $generatePDF    = $this->main_model->generate_pdf($data_register);
            }
            
        } else if ($member['valid'] == 'multi-email') {
            $result['status']   = 'multi-email';
            $result['text']     = 'พบหลาย Email';
            $result['emails']   = $email;
        } else {
            $result['status']   = 'not-found';
            $result['text']     = 'ไม่พบในรายชื่อ';
        }
        
        echo json_encode($result);
    }

    public function ajax_register_member() {
        $this->load->helper('string');
        $this->load->model('main_model');

        $mixRound           = explode(':', $this->input->post('round')) ;
        $data['round_id']   = $mixRound[0];
        $data['round']      = $mixRound[1];
        $data['name']       = $this->input->post('name');
        $data['email']      = $this->input->post('email');
        $data['idp']        = $this->input->post('idp');
        $data['tel_number'] = $this->input->post('tel_number');
        $data['unit_code']  = $this->input->post('unit_code');
        $data['unit_name']  = $this->input->post('unit_name');
        
        $idp = $data['idp'];
        $num = $this->main_model->check_registered($idp, $data['round_id'])->num_rows();
        if ($num == 0) {  //when person has not exist in current register 
            $insert = $this->main_model->regester_member($data);

            if ($insert['status']) {
                $result         = $insert;
                $result['text'] = 'ลงทะเบียนเรียบร้อย';
            } else {
                $result['status']   = false;
                $result['text']     = 'ลงทะเบียนไม่ได้ กรุณาลงทะเบียนอีกครั้ง';
            }
            
        } else {
            $result['status']   = false;
            $result['text']     = 'มีการลงทะเบียนแล้ว';
        }

        if ($insert['status']) {
            $data_register  = $this->main_model->get_seat_detail_registered($insert['seat_number'], $insert['round_id'])->row();
            $generatePDF   = $this->main_model->generate_pdf($data_register);

            $file_name = FCPATH."/assets/PDF_generate/{$data_register->idp}.pdf";
            if (file_exists($file_name)) {
                $setKeyConfirm['key']           = random_string('alnum', 64);
                $setKeyConfirm['seat_number']   = $insert['seat_number'];
                $setKeyConfirm['round_id']      = $insert['round_id'];
                $this->main_model->confirm_key($setKeyConfirm);

                $num = $this->main_model->get_email($data_register->idp)->num_rows();
                if ($num == 1) {
                    $email = $this->main_model->get_email($data_register->idp)->row();
                    $emailAddress = "$email->REG_USERNAME@rtarf.mi.th";
                    $send = $this->send_mail($email->REG_CID, $emailAddress, $setKeyConfirm['key']);
                } else {
                    $send['status'] = false;
                    $send['text']   = 'ไม่พบ Email ใน RTARF';
                }     
                
                // $emailAddress = $data['email'];
                // $send = $this->send_mail($idp, $emailAddress, $setKeyConfirm['key']);

                if ($send['status']) {
                    $result['status']   = true;
                    $result['text']     = "ลงทะเบียนเรียบร้อย ส่งบัตรประจำตัวสอบไปยัง RTARF Mail [$emailAddress] แล้ว";
                } else {
                    $result['status']   = false;
                    $result['text']     = "ลงทะเบียนเรียบร้อย แต่ไม่สามารถส่งบัตรประจำตัวสอบไปยัง RTARF Mail <{$send['text']}>";
                }                
            } 
        }

        $data['seat_number'] = ( isset($insert['seat_number']) ) ? $insert['seat_number'] : '*';
        $this->write_send_mail($result, $data);

        echo json_encode($result);
    }

    private function send_mail($idp, $email, $message) {	
		$mail = new phpmailer;
		$mail->IsSMTP(); 
		$mail->SMTPDebug  	= 0; 		
		$mail->SMTPAuth   	= true;                  
		$mail->SMTPSecure 	= "tls"; 
		$mail->SMTPOptions	= array('ssl' => array('verify_peer' => false,'verify_peer_name' => false,'allow_self_signed' => true));              
		$mail->Host       	= "intmail.rtarf.mi.th";
		$mail->Port 		= 587;
		$mail->Username 	= "mildoc@rtarf.mi.th";
		$mail->Password		= "xje7Cjma";
		//$mail->SetFrom("rtarfli@rtarf.mi.th");
		$mail->SetFrom("rtarfli@rtarf.mi.th", 'สถาบันภาษากองทัพไทย RTARF Language Institute');

        $text = "<h2>บัตรประจำตัวผู้เข้าสอบ</h2> <h3>การทดสอบวัดระดับทักษะภาษาอังกฤษของ บก.ทท.</h3>";
        // $site = site_url("main/confirm/$message");
        // $text .= "<a href='{$site}'>ยืนยันการลงทะเบียน ทดสอบวัดระดับทักษะภาษาอังกฤษของ บก.ทท.<a>";
        // $text .= "<div>กรุณายืนยันการลงทะเบียน ภายใน 3 วัน</div>";

        $mail->AddAddress($email);
        $file_name = FCPATH."/assets/PDF_generate/{$idp}.pdf";
        $mail->addAttachment($file_name); 
		$mail->Subject = 'RTARF Language Institute: สถาบันภาษากองทัพไทย';
		$mail->MsgHTML($text);
		$mail->CharSet = "utf-8";
		 
		//send the message, check for errors
		if (!$mail->send()) {
			// echo "Mailer Error: " . $mail->ErrorInfo;
            $send['status'] = false;
            $send['text']   = "ส่ง Email ไม่สำเร็จ Mailer Error: ". $mail->ErrorInfo;
		} else {
            // echo "Message sent!";
            $send['status'] = true;
            $send['text']   = "ส่ง Email สำเร็จ";            
        }
        
        return $send;
    }

    public function confirm($confirmKey) {
        $this->load->model('main_model');

        $num = $this->main_model->check_confirm_key($confirmKey)->num_rows();
        if ($num == 1) {
            $confirm = $this->main_model->check_confirm_key($confirmKey)->row();

            $limitSecond = time()-strtotime($confirm->time_create);
            echo $limitSecond;
            // echo strtotime('2019-07-06 15:21:26') - time() ."<br>";

            if ( $limitSecond < (3*24*60*60) ) {
                $setConfirm = $this->main_model->set_confirm($confirm->round_id, $confirm->seat_number);
                redirect('main/result_comfirm/true');
            } else {
                // echo 'หมดเวลา';
                redirect('main/result_comfirm/false');
            }
            
        } else {
            redirect('main/result_comfirm/false');
        }
    }

    public function result_comfirm($type) {
        $data['title'] = 'RTES';

        if ($type == 'true') {
            $data['class'] = 'text-success';
            $data['text'] = 'ยืนยันการลงทะเบียนเรียบร้อย';
        } else {
            $data['class'] = 'text-danger';
            $data['text'] = '! หมดเวลายืนยัน การลงทะเบียน';
        }
        
        $this->load->view('foundation_view/header_view', $data);
		$this->load->view('main_view/main_result_comfirm', $data);
        $this->load->view('foundation_view/footer_view');
    }

    
    public function ajax_check_score() {
        $this->load->model('main_model');

        $idp = $this->input->post('idp');
        $num = $this->main_model->check_score($idp)->num_rows();
        if ($num > 0) {
            $result['status']   = true;
            $result['text']     = "พบข้อมูล";
            $result['score']    = $this->main_model->check_score($idp)->result();
        } else {
            $result['status']   = false;
            $result['text']     = "ไม่พบข้อมูล";
        }
        
        echo json_encode($result);
    }

    public function ajax_cancel_registered() {
        $this->load->model('main_model');

        $row_id = $this->secure_lib->makeSecure($this->input->post('row'), 'dec');
        $detail = $this->main_model->get_register_detail($row_id)->row_array(); 
        $openedRound = $this->main_model->check_opened_round()->num_rows();  
        if ($openedRound != 0) {
            $update = $this->main_model->cancel_registered($row_id);            
            if ($update) {
                $result['status']   = true;
                $result['text']     = "ยกเลิกการลงทะเบียน เรียบร้อย";
            } else {
                $result['status']   = false;
                $result['text']     = "ยกเลิกการลงทะเบียน ไม่ได้";
            }
            $this->write_clear_seat($detail, $result);
        } else {
            $result['status']   = false;
            $result['text']     = "ยกเลิกการลงทะเบียน ไม่ได้ เนื่องจากปิดการลงทะเบียนทุกห้องแล้ว";
        }
              
        echo json_encode($result);
    }

    private function write_send_mail($mailStatus, $data) {
        $filename = FCPATH."assets/log/".date("Y-m") ."-send-mail.log";
        $file = fopen($filename, "a");
        $txt = date("Y-m-d H:i:s") .",{$data['idp']},{$data['name']},{$data['email']},{$data['seat_number']},{$mailStatus['text']}";
        $txt .= ",{$this->input->ip_address()}";
        $txt .= PHP_EOL;
        fwrite($file, $txt);
        fclose($file);

        return ;
    }

    private function write_clear_seat($detail, $result) {
        $this->load->model('main_model');
        // $detail = $this->main_model->get_register_detail($id)->row_array();
        $filename = FCPATH."assets/log/".date("Y-m") ."-clear-seat.log";
        $file = fopen($filename, "a");
        $txt = date("Y-m-d H:i:s") .",{$detail['idp']},{$detail['name']},{$detail['unit_name']},{$detail['seat_number']}";
        $txt .= ",{$detail['date_test']},{$detail['time_test']},{$detail['round']},{$detail['room_name']}";
        $txt .= ",CLIENT_USER#{$this->input->ip_address()} {$result['text']}";
        $txt .= PHP_EOL;
        fwrite($file, $txt);
        fclose($file);
    }

}
