<?php

class Validation_model extends CI_Model {

	function getState($name, $key){
			$this->db->select('state');
			$this->db->from('user');
			$this->db->where('name', $name);
			$this->db->where('ValidationKey', $key);
			
			$ret = $this->db->get()->row()->state;
			return $ret;
	}
	
	function getState2($name){
			$this->db->select('state');
			$this->db->from('user');
			$this->db->where('name', $name);
			
			$ret = $this->db->get()->row()->state;
			return $ret;
	}

	function setState($name, $bool){
		$data['state'] = $bool;
		$this->db->where('name', $name);
		$this->db->update('user', $data);
	}
	
	function getKey($name){
		$this->db->select('ValidationKey');
		$this->db->from('user');
		$this->db->where('name', $name);
			
		$ret = $this->db->get()->row()->ValidationKey;
		return $ret;
	}
	
	function getMail($name){
		$this->db->select('mail');
		$this->db->from('user');
		$this->db->where('name', $name);
			
		$ret = $this->db->get()->row()->mail;
		return $ret;
	}
	
	function existName($name){
		$this->db->select('name');
		$this->db->from('user');
		$this->db->where('name', $name);
		
		if(isset($this->db->get()->row()->name)){
			$ret = true;
		}
		
		else{$ret = false;}
		
		return $ret;
	}
	
	function sameKey($name, $key){
		$this->db->select('ValidationKey');
		$this->db->from('user');
		$this->db->where('name', $name);
		$this->db->where('ValidationKey', $key);
		
		if(isset($this->db->get()->row()->ValidationKey)){
			$ret = true;
		}
		
		else{$ret = false;}
		
		return $ret;
	}
}
		
