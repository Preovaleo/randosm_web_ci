<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pw_forgotten extends CI_Controller {

	function Pw_forgotten() {
		parent::__construct();
		$this->load->model('validation_model');
		$this->load->helper('cookie');
    	}

	public function index(){
		$data['titre'] = 'Mot de passe oublié ?';
		$data['page_en_cours'] = null;
		
		$this->load->view('header', $data);
        	$this->load->view('menu', $data);
        	$this->load->view('pw_forgotten');
        	$this->load->view('footer');
						
	}

	public function pw_change_mail(){

		$this->form_validation->set_rules('pseudo', 'pseudo','trim|required|xss_clean|htmlspecialchars|max_length[16]');
		
		if($this->form_validation->run()) {
			$success['success'] = 2;
			$data = array(
                                'name'=>$this->input->post('pseudo')
                        );

			$name = $data['name'];
			$existName = $this->validation_model->existName($name);

			if($existName){
				$mail = $this->validation_model->getMail($name);
				$key = $this->validation_model->getKey($name);
				$this->load->library('email');
				$this->email->from('theobou@icare.pulseheberg.net',"\"Rand'OSM\"");
				$this->email->to($mail);
				$this->email->subject('Nouveau Mot de passe');
				$this->email->message("Bonjour,\n\nVoici un lien qui vous permettra de changer de mot de passe :\n\n ".base_url()."pw_forgotten/pw_change?name=".urlencode($name)."&key=".urlencode($key)."\n\nSi ce mail ne vous est pas destiné, veuillez l'ignorer.\nCe mail a été envoyé automatiquement, veuillez ne pas y répondre.");
				$this->email->send();


				$data['titre'] = 'Mot de passe oublié ?';
				$data['page_en_cours'] = null;
		
				$this->load->view('header', $data);
				$this->load->view('menu', $data);
				$this->load->view('new_mail', $success);
				$this->load->view('footer');
			}

			else{
				$data['titre'] = 'Mot de passe oublié ?';
				$data['page_en_cours'] = null;
		
				$this->load->view('header', $data);
        			$this->load->view('menu', $data);
        			$this->load->view('pw_forgotten', $success);
        			$this->load->view('footer');
			}

		}

	}

	function pw_change(){

		$data  = array();
		$data['titre'] = 'Modifier mon mot de passe';
		$data['page_en_cours'] = NULL;
		
		
		if(isset($_GET['name'])){
			$name = $_GET['name'];
			set_cookie('name', $name, '3600');
		}
		$data['name'] = get_cookie('name');

		if(isset($_GET['key'])){
			$key = $_GET['key'];
			set_cookie('key', $key, '3600');
		}
		$data['key'] = get_cookie('key');

		$trueKey = $this->validation_model->sameKey($data['name'], $data['key']);

		if($trueKey){
		    $this->form_validation->set_rules('pass1','nouveau mot de passe','trim|required|xss_clean|min_length[5]|htmlspecialchars|max_length[30]|callback_isDifferent');
		    $this->form_validation->set_rules('pass2','vérification du mot de passe','trim|required|matches[pass1]');

			if($this->form_validation->run()) {

				$data['password'] = hash ( "sha256", $this->input->post('pass1') ); 

				$this->load->model('user_model');
				$this->user_model->pw_modify2($data);

				$success['success'] = 'Votre mot de passe a été modifié.';                

				// Chargement des vues
				$this->load->view('header', $data);
				$this->load->view('menu', $data);
				$this->load->view('pw_change_disconnected', $success);
				$this->load->view('footer');
			
			}
			else {      
				// Chargement des vues
				$this->load->view('header', $data);
				$this->load->view('menu', $data);
				$this->load->view('pw_change_disconnected');
				$this->load->view('footer');
			}
		}

		else{
			redirect('login','refresh');
		}
		
	}

    
    function isDifferent($password) {
        $password = hash ( "sha256", $password );
        $username = get_cookie('name');
        $this->db->select('password');
        $this->db->from('user');
        $this->db->where('name', $username);
        $this->db->where('password', $password);
        $query = $this->db->get();
        
        $row = $query->row();
        
        if($query->num_rows() === 1){
            $this->form_validation->set_message('isDifferent', 'Le %s doit être différent de l\'ancien.');
            return false;
        }
        else {
            return true;
        }
    }

}
