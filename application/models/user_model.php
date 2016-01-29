<?php
class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    function User_model(){
        $this->username = '';
        $this->load->model('hikes_model');
    }    
    
    function getUserNameById($userId){
        $this->db->select('name');
        $this->db->from('user');
        $this->db->where('user_id', $userId);
        
        $query = $this->db->get();
        if($query->num_rows() === 1){
            return $query->result()[0]->name;
        }
    }
    
    
    /* Cette fonction permet de se connecter au compte indiqué par $username, $password*/
    function validCredentials($username,$password){
        $this->load->library('encrypt');
        $password = hash ( "sha256", $password );
        
        
        $this->db->select('user_id, name, mail, city');
        $this->db->from('user');
        $this->db->where('name', $username);
        $this->db->where('password', $password);
        $query = $this->db->get();
        
        $row = $query->row();
        
        if($query->num_rows() === 1){
            $session_data = array('username' => $row->name, 'userId' => $row->user_id, 'mail' => $row->mail, 'city' => $row->city, 'logged_in' => true);
            $this->session->set_userdata($session_data);
            $this->session->set_userdata('state', $this->getUserState());
            return true;
        }
        else return false;
    }
    
    function getUserHikes() { // Retourne toutes les randonnées de l'utilisateur
        $this->db->select('randonnee_id, name, type, difficulty, time, distance, description, date_of_creation, creator, city, number_download, moderated');
        $this->db->from('randonnee');
        $this->db->where('creator', $this->session->userdata('userId'));
        $query = $this->db->get();
        
        $res =  $query->result();
        foreach($res as $hike) { // Remplace d'id de la ville par un tableau qui contient son nom et son numéro de département.
            $hike->city = $this->hikes_model->getCityNameById($hike->city);
        }
        return $res;
    }
    
    function getUserHike($hikeId) { // Retourne LA randonnée qui a cet ID
        $this->db->select('randonnee_id, name, type, city, difficulty, time, distance, description');
        $this->db->from('randonnee');
        $this->db->where('creator', $this->session->userdata('userId'));
        $this->db->where('randonnee_id', $hikeId);
        $query = $this->db->get();
        if($query->num_rows() === 1){
            $query->result()[0]->city = $this->hikes_model->getCityNameById($query->result()[0]->city);
            return $query->result()[0];
        }
    }
    
    function isLoggedIn(){
        if($this->session->userdata('logged_in')) return true;
        else return false;
    }
    
    function delete() {
        $this->db->where('user_id', $this->session->userdata('userId'))->delete('user');
    }
    
    function user_modify($data) { 
        $data2 = array();
        $data2['name'] = $data['name'];
        $data2['mail'] = $data['mail'];
        $data2['city'] = $data['city'];
        
        $this->db->where('user_id', $this->session->userdata('userId'));
        $this->db->update('user', $data2);     
        
        $this->db->select('user_id, name, mail, city');
        $this->db->from('user');
        $this->db->where('user_id', $this->session->userdata('userId'));
        $query = $this->db->get();
        
        $row = $query->row();
        
        if($query->num_rows() === 1){
            $session_data = array('username' => $row->name, 'userId' => $row->user_id, 'mail' => $row->mail, 'city' => $row->city,'logged_in' => true);
            $this->session->set_userdata($session_data);
            return true;
        }
        else {
            return false;
        }
    }
    
    function pw_modify($data) { 
        $data2 = array();
        $data2['password'] = $data['password'];
        
        $this->db->where('user_id', $this->session->userdata('userId'));
        $this->db->update('user', $data2);    
    }

	//utilisé lorsque l'utilisateur a oublié son mot de passe; c'est à dire qu'il n'est pas connecté.
    function pw_modify2($data) { 
        $data2 = array();
        $data2['password'] = $data['password'];
        
        $this->db->where('name', $data['name']);
        $this->db->update('user', $data2);    
    }
    
    function GetAutocomplete($options = array()) {
        $this->db->select('libCity');
        $this->db->like('libCity', $options['keyword'], 'after');
        $query = $this->db->get('city');
        return $query->result();
    }
    function getCityIdByItsName($name){
        $this->db->select('idCity');
        $this->db->from('city');
        $this->db->where('libCity', $name);
        return $this->db->get();
    }
    function getCityNameByItsId($id){        
        $this->db->select('libCity, numDep');
        $this->db->from('city');
        $this->db->where('idCity', $id);
        
        return $this->db->get();
    }
    
    
    function getUserState(){
        $status = "0";
        if($this->isLoggedIn()){
            $this->db->select('state');
            $this->db->from('user');
            $this->db->where('user_id', $this->session->userdata('userId'));
            $q = $this->db->get();
            //            if($q->num_rows()==1){
            $status = $q->result()[0]->state;
            //            }
        }
        return $status;
    }
    
    /* Cette fonction permet de savoir si un compte $username a bien pour mot de passe $password
     * renvoi vrai si un tel compte existe.*/
    function validCredentials2($username, $password){
        $this->load->library('encrypt');
        $password = hash ( "sha256", $password );
        
        
        $this->db->select('name');
        $this->db->from('user');
        $this->db->where('name', $username);
        $this->db->where('password', $password);
        $query = $this->db->get();
        
        $row = $query->row();
        
        if($query->num_rows() === 1){
            $ret = true;
        }
        else {$ret = false;}
        
        return $ret;
    }
    
    function contact($data) {
        $this->db->insert('ci_contact',$data);        
    }
}
