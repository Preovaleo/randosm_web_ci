<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//======================================================================
// CLASSE ADMINISTRATION
//======================================================================
/* 
 * Gère tout la partie réservée aux administrateurs du site :
 * Promotion et rétrogradation des membres, suppression des comptes
 * Lecture, suppression et réponse au messages
 */

class Administration extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $state = $this->session->userdata('state');
        // Si l'utilisateur n'est pas un administrateur (state=1), on le redirige vers l'accueil
        if($state != "1"){
            redirect('index', 'refresh');
        }
        $this->load->model('user_model');
        $this->load->model('moderation_model');
        $this->load->model('admin_model');
        $this->load->model('hikes_model');
        $this->load->model('validation_model');
    }
    
    public function index()
    {        
        $data  = array();
        $data['titre'] = 'Rand\'OSM - Administration';
        $data['connected'] = $this->session->userdata('logged_in');
        $data['page_en_cours'] = 'administration';
        $data['status'] = $this->session->userdata('state');
        
        
        // Chargement des vues
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('administration', $data);
        $this->load->view('footer');
    }
    
    // Gestion de l'affichage de tous les messages
    public function messages()
    {        
        $data  = array();
        $data['titre'] = 'Administration - Messages';
        $data['connected'] = $this->session->userdata('logged_in');
        $data['page_en_cours'] = 'administration';
        $data['messages'] = $this->admin_model->getAllMessages();
        $data['status'] = $this->session->userdata('state');
        $data['mess'] = true; // Variable utilisée par la vue pour savoir quoi afficher
        
        // Chargement des vues
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('administration', $data);
        $this->load->view('footer');
    }
    
    // Fonction pour supprimer tous les messages cochés 
    public function delete_mess()
    {              
        $messages = $this->admin_model->getAllMessages();
        foreach($messages as $message) {
            if ($this->input->post($message->id)!=false) { // Correspond à une case cochée
                $this->admin_model->deleteMessageById($message->id);
            }
        }
        
        redirect('administration/messages', 'refresh');
    }
    
    // Répondre à un message
    public function answer($id) {
        $message = $this->admin_model->getMessage($id); // On récupère le message complet à partir de son id
        $data  = array();
        $data['titre'] = 'Répondre à un message';
        $data['connected'] = $this->session->userdata('logged_in');
        $data['page_en_cours'] = 'administration';
        $data['status'] = $this->session->userdata('state');
        $data['id'] = $id;
        // Le nom de l'expéditeur est par défaut le nom de l'administrateur (il peut le modifier)
        $data['name'] = $this->session->userdata('username'); 
        // Le titre est de la forme "Re:+ancien titre"
        $data['title'] = "Re:".$message->title; 
        // On préremplit le contenu du message avec quelques espaces suivis de l'ancien message
        $data['message'] = "\n\n\n\n ********************\n// Message reçu de ".$message->name." : \n".$message->message;       
        
        // Définition des conditions du formulaire
        $this->form_validation->set_rules('name', 'pseudo','trim|required|xss_clean|htmlspecialchars|max_length[16]');
        $this->form_validation->set_rules('title','titre','trim|required|xss_clean|htmlspecialchars|max_length[30]');
        $this->form_validation->set_rules('message','message','trim|required|xss_clean|htmlspecialchars|max_length[2000]');	
        
        // Si le formulaire ne retourne pas d'erreurs, on envoie le mail
        if($this->form_validation->run()) {
            $data['name'] = $this->input->post('name');
            $data['title'] = $this->input->post('title');
            $data['message'] = $this->input->post('message');
                    
            // Envoi du mail
            $this->load->library('email');
            $this->email->from('theobou@icare.pulseheberg.net',"\"".$data['name']." de Rand'OSM\"");
            $this->email->to($message->mail);
            $this->email->subject($data['title']);
            $this->email->message($data['message']);
            $this->email->send();       
            
            $success['success'] = 'Message envoyé !';			
            
            // Chargement des vues
            $this->load->view('header', $data);
            $this->load->view('menu', $data);
            $this->load->view('answer', $success);
            $this->load->view('footer');
            
        }
        else {    
            if (($this->input->post('name')!='')) {
                $data['name'] = $this->input->post('name');
                $data['title'] = $this->input->post('title');
                $data['message'] = $this->input->post('message');
            }
            
            // Chargement des vues
            $this->load->view('header', $data);
            $this->load->view('menu', $data);
            $this->load->view('answer', $data);
            $this->load->view('footer');
        }
    }
    
    // Gestion de l'affichage de tous les membres
    public function members()
    {        
        $data  = array();
        $data['titre'] = 'Administration - Membres';
        $data['connected'] = $this->session->userdata('logged_in');
        $data['page_en_cours'] = 'administration';
        $data['users'] = $this->admin_model->getAllUsers();
        $data['status'] = $this->session->userdata('state');
        $data['mess'] = false;
        
        // Chargement des vues
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('administration', $data);
        $this->load->view('footer');
    }
    
    // Fonction d'envoi d'un nouveau mail d'activation
    public function newMail($name) {
        $mail = $this->validation_model->getMail($name);
        $key = $this->validation_model->getKey($name);
        
        $this->load->library('email');
        $this->email->from('theobou@icare.pulseheberg.net',"\"Rand'OSM\"");
        $this->email->to($mail);
        $this->email->subject('Inscription Re');
        $this->email->message("Bonjour,\n\nVoici un nouveau lien pour pouvoir activer votre compte.\nVeuillez valider votre compte en cliquant sur ce lien :\n\n ".base_url()."validation?name=".urlencode($name)."&key=".urlencode($key)."\n\nSi ce mail ne vous est pas destiné, veuillez l'ignorer.\nCe mail a été envoyé automatiquement, veuillez ne pas y répondre.");
        $this->email->send();
        
        redirect('administration/members', 'refresh');    
    }
    
    // Suppression d'un compte, demande la confirmation
    public function confirm($userId, $confirm = false){
        if($this->user_model->isLoggedIn()){
            $data  = array();
            $data['titre'] = 'Supprimer un compte';
            $data['connected'] = $this->session->userdata('logged_in');
            $data['page_en_cours'] = 'administration';
            $data['status'] = $this->session->userdata('state');
            $data['conf'] = $confirm;
            $data['name'] = $this->user_model->getUserNameById($userId);
            $data['userId'] = $userId;
            
            if($confirm==true) { // Si l'administrateur confirme, on supprime le compte
                $this->admin_model->deleteUserById($userId);
            }
            $this->load->view('header', $data);
            $this->load->view('menu', $data);
            $this->load->view('confirm', $data);
            $this->load->view('footer');
        } else {
            redirect('login','refresh');
        }
    }
    
    // Promotion d'un membre, effective à sa prochaine connexion
    public function promote($id) {
        if($this->user_model->isLoggedIn()){
            $data  = array();
            $data['titre'] = 'Administration - Membres';
            $data['connected'] = $this->session->userdata('logged_in');
            $data['page_en_cours'] = 'administration';
            $data['status'] = $this->session->userdata('state');            
            
            $this->admin_model->promote($id);
            
            redirect('administration/members', 'refresh');            
        } else {
            redirect('login','refresh');
        }
    }
    
    // Rétrogradation d'un membre, effective à sa prochaine connexion
    public function demote($id) {
        if($this->user_model->isLoggedIn()){
            $data  = array();
            $data['titre'] = 'Administration - Membres';
            $data['connected'] = $this->session->userdata('logged_in');
            $data['page_en_cours'] = 'administration';
            $data['status'] = $this->session->userdata('state');            
            
            $this->admin_model->demote($id);
            
            redirect('administration/members', 'refresh');    
            
        } else {
            redirect('login','refresh');
        }
    }
}
