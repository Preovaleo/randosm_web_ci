<?php
class Hikes_model extends CI_Model
{
    function __construct() {
        parent::__construct();
    }
    
    function create_hike($data) {
        $requete = $this->db->insert('randonnee', $data);
        return $this->db->insert_id();
    }
    
    function modify_hike($data) { // Le paramètre $data de cette fonction contient $hikeId et les données du tableau ci-dessous
        
        $data2= array();
        if($data['name'])
        $data2['name'] = $data['name'];
        if($data['type'])
        $data2['type'] = $data['type'];
        if($data['time'])
        $data2['time'] = $data['time'];
        if($data['city'])
        $data2['city'] = $this->getCityIdByName($data['city']);
        if($data['distance'])
        $data2['distance'] = $data['distance'];
        $data2['difficulty'] = $data['difficulty'];
        //        $data2['description'] = $data['description'];
        
        $this->db->where('randonnee_id', $data['hikeId']);
        $this->db->update('randonnee', $data2);
    }
    
    function delete_hike($hikeId) {
        $this->db->delete('randonnee', array('randonnee_id' => $hikeId)); 
    }   
    
    function GetAutocomplete($options = array()) {
        $this->db->select('libCity, numDep');
        $this->db->like('libCity', $options['keyword'], 'after');
        $query = $this->db->get('city');
        return $query->result();
    } 
    
    function getCityIdByName($name){
        $name = explode(" ", $name)[0];
        $this->db->select('idCity');
        $this->db->from('city');
        $this->db->where('libCity', $name);
        
        $query = $this->db->get();
        if($query->num_rows() === 1){
            return $query->result()[0]->idCity;
        }
    }
    function getCityNameById($id){   
        $this->db->select('libCity, numDep');
        $this->db->from('city');
        $this->db->where('idCity', $id);
        
        $query = $this->db->get();
        
        foreach ($query->result() as $row){
            return $row;
        }
    }
    function checkCity($cityField){ // Vérifie que la ville entrée est bien une ville contenue dans la base de donnée   
        $this->db->select('libCity');
        $this->db->from('city');
        $this->db->where('libCity', $cityField);
        
        $query = $this->db->get();
        $row = $query->row();
        if($query->num_rows() === 1){
            return "true";
        }
    }
}