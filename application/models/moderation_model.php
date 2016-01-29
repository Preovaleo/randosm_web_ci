<?php
class Moderation_model extends CI_Model
{
    function __construct() {
        parent::__construct();
        $state = $this->session->userdata('state');
        if($state != "1" && $state != "2"){
            redirect('index', 'refresh');
        }
        $this->load->model('hikes_model');
    }
    
    function getUnmoderatedHikes() {
            $this->db->select('randonnee_id, name, city, type, difficulty, time, distance, description, date_of_creation, creator');
            $this->db->from('randonnee');
            $this->db->where('moderated', '0');
            $this->db->order_by("date_of_creation"); 
            $query = $this->db->get();
            
            $res =  $query->result();
            
            if($query->num_rows() > 0){
                foreach ($res as $hike) {
                    $hike->city = $this->hikes_model->getCityNameById($hike->city);
                    $hike->creator = $this->user_model->getUserNameById($hike->creator);
                }
                return $query->result();
            }
        }
    
    
    function changeHikeState($hikeId, $state) {
            $data = array(
               'moderated' => $state
            );
            $this->db->where('randonnee_id', $hikeId);
            $this->db->update('randonnee', $data);
            redirect('moderation', 'refresh');   
    }
}