<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Session_lib {
        var $CI;

        public function __construct() {
            $this->CI =& get_instance();
            $this->CI->load->library('session');
        }

        public function makeSecure($text, $type) {
            if ( $type == 'enc' ) {
                $find = array('+', '=', '/');
                $replace = array('.', '-', '~');

                $encrypted  = $this->CI->encryption->encrypt($text);
                $result  = str_replace($find, $replace, $encrypted);
            
            } else if( $type == 'dec' ) {
                $find = array('.', '-', '~');
                $replace = array('+', '=', '/');

                $decrypted  = str_replace($find, $replace, $text);
                $result     = $this->CI->encryption->decrypt($decrypted);

            } else $result = 'fail';
            
            return $result;
        }

        public function store_session($array) {
            $this->CI->session->set_userdata('token', $array['token']);
            $this->CI->session->set_userdata('username', $array['username']);
            $this->CI->session->set_userdata('type_user', $array['type']);

            return true;
        }

        public function check_session_age() {
            $this->CI->load->model('login_model');

            $token = $this->CI->session->userdata('token');
            $selectToken = $this->CI->login_model->check_token($token)->row();
            if ( (time() - strtotime($selectToken->time_create)) > (60*60)) {
                redirect('main/index');
                $result = false;
            } else {
                $result = true;
            }
            
            return $result;
        }
    }

?>