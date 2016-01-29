<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//======================================================================
// CLASSE MODIFY_HIKE
//======================================================================
/* 
 * Gère la partie modification des randonnées de l'utilisateur
 * Nom, ville, description, étapes, etc..
 */

class Modify_hike extends CI_Controller {
    
    public function __construct(){  
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('hikes_model'); 
    }
    
    // Modifie la randonnée dont l'id est passé en paramètre
    public function modify($hikeId)
    {        
        // On définit les conditions du formulaire
        $this->form_validation->set_rules('name', 'nom','required|xss_clean|htmlspecialchars');
        $this->form_validation->set_rules('city','ville associée','required|xss_clean|htmlspecialchars');
        $this->form_validation->set_rules('distance','longueur','required|xss_clean|htmlspecialchars');
        $this->form_validation->set_rules('time','durée','required|xss_clean|htmlspecialchars');
        $this->form_validation->set_rules('description','description','required|xss_clean|htmlspecialchars');
        
        // Si le formulaire ne renvoie pas d'erreurs
        if($this->form_validation->run()) {
            
            $data = array(
                'hikeId'=>$hikeId,
                'name'=>$this->input->post('name'),
                'type'=>$this->input->post('type'),
                'difficulty'=>$this->input->post('difficulty'),
                'time'=>$this->input->post('time'),
                'distance'=>$this->input->post('distance'),
                'description'=>$this->input->post('description'),
                'creator'=>$this->session->userdata('userId'),
                //'city'=>$this->input->post('city'),
            );
            
            // On modifie la randonnée avec des nouvelles données
            $this->hikes_model->modify_hike($data);
            
            $data['success'] = 'Création réussie';		
            
            // Chargement des vues
            $data2 = array(
                'titre'=> 'Mes randonnées',
                'page_en_cours'=> 'MesRandonnees',
                'connected'=> $this->session->userdata('logged_in'),
            );
            $data2['status'] = $this->session->userdata('state');
            
            redirect('my_hikes','refresh');
        }
        else {        
            
            if($this->user_model->isLoggedIn()){
                
                $data = array(
                    'titre'=>'Créer une randonnée',
                    'page_en_cours'=>'MesRandonnees',
                    'connected'=> $this->session->userdata('logged_in'),
                );
                $data['status'] = $this->session->userdata('state');
                
                // Chargement des vues
                $this->load->view('header', $data);
                $this->load->view('menu', $data);
                $this->load->view('create_hike');
                $this->load->view('footer');
            } else {
                redirect('admin/login','refresh');
            }  
        }
    }
    
    // Fonction dédiée à la modification des étapes de la randonnée
    public function modify_steps($hikeId) {
        $data = array(
                    'titre'=>'Créer une randonnée',
                    'page_en_cours'=>'MesRandonnees',
                    'connected'=> $this->session->userdata('logged_in'),
        );
        $data['status'] = $this->session->userdata('state');
        
        // Chargement des vues
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('create_hike/modify_steps');
        $this->load->view('footer');
    }
}