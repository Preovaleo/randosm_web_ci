<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_account extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('url'); 
        $this->load->helper('assets');
        
    }
    
    public function index()
    {
        $data=array();
        $data['titre'] = 'Accueil';
        $data['connected'] = $this->session->userdata('logged_in');
        
        
        if($this->session->userdata('logged_in')){
            $this->load->view('header', $data);
            $this->load->view('menu', $data);
            $this->load->view('my_account', $data);
            echo "Mon compte";
            $this->load->view('footer');
        }
        else {
            redirect('index', 'refresh');
        }
        
    }
}