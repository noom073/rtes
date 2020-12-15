<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search_model extends CI_Model {

    var $mysql;

    public function __construct(Type $var = null) {
        $this->mysql  = $this->load->database('mysql', true);
    }

    public function get_unitname() {
        $this->mysql->select('distinct(unit_name) as unitname');
        $this->mysql->where('unit_name is not null');
        $this->mysql->order_by('unitname');
        $query = $this->mysql->get('ecl2_register');

        return $query;
    }

    public function get_time_test() {
        $this->mysql->select('distinct(time_test) as time');
        $this->mysql->order_by('time');
        $query = $this->mysql->get('ecl2_round');

        return $query;
    }

    public function search_score($search_score) {
        $this->mysql->select('a.name, a.unit_name, a.score_test, b.date_test, b.time_test');
        $this->mysql->join('ecl2_round b', 'a.round_id = b.row_id');

        if ($search_score['idp'] != '') {
            $this->mysql->where('a.idp', $search_score['idp']);
        }
        if ($search_score['unitname'] != '') {
            $this->mysql->where('a.unit_name', $search_score['unitname']);
        }
        if ($search_score['date'] != '') {
            $mixDate = explode('-', $search_score['date']);
            $year = $mixDate[2]-543;
            $month = $mixDate[1];
            $day = $mixDate[0];
            $date = "{$year}-{$month}-{$day}";

            $this->mysql->where('b.date_test', $date);
        }
        if ($search_score['time'] != '') {
            $this->mysql->where('b.time_test', $search_score['time']);
        }

        $this->mysql->where('a.score_test is not null');
        $query = $this->mysql->get('ecl2_register a');
        // echo $this->mysql->last_query();

        return $query;
    }

}