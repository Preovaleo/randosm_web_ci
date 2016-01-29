<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {
    
    function Admin() {
        parent::__construct();
    }
    
    
    function index(){
        header('Content-type: text/html; charset=iso-8859-1');
        
        if(count($_POST) > 0) {
            echo "Données reçues en POST:";
            foreach($_POST as $v)
            echo strrev(utf8_decode($v)).":";
        }
    }
    
}