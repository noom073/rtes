<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Score_model extends CI_Model {

    var $mysql, $oracle;

    public function __construct(Type $var = null) {
        $this->mysql  = $this->load->database('mysql', true);
        $this->oracle = $this->load->database('person1', true);
    }

    public function get_person_detail($idp) {
        $this->oracle->select('BIOG_NAME, BIOG_ID, BIOG_IDP,BIOG_UNITNAME');
        $this->oracle->where('BIOG_IDP', $idp);
        $query = $this->oracle->get('PER_BIOG_VIEW');

        return $query;
    }

    public function check_in_registered($idp, $round) {
        $this->mysql->where('idp', $idp);
        $this->mysql->where('round', $round);
        $query = $this->mysql->get('ecl2_register');

        return $query;
    }

    public function insert_score($idp, $round, $score) {
        $field['score_test']    = $score;
        $field['time_update']   = date("Y-m-d H:i:s");
        $field['user']          = "{$this->session->username}#{$this->input->ip_address()}";

        $this->mysql->where('idp', $idp);
        $this->mysql->where('round', $round);
        $query = $this->mysql->update('ecl2_register', $field);

        return $query;
    }

}