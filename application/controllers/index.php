<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//======================================================================
// CLASSE INDEX
//======================================================================
/* 
 * Correspond à l'accueil du site,
 * Gère également les pages secondaires 
 */

class Index extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('url'); 
        $this->load->helper('assets');
        $this->load->model('user_model');     
    }
    
    public function index()
    {
        $data  = array();
        $data['titre'] = 'Rand\'OSM - Accueil';
        $data['connected'] = $this->session->userdata('logged_in');
        $data['page_en_cours'] = 'accueil';
        $data['status'] = "0";
        $data['status'] = $this->session->userdata('state');        
        
        // Chargement des vues
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('accueil', $data);
        $this->load->view('footer');
    }
    
    // Charge les mentions légales
    public function legal() {
        $data  = array();
        $data['titre'] = 'Mentions légales';
        $data['page_en_cours'] = 'legal';
        $data['connected'] = $this->session->userdata('logged_in');
        $data['status'] = $this->session->userdata('state');        
        
        // Chargement des vues
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('legal', $data);
        $this->load->view('footer');        
    }
    
    // Charge la vue OSM
    public function osm() {
        $data  = array();
        $data['titre'] = 'OpenStreetMap';
        $data['connected'] = $this->session->userdata('logged_in');
        $data['page_en_cours'] = 'osm';
        $data['status'] = $this->session->userdata('state');
        
        // Chargement des vues
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('osm', $data);
        $this->load->view('footer');
    }
    
    // Charge la page aboutus
    public function aboutus() {
        $data  = array();
        $data['titre'] = 'Qui sommes-nous ?';
        $data['connected'] = $this->session->userdata('logged_in');
        $data['page_en_cours'] = 'aboutus';
        $data['status'] = $this->session->userdata('state');
        
        // Chargement des vues
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('aboutus', $data);
        $this->load->view('footer');
    }
    
    // Charge la page faq
    public function faq() {
        $data  = array();
        $data['titre'] = 'FAQ';
        $data['connected'] = $this->session->userdata('logged_in');
        $data['page_en_cours'] = 'faq';
        $data['status'] = $this->session->userdata('state');
        
        // Chargement des vues
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('faq', $data);
        $this->load->view('footer');
    }
    
    // Charge la page donate
    public function donate() {
        $data  = array();
        $data['titre'] = 'Donation';
        $data['connected'] = $this->session->userdata('logged_in');
        $data['page_en_cours'] = 'donate';
        $data['status'] = $this->session->userdata('state');
        
        // Chargement des vues
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('donate', $data);
        $this->load->view('footer');
    }
    
    // Gère la page contact, le formulaire et l'ajout du message dans la base de données
    public function contact() {
        $this->load->library('recaptcha');
        // On définit les règles du formulaire
        $this->form_validation->set_rules('name', 'pseudo','trim|required|xss_clean|htmlspecialchars|max_length[16]');
        $this->form_validation->set_rules('email','email','trim|required|xss_clean|valid_email|htmlspecialchars|max_length[30]');
        $this->form_validation->set_rules('title','titre','trim|required|xss_clean|htmlspecialchars|max_length[30]');
        $this->form_validation->set_rules('message','message','trim|required|xss_clean|htmlspecialchars|max_length[2000]');
        $data = array(
                        'name' => $this->session->userdata('username'),
                        'mail' => $this->session->userdata('mail'),
                        'title' => '',
                        'message' => '',
                        'page_en_cours' => 'contact',
                        'recaptcha_html' => $this->recaptcha->recaptcha_get_html()
        );		
        
        $this->recaptcha->recaptcha_check_answer();
        // Si le formulaire est correct et le captcha est valide
        if($this->form_validation->run() && $this->recaptcha->getIsValid()) {
            
            $data = array(
                        'name'=>$this->input->post('name'),
                        'mail'=>$this->input->post('email'),
                        'title'=>$this->input->post('title'),
                        'message'=>$this->input->post('message')
                );
            // Ajout du message dans la BDD
            $this->user_model->contact($data);
            
            $success['success'] = 'Message envoyé !';			

            $data2 = array(
                        'titre'=> 'Contact',
                        'connected'=> $this->session->userdata('logged_in'),
                        'page_en_cours' => 'contact',
                        'status' => $this->session->userdata('state')
            );
            
            // Chargement des vues
            $this->load->view('header', $data2);
            $this->load->view('menu', $data2);
            $this->load->view('contact', $success);
            $this->load->view('footer');
            
        }
        else {    
            if (($this->input->post('name')!='') && !$this->recaptcha->getIsValid()) {
            $data = array(
                        'name'=>$this->input->post('name'),
                        'mail'=>$this->input->post('email'),
                        'title'=>$this->input->post('title'),
                        'message'=>$this->input->post('message'),
                        'recaptcha_html' => $this->recaptcha->recaptcha_get_html(),
                        'error_captcha' => "Captcha invalide"
            );
            }
            
            $data2  = array();
            $data2['titre'] = 'Donation';
            $data2['connected'] = $this->session->userdata('logged_in');
            $data2['page_en_cours'] = 'contact';
            $data2['status'] = $this->session->userdata('state');
            
            // Chargement des vues
            $this->load->view('header', $data2);
            $this->load->view('menu', $data2);
            $this->load->view('contact', $data);
            $this->load->view('footer');
        }
    }
}