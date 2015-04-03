<?php
	session_start();
	require_once('fonctions_mongo.php');
	if(isset($_SESSION['admin']) && $_SESSION['admin'] && isset($_GET['_id'])){
		supprimer_article_mongo($_GET['_id']);
	}
	header('Location:page_admin.php');
?>
