<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Secure_lib {
        var $CI;

        public function __construct() {
            $this->CI =& get_instance();
            $this->CI->load->library('encryption');
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

    }

?>