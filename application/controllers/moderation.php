<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//======================================================================
// CLASSE MODERATION
//======================================================================
/* 
 * Gère tout la partie réservée aux modérateurs du site :
 * Voir toutes les randonnées en attente de validation et
 * valider ou refuser ces randonnées
 */

class Moderation extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $state = $this->session->userdata('state');
        // Si l'utilisateur n'est pas un administrateur ou un modérateur, on le redirige vers l'accueil
        if($state != "1" && $state != "2"){
            redirect('index', 'refresh');
        }
        $this->load->model('user_model');
        $this->load->model('moderation_model');
        $this->load->model('hikes_model');
    }
    
    function index()
    {   
        $data  = array();
        $data['titre'] = 'Rand\'OSM - Accueil';
        $data['connected'] = $this->session->userdata('logged_in');
        $data['page_en_cours'] = 'moderation';
        // Récupération des randonnées non validées
        $data['unmoderatedHikes'] = $this->moderation_model->getUnmoderatedHikes();
        $data['status'] = $this->session->userdata('state');
        
        // Chargement des vues
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('moderation', $data);
        $this->load->view('footer');
    }
    
    // Change l'état d'une randonnée à partir des données passées en paramètres
    public function changeHikeState($hikeId, $state)
    {
        $this->moderation_model->changeHikeState($hikeId, $state);
    }
}