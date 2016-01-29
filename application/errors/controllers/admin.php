<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    function Admin() {
        parent::__construct();
        $this->load->model('user_model');
    }
    
    
    function index(){
        if($this->user_model->isLoggedIn()){
            redirect('admin/dashboard','refresh');
        } else {
            redirect('admin/login','refresh');
        }
    }
    
    
    function login(){
        if($this->user_model->isLoggedIn()){
            redirect('my_account','refresh');
        }
        else {
            
            //on charge la validation de formulaires
            $this->load->library('form_validation');
            
            //on définit les règles de succès
            $this->form_validation->set_rules('username','Login','required');
            $this->form_validation->set_rules('password','Mot de passe','required');
            
            //si la validation a échouée on redirige vers le formulaire de login
            if(!$this->form_validation->run()){
                $data  = array();
                $data['titre'] = 'Accueil';
                $data['connected'] = $this->session->userdata('logged_in');
                
                $this->load->view('header', $data);
                $this->load->view('menu', $data);
                $this->load->view('loginform', $data);
                $this->load->view('footer');
            }
            else {
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $validCredentials = $this->user_model->validCredentials($username,$password);
                
                if($validCredentials){
                    redirect('admin/dashboard','refresh');
                }
                else {
                    $data['error_credentials'] = 'Wrong Username/Password';
                    
                    $this->load->view('header');
                    $this->load->view('menu');
                    $this->load->view('loginform');
                    $this->load->view('footer');
                }
            }
        }
    }
    
    
    /* Charge la vue mon compte si bien connecté */
    function dashboard(){
        if($this->user_model->isLoggedIn()){
            $data  = array();
            $data['titre'] = 'Accueil';
            $data['connected'] = $this->session->userdata('logged_in');
            
            $this->load->view('header', $data);
            $this->load->view('menu', $data);
            $this->load->view('admin', $data);
            $this->load->view('footer');
        }
    }
}