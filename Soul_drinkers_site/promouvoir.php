<?php
	session_start();
	require_once('fonctions_mongo.php');
	if(isset($_SESSION['admin']) && $_SESSION['admin'] && isset($_GET['qui'])){
		changer_statut_utilisateur_mongo($_GET['qui']);
	}
	header('Location:page_admin.php');
?>
