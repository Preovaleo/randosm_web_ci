<?php
class Signup_model extends CI_Model
{
    function __construct() {
        parent::__construct();
    }
    
    function signup($data) {
        $this->db->insert('user',$data);        
    }
}