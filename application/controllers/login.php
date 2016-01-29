<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//======================================================================
// CLASSE LOGIN
//======================================================================
/* 
 * Charge le formulaire de connexion
 */

class Login extends CI_Controller {
    
    public function __construct(){
        parent::__construct();        
    }
    
    /* Page de connexion */
    public function index() {     
        $data['connected'] = $this->session->userdata('logged_in');
        $data['titre'] = 'Connexion';
        $data['page_en_cours'] = 'login';
        $data['status'] = null;
        
        
        // Chargement des vues
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('loginform');
        $this->load->view('footer');
    }
}