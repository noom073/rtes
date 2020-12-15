<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {

    var $mysql, $oracle;

    public function __construct(Type $var = null) {
        $this->mysql  = $this->load->database('mysql', true);
        $this->oracle = $this->load->database('person1', true);
    }

	public function check_avaiable_round() {
        // $query = $this->oracle->query('select * from tab');
        $this->mysql->where('active', 'y');
        $query = $this->mysql->get('ecl2_round');
        
        return $query;
    }

    public function check_member($idp) {
        $this->oracle->select('a.BIOG_NAME, a.BIOG_IDP, a.BIOG_UNIT, BIOG_UNITNAME, 
            b.REG_USERNAME');
        $this->oracle->join('RTARFMAIL.REGISTER_TAB b', "a.BIOG_IDP = b.REG_CID", "left");
        $this->oracle->where('a.BIOG_IDP', $idp);
        $query = $this->oracle->get('PER_BIOG_VIEW a');

        return $query;
    }
    
    public function check_registered($idp, $round) {
        $this->mysql->select('a.idp, a.seat_number, a.name, a.unit_name, a.round_id,
            a.row_id,
            b.date_test, b.time_test,
            c.room_name');
        $this->mysql->join('ecl2_round b', "a.round_id = b.row_id
            and b.round = '{$round}' ");
        $this->mysql->join('ecl2_room c', "b.room_id = c.row_id");
        $this->mysql->where('idp', $idp);
        $query = $this->mysql->get('ecl2_register a');
        // echo $this->mysql->last_query();

        return $query;        
    }

    public function list_round_can_register($round) {
        $this->mysql->select('a.row_id, a.date_test, a.time_test, a.room_id, a.active, a.round,
            b.room_name,
            count(*) as total');
        $this->mysql->join('ecl2_room b', 'a.room_id = b.row_id');
        $this->mysql->join('ecl2_register c', "a.row_id = c.round_id 
            and (c.idp is null or c.idp = '')
            and c.active = 'y'");
        $this->mysql->group_by('a.row_id');
        $this->mysql->where('a.active', 'y');
        $this->mysql->where('a.round', $round);
        $query = $this->mysql->get('ecl2_round a');

        return $query;
    }

    public function regester_member($array) {
        $field['round']                 = $array['round'];
        $field['idp']                   = $array['idp'];
        $field['name']                  = $array['name'];
        $field['email']                 = $array['email'];
        $field['tel_number']            = $array['tel_number'];
        $field['unit_code']             = $array['unit_code'];
        $field['unit_name']             = $array['unit_name'];
        $field['time_user_register']    = "{$this->input->ip_address()}#".date("Y-m-d H:i:s");

        $round_id = $array['round_id'];
        $maxSeat = $this->main_model->check_max_seat($round_id)->row();

        do {
            $seat_number = rand(1, $maxSeat->max_seat);
        } while ($this->main_model->check_dup_seat($seat_number, $round_id)->num_rows() != 1);

        $chk_second = $this->main_model->check_dup_seat($seat_number, $round_id);

        if ($chk_second->num_rows() == 1) { // check dubplicate second
            $this->mysql->where('seat_number', $seat_number);         
            $this->mysql->where('round_id', $round_id);         
            $query['status']        = $this->mysql->update('ecl2_register', $field);
            $query['seat_number']   = $seat_number;
            $query['round_id']      = $round_id;
            
        } else {
            $query['status'] = false;
        }        

        return $query;
    }

    public function check_dup_seat($seat, $round_id) {
        $this->mysql->where('seat_number', $seat);
        $this->mysql->where('round_id', $round_id);
        $this->mysql->where('active', 'y');
        $this->mysql->where("(idp is null or idp like '')");
        $query = $this->mysql->get('ecl2_register');

        return $query;
    }

    public function check_max_seat($round_id) {
        $this->mysql->select_max('seat_number', 'max_seat');
        $this->mysql->where('round_id', $round_id);
        $query = $this->mysql->get('ecl2_register');

        return $query;
    }

    public function get_seat_detail_registered($seat, $round_id) {
        $this->mysql->select('a.name, a.unit_name, a.seat_number, a.idp,
            b.date_test, b.time_test,
            c.room_name');
        $this->mysql->join('ecl2_round b', 'a.round_id = b.row_id');
        $this->mysql->join('ecl2_room c', 'b.room_id = c.row_id');
        $this->mysql->where('a.seat_number', $seat);
        $this->mysql->where('a.round_id', $round_id);
        $this->mysql->where("(a.idp is not null or a.idp not like '')");
        $query = $this->mysql->get('ecl2_register a');

        return $query;
    }

    public function generate_pdf($obj) {
        $this->load->library('pdf');

        $y      = substr($obj->date_test, 0,4)+543;
        $m      = $this->thai_month( substr($obj->date_test, 5,2) );
        $d      = substr($obj->date_test, 8);
        $date   = "$d $m $y";

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->setPrintHeader(false);
        $pdf->SetFont('thsarabun', 'B');
        $pdf->AddPage();

        $params = $pdf->serializeTCPDFtagParameters(array($obj->idp, 'C39', '', '', 80, 30, 0.4, array('position'=>'C', 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'thsarabun', 'fontsize'=>16, 'stretchtext'=>1), 'N'));
        $barCode = '<tcpdf method="write1DBarcode" params="'.$params.'" />';

        $html = <<<EOF
<div>
    <h2 style="text-align:center; font-size:32px; margin:10px">บัตรประจำตัวผู้เข้าสอบ</h2>
    <div style="border: 2px solid black;">
        <div style="text-align:center; font-size:25px" >
            การทดสอบวัดระดับทักษะภาษาอังกฤษของ บก.ทท.
        </div>

        <table cellspacing="0" cellpadding="5" border="0">
            <tr>
                <td style="font-size:24px" width="110"> ชื่อผู้สอบ</td>
                <td width="300" style="font-size:18px; line-height:31px;">$obj->name</td>
                <td rowspan="3" align="center" width="130" style="font-size:32px" border="1">เลขที่นั่ง <br> $obj->seat_number</td>
            </tr>
            <tr>
                <td style="font-size:24px"> สังกัด</td>
                <td style="font-size:18px; line-height:31px;">$obj->unit_name</td>
            </tr>
            <tr>
                <td style="font-size:24px"> วันสอบ</td>
                <td style="font-size:18px; line-height:31px;">$date</td>
            </tr>
            <tr>
                <td style="font-size:24px"> เวลาสอบ</td>
                <td style="font-size:18px; line-height:31px;">$obj->time_test น.</td>
            </tr>
            <tr>
                <td style="font-size:24px"> สถานที่สอบ</td>
                <td style="font-size:18px; line-height:31px;">$obj->room_name</td>
            </tr>
            <tr>
                <td colspan="4" style="font-size:24px">$barCode</td>
            </tr>  
        </table>
        
    </div>
    <h2>คำชี้แจงและข้อปฏิบัติในการเข้ารับการทดสอบภาษาอังกฤษ</h2>
    <ol>
        <li style="font-size:15px;"> เข้ารับการทดสอบตามรอบที่ลงทะเบียนไว้เท่านั้น หากไม่มาสอบจะถือว่าสละสิทธิ์</li>
        <li style="font-size:15px;"> ไม่อนุญาตให้เข้าห้องสอบหลังจากเริ่มการทดสอบแล้ว</li>
        <li style="font-size:15px;"> ไม่สามารถสมัครเพิ่มเติมหรือเปลี่ยนแปลงรอบการทดสอบหลังจากปิดระบบลงทะเบียน</li>
        <li style="font-size:15px;"> นำบัตรประจำตัวประชาชนหรือบัตรข้าราชการ พร้อมดินสอ 2B และยางลบไปในวันทดสอบ</li>
        <li style="font-size:15px;"> ห้ามนำเครื่องมือสื่อสารหรืออุปกรณ์อิเล็กทรอนิกส์ทุกชนิดเข้าห้องสอบ</li>
        <li style="font-size:15px;"> พิมพ์หลักฐานการลงทะเบียน และนำไปแสดงต่อเจ้าหน้าที่ก่อนเข้าห้องสอบ</li>
    </ol>
</div>
EOF;
        $pdf->writeHTML($html);
        
        $filename = "$obj->idp.pdf";
        $result = $pdf->Output(FCPATH."/assets/PDF_generate/$filename", 'F');

        return $result;
    }

    public function get_email($idp) {
        $this->oracle->select('REG_CID, REG_FULLNAME, REG_USERNAME');
        $this->oracle->where('REG_CID', $idp);
        $query = $this->oracle->get('RTARFMAIL.REGISTER_TAB');

        return $query;
    }

    public function thai_month($mm) {
        $month['01'] = "มกราคม";
        $month['02'] = "กุมภาพันธ์";
        $month['03'] = "มีนาคม";
        $month['04'] = "เมษายน";
        $month['05'] = "พฤษภาคม";
        $month['06'] = "มิถุนายน";
        $month['07'] = "กรกฎาคม";
        $month['08'] = "สิงหาคม";
        $month['09'] = "กันยายน";
        $month['10'] = "ตุลาคม";
        $month['11'] = "พฤศจิกายน";
        $month['12'] = "ธันวาคม";

        return $month[$mm];
    }

    public function confirm_key($array) {
        $field['confirm_key']   = $array['key'];
        $field['round_id']      = $array['round_id'];
        $field['seat_number']   = $array['seat_number'];
        $field['time_create']   = date("Y-m-d H:i:s");
        $query = $this->mysql->insert('ecl2_confirm_key', $field);

        return $query;
    }

    public function check_confirm_key($key) {
        $this->mysql->where('confirm_key', $key);
        $query = $this->mysql->get('ecl2_confirm_key');

        return $query;
    }

    public function set_confirm($round_id, $seat_number) {
        $field['confirm']   = 'y';
        $this->mysql->where('round_id', $round_id);
        $this->mysql->where('seat_number', $seat_number);
        $query = $this->mysql->update('ecl2_register', $field);

        return $query;
    }

    public function get_round_test() {
        $this->mysql->select('distinct(round) as round');
        // $this->mysql->where('active', 'y');
        $this->mysql->order_by('round', 'desc');
        $query = $this->mysql->get('ecl2_round');

        return $query;
    }

    public function check_score($idp) {
        $md         = date("m-d");
        $currYear   = date("Y") ."-". $md;
        $agoYear    = date("Y")-2 ."-". $md;

        $this->mysql->select('a.name, a.unit_name, a.score_test
            ,b.round, b.date_test, b.time_test
            ,c.room_name');
        $this->mysql->join('ecl2_round b', 'a.round_id = b.row_id');
        $this->mysql->join('ecl2_room c', 'b.room_id = c.row_id');
        $this->mysql->where('a.idp', $idp);
        $this->mysql->where('a.score_test is not null');
        $this->mysql->where('a.score_test is not null');
        $this->mysql->where("b.date_test >= '$agoYear'");
        $this->mysql->where("b.date_test <= '$currYear'");
        $this->mysql->order_by('b.date_test desc, b.time_test desc');
        $query = $this->mysql->get('ecl2_register a');

        return $query;
    }

    public function cancel_registered($row_id) {
        $field['idp']           = null;
        $field['name']          = null;
        $field['email']         = null;
        $field['tel_number']    = null;
        $field['unit_code']     = null;
        $field['unit_name']     = null;
        $field['time_update']   = date("Y-m-d H:i:s");
        $field['user']          = "{$this->input->ip_address()}#". date("Y-m-d H:i:s");

        $this->mysql->where('row_id', $row_id);
        $query = $this->mysql->update('ecl2_register', $field);  

        return $query;
    }

    public function get_register_detail($id) {
        $this->mysql->select('a.idp, a.name, a.unit_name, a.seat_number,
            b.date_test, b.time_test, b.round,
            c.room_name');
        $this->mysql->join('ecl2_round b', 'a.round_id = b.row_id', 'left');
        $this->mysql->join('ecl2_room c', 'b.room_id = c.row_id', 'left');            
        $this->mysql->where('a.row_id', $id);
        $query = $this->mysql->get('ecl2_register a');
        // echo $this->mysql->last_query();

        return $query;
    }

    public function check_opened_round(Type $var = null) {
        $this->mysql->where('active', 'y');
        $query = $this->mysql->get('ecl2_round');

        return $query;
    }
}