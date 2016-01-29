<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('url'); 
        $this->load->helper('assets');
        
        
    }
    
    /* Page de connexion */
    public function index()
    {     
        
        $data['connected'] = $this->session->userdata('logged_in');
        $data['titre'] = ('Connexion');
        
        // Chargement des vues
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        
        $this->load->view('loginform');
        
        $this->load->view('footer');
        
    }
}