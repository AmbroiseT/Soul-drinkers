<?php
	session_start();	
	require_once('fonctions_affichage.php');
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
		$tab=articles_requete(array());
		foreach($tab as $article){
			affiche_article($article);
			echo 'Validation? '.$article['valide'].'<br>';
			echo '<a href="validationArticles.php?_id='.$article['_id'].'&valide='.$article['valide'].'">Valider/devalider</a>';
		}
	}
?>
