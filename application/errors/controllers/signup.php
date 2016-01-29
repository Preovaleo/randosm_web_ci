<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller {
    
    public function __construct(){
        parent::__construct();   

    }
    
    public function index()
    {        
        $this->form_validation->set_rules('pseudo','pseudo','trim|required|xss_clean|is_unique[user.name]');
        $this->form_validation->set_rules('email','email','trim|required|xss_clean|valid_email|is_unique[user.mail]');
        $this->form_validation->set_rules('pass','mot de passe','trim|required|xss_clean|min_length[5]');
        $this->form_validation->set_rules('pass2','vérification du mot de passe','trim|required|matches[pass]');
        
        if($this->form_validation->run()) {
            
            $data = array(
                        'name'=>$this->input->post('pseudo'),
                        'mail'=>$this->input->post('email'),
                        'password'=>sha1($this->input->post('pass'))
                );
            
            $this->signup_model->signup($data);
            
            $this->email->from('contact@randosm.fr', 'Rand\'OSM');
            $this->email->to($this->input->post('mail'), 'Inscription');
            $this->email->subject('Inscription');
            $this->email->message('Bienvenue ! BLABLA');
            $this->email->send();
            
            $data['success'] = 'Inscription réussie';

            // Chargement des vues
            $this->load->view('header');
            $this->load->view('menu');
            $this->load->view('signup', $data);
            $this->load->view('footer');
            
        }
        else {        
         // Chargement des vues
            $this->load->view('header');
            $this->load->view('menu');
            $this->load->view('signup');
            $this->load->view('footer');
        }
    }  
}