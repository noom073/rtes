<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/TCPDF-master/tcpdf.php';

    class Pdf extends TCPDF {

        public function __construct() {
            
        }
    }

?>