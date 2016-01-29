<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//======================================================================
// CLASSE MY_HIKES
//======================================================================
/* 
 * Gère la partie "Mes randonnées"
 * Affichage de toutes les randonnées de l'utilisateur,
 * leur statut et attributs, ainsi que leur modification et suppression, 
 * et la création de nouvelles randonnées
 */

class My_hikes extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('url'); 
        $this->load->helper('assets');
        
        $this->load->model('hikes_model');
        $this->load->model('user_model');
        $this->load->helper('directory');
    }
    
    public function index() 
    {
        if($this->user_model->isLoggedIn()){
            $data  = array();
            $data['titre'] = 'Mes randonnées';
            $data['connected'] = $this->session->userdata('logged_in');
            $data['page_en_cours'] = 'MesRandonnees';
            $data['status'] = $this->session->userdata('state');
            
            $data['userHikes'] = $this->user_model->getUserHikes();
            
            
            // Chargement des vues
            $this->load->view('header', $data);
            $this->load->view('menu', $data);
            $this->load->view('my_hikes', $data);
            $this->load->view('footer');
        } else {
            redirect('login','refresh');
        }
    }
    
    // Charge la partie création de randonnée
    public function create_hike()
    {        
        if($this->user_model->isLoggedIn()){
            $data  = array();
            $data['titre'] = 'Créer une randonnée';
            $data['connected'] = $this->session->userdata('logged_in');
            $data['page_en_cours'] = 'MesRandonnees';
            $data['status'] = $this->session->userdata('state');
            
            // Chargement des vues
            $this->load->view('header', $data);
            $this->load->view('menu', $data);
            $this->load->view('create_hike', $data);
            $this->load->view('footer');
        } else {
            redirect('admin/login','refresh');
        }
    }
    
    // Supprime la randonnée dont l'id est passé en paramètre
    public function delete($hikeId)
    {
        if($this->user_model->isLoggedIn()){
            $userHikes = $this->user_model->getUserHikes($hikeId);
            $checkHikeBelonging = false;
            foreach ($userHikes as $hike) {
                if($hikeId == $hike->randonnee_id) { // On vérifie que la randonnée appartient bien à l'utilisateur
                    $hikeParameters = $this->user_model->getUserHike($hikeId);
                    $checkHikeBelonging = true;
                }
            }
            
            if($checkHikeBelonging) {
                $this->hikes_model->delete_hike($hikeId);
                redirect('my_hikes/','refresh');
            } else {
                redirect('my_hikes/','refresh');
            }
        }
    }
    
    // Modifie la randonnée dont l'id est passé en paramètre
    public function modify($hikeId) {
        if($this->user_model->isLoggedIn()){
            $newValues = array(
                'name' => $this->input->post('name-field'),
                'type' => $this->input->post('type-field'),
                'time' => $this->input->post('time-field'),
                'city' => $this->input->post('city-field'),
                'distance' => $this->input->post('distance-field'),
                'hikeId' => $hikeId
            );
            
            $userHikes = $this->user_model->getUserHikes($hikeId);
            $checkHikeBelonging = false;
            foreach ($userHikes as $hike) {
                if($hikeId == $hike->randonnee_id) { // On vérifie que la randonnée appartient bien à l'utilisateur
                    $checkHikeBelonging = true;
                }
            }
            
            if($checkHikeBelonging) {
                print_r($this->hikes_model->modify_hike($newValues));
            }
            
            redirect('my_hikes/','refresh');
        } else {
            redirect('admin/login','refresh');
        }
    }
    
    // Retourne le contenu du fichier markers.json (coordonnées des points de la randonnée et commentaires) pour la randonnée dont l'id est passé en paramètre
    public function getHikeInfos($hikeId) {
        
        $zip = new ZipArchive;
        $zipUrl = '../hiking/'.$hikeId.'.zip';
        if ($zip->open($zipUrl) === TRUE) {
            //            for ($i = 0; $i < 4; $i++) {
            //                echo $zip->getNameIndex($i) . '<br />';
            //            }
            echo $zip->getFromName('./markers.json');
            //            for( $i = 0; $i < $zip->numFiles; $i++ ){ 
            //                $stat = $zip->statIndex($i); 
            //                print_r($stat);
            //            }
            
        } else {
            echo 'échec';
        }
        $zip->close();
    }

    // Retourne un tableau contenant les liens des photos associées à la randonnée don l'id est passé en paramètre
    public function getHikePictures($hikeId) {
        $picturesLinks = array();
        
        $za = new ZipArchive(); 
        
        $za->open('../hiking/'.$hikeId.'.zip'); 
        
        for( $i = 0; $i < $za->numFiles; $i++ ){ 
            $stat = $za->statIndex($i); 
            //            print_r(basename( $stat['name'] ) . PHP_EOL ); 
            if(strstr($stat['name'], 'media/photo/') && strstr($stat['name'], '.') && !strstr($stat['name'], '/.') && $stat['name'] != "./media/photo/"){
                $za->extractTo('../hiking/temporaire/', array($stat['name']));
                array_push($picturesLinks, "http://zip.theobouge.eu/temporaire/" . $stat['name']);
            }
            
        }
        
        echo json_encode($picturesLinks);
    }
    
    // Retourne la liste des villes pour l'autocomplétion
    function suggestions() {
        $term = $this->input->post('term',TRUE);
        
        if (strlen($term) < 2) break;
        
        $rows = $this->hikes_model->GetAutocomplete(array('keyword' => $term));        
        
        $json_array = array();
        foreach ($rows as $row)
            array_push($json_array, $row->libCity . " (". $row->numDep . ")");
        
        echo json_encode($json_array);
    }
    
    function checkCity() { // Vérifie que la ville entrée est bien référencée dans la base de donnée
        $cityField = explode(" ", $this->input->post('cityField',TRUE));
        $query = $this->hikes_model->checkCity($cityField[0]);
        if($this->hikes_model->checkCity($cityField[0])=="true"){
            echo "true";
        } else {
            echo "false";
        }
    }
}