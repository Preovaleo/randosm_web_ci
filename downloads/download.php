<?php
	
	$user = 'telechargement';
	$password = 'INjkwFDKO"x0;y|\&JptPjm&o7&V';
	$base__ = 'randOSM';

	$id = $_GET['id'];
	$file= $id .".zip";

	if (($file != "") && (file_exists("./" . basename($file)))) {
		
		//connection a la bdd
		$link = mysql_connect('localhost', $user, $password);
		if (!$link) {
		   die('Impossible de se connecter : ' . mysql_error());
		}

		// Rendre la base de données foo, la base courante
		$db_selected = mysql_select_db($base__, $link);
		if (!$db_selected) {
		   die ('Impossible de sélectionner la base de données : ' . mysql_error());
		}
		
		//request	
		$rep = mysql_query("UPDATE randonnee SET number_download=number_download+1 WHERE randonnee_id=".$id.";");
		if (!$rep) {
		   die ('Erreur querry : ' . mysql_error());
		}
		
		//regle pour le telchargement
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		readfile($file);

		exit();

	} else {
		die("le fichier n'existe pas");
	}
?>

