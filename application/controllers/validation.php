<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validation extends CI_Controller {
	
	function Validation(){
		parent::__construct();
		$this->load->model('validation_model');
	}
	
	function index(){
		$data2 = array();
		$name = $_GET['name'];
		$key = $_GET['key'];
		$sameKey = $this->validation_model->sameKey($name, $key);
		
		if($sameKey){
			$state = $this->validation_model->getState($name, $key);

			if($state != 0){
				//afficher message à l'aide de la vue, comme quoi compte déjà activé
				$data['titre'] = "Ce compte Rand'OSM a déjà été validé";
				$data['page_en_cours'] = null;
				$data['status'] = null;
				$data2['success'] = 2;
				$this->load->view('header', $data);
				$this->load->view('menu', $data);
				$this->load->view('validation', $data2);
				$this->load->view('footer');
		
			}
		
			else{
				$data['titre'] = "Compte Rand'OSM validé";
				$data['page_en_cours'] = null;
		    		$data['status'] = null;
		    		$data2['success'] = 1;
				$this->validation_model->setState($name, 3);
				$this->load->view('header', $data);
				$this->load->view('menu', $data);
				$this->load->view('validation', $data2);
				$this->load->view('footer');
			}
		}

		else{
			$data['titre'] = "Mauvaise clé d'activation";
			$data['page_en_cours'] = null;
			$data['status'] = null;
			$data2['success'] = 3;
			$this->load->view('header', $data);
			$this->load->view('menu', $data);
			$this->load->view('validation', $data2);
			$this->load->view('footer');

		}
		

	}

	function new_mail(){
		$name = $_GET['name'];

		$mail = $this->validation_model->getMail($name);
		$key = $this->validation_model->getKey($name);

		$this->load->library('email');
		$this->email->from('theobou@icare.pulseheberg.net',"\"Rand'OSM\"");
		$this->email->to($mail);
		$this->email->subject('Inscription Re');
		$this->email->message("Bonjour,\n\nVoici un nouveau lien pour pouvoir activer votre compte.\nVeuillez valider votre compte en cliquant sur ce lien :\n\n ".base_url()."validation?name=".urlencode($name)."&key=".urlencode($key)."\n\nSi ce mail ne vous est pas destiné, veuillez l'ignorer.\nCe mail a été envoyé automatiquement, veuillez ne pas y répondre.");


		$this->email->send();


		$data['titre'] = "Nouveau mail envoyé.";
		$data['page_en_cours'] = null;
            	$data['status'] = null;
		$success['success'] = 1;
		$this->load->view('header', $data);
		$this->load->view('menu', $data);
		$this->load->view('new_mail', $success);
		$this->load->view('footer');
	}
}
