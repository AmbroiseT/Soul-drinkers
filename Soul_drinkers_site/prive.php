<?php
	session_start();
	require_once("fonctions_mongo.php");
	if(isset($_SESSION['admin']) && $_SESSION['admin']){
		if(isset($_GET['_id']) && isset($_GET['valide'])){
			$v;
			if($_GET['valide']=='true'){
				$v='false';
			}
			else{
				$v='true';
			}
			valider_article_mongo($_GET['_id'],$v);
		}
	}
	header('Location:page_admin.php');
?>
