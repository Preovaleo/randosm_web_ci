<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//======================================================================
// CLASSE SIGNUP
//======================================================================
/*
 * Gère la partie création de compte, avec captcha et validation par mail
 */

class Signup extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        // On définit les règles du formulaire
        $this->form_validation->set_rules('name', 'pseudo', 'trim|required|xss_clean|is_unique[user.name]|htmlspecialchars|max_length[16]');
        $this->form_validation->set_rules('email', 'email', 'trim|required|xss_clean|valid_email|is_unique[user.mail]|htmlspecialchars|max_length[30]');
        $this->form_validation->set_rules('pass', 'mot de passe', 'trim|required|xss_clean|min_length[5]|htmlspecialchars|max_length[30]');
        $this->form_validation->set_rules('pass2', 'vérification du mot de passe', 'trim|required|matches[pass]');
        $data['page_en_cours'] = 'signup';
        $data['status'] = null;


        // Si le formulaire n'a pas renvoyé d'erreur et que le captcha est valide
        if ($this->form_validation->run()) {

            $ValidationKey = md5(microtime() * 100000); //Création d'une clé qui permettra d'activer le compte, à l'aide de l'envoi d'un e-mail de confirmation du compte.
            $name = $this->input->post('name');

            //Ce tableau contient les résultats du formulaire, ainsi qu'un clé pour activer le compte.
            $data = array(
                'name' => $this->input->post('name'),
                'mail' => $this->input->post('email'),
                'password' => hash("sha256", $this->input->post('pass')),
                'ValidationKey' => $ValidationKey
            );

            $this->load->model('signup_model');
            $this->signup_model->signup($data); // Ajout du membre dans la BDD

            $this->load->library('email');
            $this->email->from('theobou@icare.pulseheberg.net', "\"Rand'OSM\"");
            $this->email->to($_POST['email']);
            $this->email->subject('Inscription');
            $this->email->message("Vous avez bien été inscrit à notre site.\nVeuillez valider votre compte en cliquant sur ce lien :\n\n http://randosm.theobouge.eu/validation?name=" . urlencode($name) . "&key=" . urlencode($ValidationKey) . "\n\nSi ce mail ne vous est pas destiné, veuillez l'ignorer.\nCe mail a été envoyé automatiquement, veuillez ne pas y répondre.");
            $this->email->send(); // Envoi du mail de confirmation


            $success['success'] = "Votre compte a bien été créé!<br/>Pour valider votre inscription, veuillez cliquer sur le lien envoyé à votre adresse mail.";

            $data2 = array(
                'titre' => 'Inscription',
                'connected' => $this->session->userdata('logged_in'),
                'page_en_cours' => 'Inscription',
            );

            // Chargement des vues
            $this->load->view('header', $data2);
            $this->load->view('menu', $data2);
            $this->load->view('signup', $success);
            $this->load->view('footer');
        } else {
            $data2 = array(
                'titre' => 'Inscription',
                'connected' => $this->session->userdata('logged_in'),
                'page_en_cours' => 'Inscription',
                'status' => null,
            );

            if ($this->form_validation->run()) {
                $data = array(
                    'error_captcha' => "Captcha invalide"
                );
            }

            // Chargement des vues
            $this->load->view('header', $data2);
            $this->load->view('menu', $data2);
            $this->load->view('signup', $data);
            $this->load->view('footer');
        }
    }

}
