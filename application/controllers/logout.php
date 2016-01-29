<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//======================================================================
// CLASSE LOGOUT
//======================================================================
/* 
 * Déconnecte l'utilisateur, supprime sa session
 */

class Logout extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('url'); 
        $this->load->helper('assets');      
        
    }
    
    /* Page de déconnexion */
    public function index()
    {     
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('logged_in');
		$this->session->sess_destroy();
		redirect(site_url(),'refresh');
	}
}