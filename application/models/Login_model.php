<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    var $mysql, $oracle;

    public function __construct() {
        parent::__construct();        

        $this->mysql  = $this->load->database('mysql', true);
        $this->oracle = $this->load->database('person1', true);
    }

	public function get_login($username, $password) {
        // $query = $this->oracle->query('select * from tab');
        $this->mysql->select('*');
        $this->mysql->where('username', $username);
        $this->mysql->where('password', $password);
        $query = $this->mysql->get('ecl2_user');
        
        return $query;
    }
    
    public function insert_token($array) {
        $data['token']          = $array['token'];
        $data['username']       = $array['username'];
        $data['type_user']      = $array['type'];
        $data['time_create']    = date("Y-m-d H:i:s");
        $data['active']         = 'y';

        $query = $this->mysql->insert('ecl2_token', $data);

        return $query;
    }

    public function get_logout($token) {
        $data['time_update']    = date("Y-m-d H:i:s");
        $data['active']         = 'n';

        $this->mysql->where('token', $token);
        $query = $this->mysql->update('ecl2_token', $data);

        return $query;
    }

    public function check_token($token) {
        $this->mysql->where('token', $token);
        $this->mysql->where('active', 'y');
        $query = $this->mysql->get('ecl2_token');

        return $query;
    }
}