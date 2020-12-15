<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manage_round_model extends CI_Model
{

    var $mysql, $oracle;

    public function __construct()
    {
        $this->mysql  = $this->load->database('mysql', true);
        $this->oracle = $this->load->database('person1', true);
    }

    public function list_rounds()
    {
        $query = $this->mysql->query("SELECT a.row_id, a.round, a.date_test, a.time_test, a.time_create, 
            a.time_update, a.active, a.total_seat as total,
            (
            select count(*)
            from ecl2_register c
            where c.active = 'y'
            and c.round_id = a.row_id 
            and c.idp is not null
            ) as member,
            b.room_name
            FROM ecl2_round a 
            LEFT JOIN ecl2_room b 
                ON a.room_id = b.row_id 
            group by a.row_id
            ORDER BY a.date_test DESC, a.time_test DESC");
        // echo $this->mysql->last_query();
        return $query;
    }

    public function check_dup_round($array)
    {
        $this->mysql->where('round', $array['round']);
        $this->mysql->where('room_id', $array['room_id']);
        $this->mysql->where('date_test', $array['date']);
        $this->mysql->where('time_test', $array['time']);

        $query = $this->mysql->get('ecl2_round');

        return $query;
    }

    public function check_dup_round_before_update($array)
    {
        $this->mysql->not_like('row_id', $array['round_id']);
        $this->mysql->where('round', $array['round']);
        $this->mysql->where('room_id', $array['room_id']);
        $this->mysql->where('date_test', $array['date']);
        $this->mysql->where('time_test', $array['time']);

        $query = $this->mysql->get('ecl2_round');

        return $query;
    }

    public function insert_round($array)
    {
        $field['round']         = $array['round'];
        $field['room_id']       = $array['room_id'];
        $field['date_test']     = $array['date'];
        $field['time_test']     = $array['time'];
        $field['active']        = 'y';
        $field['total_seat']    = $array['amountSeat'];
        $field['time_create']   = date("Y-m-d H:i:s");
        $field['user']          = "{$this->session->username}#{$this->input->ip_address()}";
        $query['round'] = $this->mysql->insert('ecl2_round', $field);
        if ($this->mysql->trans_status() === FALSE) {
            $this->mysql->trans_rollback();
            $result = false;
        } else {
            $this->mysql->trans_commit();
            $result = true;
        }
        return $result;

        // $this->mysql->trans_begin();
        // $field['round']         = $array['round']; 
        // $field['room_id']       = $array['room_id']; 
        // $field['date_test']     = $array['date']; 
        // $field['time_test']     = $array['time']; 
        // $field['active']        = 'y'; 
        // $field['time_create']   = date("Y-m-d H:i:s");
        // $field['user']          = "{$this->session->username}#{$this->input->ip_address()}";
        // $amountSeats = $array['amountSeat'];  
        // $query['round'] = $this->mysql->insert('ecl2_round', $field);
        // $insert_id = $this->mysql->insert_id();
        // for ($i=0; $i < $amountSeats; $i++) { 
        //     $registerField['seat_number']   = $i+1;
        //     $registerField['confirm']       = 'n';
        //     $registerField['round_id']      = $insert_id;
        //     $registerField['round']         = $field['round'];
        //     $registerField['active']        = 'y';
        //     $registerField['time_create']   = date("Y-m-d H:i:s");
        //     $registerField['user']          = "{$this->session->username}#{$this->input->ip_address()}";

        //     $query['register'] = $this->mysql->insert('ecl2_register', $registerField);
        // }
        // if ($this->mysql->trans_status() === FALSE) {
        //     $this->mysql->trans_rollback();
        //     $result = false;
        // } else {
        //     $this->mysql->trans_commit();
        //     $result = true;
        // }
        // return $result;
    }

    public function list_room()
    {
        $this->mysql->select('row_id, room_name');
        $query = $this->mysql->get('ecl2_room');

        return $query;
    }

    public function update_round($array)
    {
        $field['date_test']     = $array['date'];
        $field['time_test']     = "{$array['hour']}:{$array['minute']}:00";
        $field['room_id']       = $array['room'];
        $field['round']         = $array['round'];
        $field['total_seat']    = $array['totalSeat'];
        $field['time_update']   = date("Y-m-d H:i:s");
        $field['user']          = "{$this->session->username}#{$this->input->ip_address()}";

        $chkData['round'] = $field['round'];
        $chkData['round_id'] = $field['round_id'];
        $chkData['room_id'] = $field['room_id'];
        $chkData['date'] = $field['date_test'];
        $chkData['time'] = $field['time_test'];
        $checkDuplicatRound = $this->check_dup_round_before_update($chkData)->num_rows();
        if ($checkDuplicatRound == 0) {
            $this->mysql->where('row_id', $array['round_id']);
            $query = $this->mysql->update('ecl2_round', $field);
            return $query;
        } else {
            return false;
        }
    }

    public function get_registered_data($id)
    {
        $this->mysql->select('a.row_id, a.idp, a.name, a.unit_name, a.tel_number, a.seat_number, a.confirm, 
            a.active, a.time_user_register,
            b.date_test, b.time_test, b.round,
            c.room_name, c.address');
        $this->mysql->join('ecl2_round b', 'a.round_id = b.row_id', 'left');
        $this->mysql->join('ecl2_room c', 'b.room_id = c.row_id', 'left');
        $this->mysql->where('a.round_id', $id);
        $query = $this->mysql->get('ecl2_register a');
        // echo $this->mysql->last_query();

        return $query;
    }

    public function disable_seat($row_id)
    {

        $field['active']        = 'n';
        $field['time_update']   = date("Y-m-d H:i:s");
        $field['user']          = "{$this->session->username}#{$this->input->ip_address()}";

        $this->mysql->where('row_id', $row_id);
        $query = $this->mysql->update('ecl2_register', $field);
        // echo $this->mysql->last_query();

        return $query;
    }

    public function enable_seat($row_id)
    {

        $field['active']        = 'y';
        $field['time_update']   = date("Y-m-d H:i:s");
        $field['user']          = "{$this->session->username}#{$this->input->ip_address()}";

        $this->mysql->where('row_id', $row_id);
        $query = $this->mysql->update('ecl2_register', $field);
        // echo $this->mysql->last_query();

        return $query;
    }

    public function clear_seat($row_id)
    {

        $field['idp']           = null;
        $field['name']          = null;
        $field['email']         = null;
        $field['tel_number']    = null;
        $field['unit_code']     = null;
        $field['unit_name']     = null;
        $field['time_update']   = date("Y-m-d H:i:s");
        $field['user']          = "{$this->session->username}#{$this->input->ip_address()}";

        $this->mysql->where('row_id', $row_id);
        $query = $this->mysql->update('ecl2_register', $field);

        return $query;
    }

    public function disable_round($row_id)
    {

        $field['active']        = 'n';
        $field['time_update']   = date("Y-m-d H:i:s");
        $field['user']          = "{$this->session->username}#{$this->input->ip_address()}";

        $this->mysql->where('row_id', $row_id);
        $query = $this->mysql->update('ecl2_round', $field);

        return $query;
    }

    public function enable_round($row_id)
    {

        $field['active']        = 'y';
        $field['time_update']   = date("Y-m-d H:i:s");
        $field['user']          = "{$this->session->username}#{$this->input->ip_address()}";

        $this->mysql->where('row_id', $row_id);
        $query = $this->mysql->update('ecl2_round', $field);

        return $query;
    }

    public function get_register_detail($id)
    {
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

    public function check_round_in_register($row_id)
    {
        $this->mysql->where("(idp is not null and idp not like '')");
        $this->mysql->where('round_id', $row_id);
        $query = $this->mysql->get('ecl2_register');
        // echo $this->mysql->last_query();

        return $query;
    }

    public function delete_round($row_id)
    {
        $this->mysql->trans_begin();
        $register_field['round_id'] = $row_id;
        $this->mysql->delete('ecl2_register', $register_field);

        $round_field['row_id'] = $row_id;
        $this->mysql->delete('ecl2_round', $round_field);

        if ($this->mysql->trans_status() === FALSE) {
            $this->mysql->trans_rollback();
            $result = false;
        } else {
            $this->mysql->trans_commit();
            $result = true;
        }


        return $result;
    }
}
