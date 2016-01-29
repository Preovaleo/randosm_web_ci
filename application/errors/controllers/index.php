<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('url'); 
        $this->load->helper('assets');
        
    }
    
    public function index()
    {
        $data  = array();
        $data['titre'] = 'Accueil';
        $data['connected'] = $this->session->userdata('logged_in');
        
        // Chargement des vues
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('footer');
    }
}