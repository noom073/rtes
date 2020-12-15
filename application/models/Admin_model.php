<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    var $mysql, $oracle;

    public function __construct(Type $var = null) {
        $this->mysql  = $this->load->database('mysql', true);
        $this->oracle = $this->load->database('person1', true);
    }


    public function list_rooms() {
        $query = $this->mysql->get('ecl2_room');

        return $query;
    }

    public function check_dup_room($array) {
        $this->mysql->where('room_name', $array['room_name']);        
        $query = $this->mysql->get('ecl2_room');

        return $query;
    }

    public function check_room_before_update($array) {
        $this->mysql->where('room_name', $array['room_name']);        
        $this->mysql->where('row_id <>', $array['row_id']);        
        $query = $this->mysql->get('ecl2_room');
        // echo $this->mysql->last_query();
        return $query;
    }
    
    public function insert_room($array) {
        $field['room_name']     = $array['room_name'];
        $field['address']       = $array['address'];
        $field['time_create']   = date("Y-m-d H:i:s");
        $field['user']          = "{$this->session->username}#{$this->input->ip_address()}";

        $query = $this->mysql->insert('ecl2_room', $field);

        return $query;
    }
    public function update_room($array) {
        $field['room_name']     = $array['room_name'];
        $field['address']       = $array['address'];
        $field['time_update']   = date("Y-m-d H:i:s");
        $field['user']          = "{$this->session->username}#{$this->input->ip_address()}";

        $this->mysql->where('row_id', $array['row_id']);
        $query = $this->mysql->update('ecl2_room', $field);

        return $query;
    }

    public function check_room_in_round($row_id) {
        $this->mysql->where('room_id', $row_id);
        $query = $this->mysql->get('ecl2_round');

        return $query;
    }

    public function delete_room($row_id) {
        $this->mysql->where('row_id', $row_id);
        $query = $this->mysql->delete('ecl2_room');

        return $query;
    }
}