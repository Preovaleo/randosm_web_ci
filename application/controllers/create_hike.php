<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//======================================================================
// CLASSE CREATE_HIKE
//======================================================================
/*
 * Gère la création de randonnée, du fichier zip associé 
 * et de la randonnée dans la base de données
 */

class Create_hike extends CI_Controller {
    
    private $base;

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('hikes_model');
        $this->load->library('zip');
        $this->load->helper('directory');
        $this->load->helper('file');
        
        $this->base = "./downloads/";
    }

    public function create() {
        /* ################ Récupération des infos du formulaire et des markers au format tableau JSON ################ */
        parse_str($_POST['dataString']);

        $city = $this->hikes_model->getCityIdByName($city); // Return city ID

        $this->load->helper('date');
        date_default_timezone_set('Europe/Paris');

        $data = array(
            'name' => htmlspecialchars($name),
            'difficulty' => $difficulty,
            'time' => $time,
            'distance' => htmlspecialchars($length),
            'creator' => $this->session->userdata('userId'),
            'city' => htmlspecialchars($city),
            'description' => htmlspecialchars($description),
            'date_of_creation' => mdate("%Y-%m-%d", time())
                //            'mis_pour_afficher_requete'=>"d",
        );

        if (isset($otherType))
            $data['type'] = $otherType;
        else
            $data['type'] = $type;


        $hikeId = $this->hikes_model->create_hike($data); // Création de la randonnée dans la base de donnée
        echo $hikeId;


        /* ##################################### Création du fichier ZIP ##################################### */
        
        
        $baseID = $this->base . $hikeId . "/";
        $photo = $baseID . "media/photo/";
        $audio = $baseID . "media/audio/";
        $video = $baseID . "media/video/";
        
        if (!is_dir($base)) {
            mkdir($base, 0755, TRUE);
        }
        if (!is_dir($photo)) {
            mkdir($photo, 0755, TRUE);
        } if (!is_dir($audio)) {
            mkdir($audio, 0755, TRUE);
        } if (!is_dir($video)) {
            mkdir($video, 0755, TRUE);
        }

        file_put_contents($base . "markers.json", $_POST['markers']);


        $this->zip->add_dir('media/'); // Création d'un répertoire media dans un zip.

        $this->zip->get_files_from_folder($baseID, "./");
        $this->zip->archive($baseID . '.zip');    // Fermeture de l'archive

        $this->rrmdir($baseID);    // Suppression du répertoire
//        print_r(directory_map('../hiking/'));
    }

    public function modify_infos() {
        parse_str($this->input->post('dataString'));

        $data['name'] = $name;
        $data['type'] = $type;
        $data['time'] = $time;
        $data['city'] = $city;
        $data['distance'] = $distance;
        $data['difficulty'] = $difficulty;

        $data['hikeId'] = $this->input->post('hikeId');

        $hikeId = $this->hikes_model->modify_hike($data); // Mise à jour dans la base de données
    }

    public function modify_steps() {
        $markers = $this->input->post('markers');

        if ($markers != "[]") {
            $zip = new ZipArchive;
            if ($zip->open($this->base . $this->input->post('hikeId') . '.zip') === TRUE) {
                $zip->deleteName('./markers.json');
                $zip->addFromString('./markers.json', $markers);
                $zip->close();
                echo 'ok';
            } else {
                echo 'échec';
            }
        }
    }

    /**
     * 
     * Puts files from Dropzones to media directory in the zip file of the hike.
     * 
     * @param string type of media (photo, audio or video)
     *
     */
    function file_upload($type, $hikeId) {
        echo "----- file_upload-----";
        if (!empty($_FILES)) {
            $filesCount = count($_FILES['file']['name']);
            for ($i = 0; $i < $filesCount; $i++) {
                print_r($_FILES['file']);
                $tempFile = $_FILES['file']['tmp_name'][$i];
                $targetFile = $_FILES['file']['name'][$i];

                $zip = new ZipArchive;
                if ($zip->open($this->base . $hikeId . '.zip') === TRUE) {
                    $zip->addFile($tempFile, 'media/' . $type . '/' . $targetFile);
                    $zip->close();
                    echo 'ok';
                } else {
                    echo 'échec';
                }
            }
        }
    }

    /**
     * 
     * Delete a not empty diretory
     * 
     *
     */
    function rrmdir($path) {
        $it = new RecursiveDirectoryIterator($path);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($path);
    }

}
