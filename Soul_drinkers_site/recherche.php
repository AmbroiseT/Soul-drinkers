<!DOCTYPE html>
<?php
session_start();
?>
<html>
	<head>
	<title>Epées maudites en tout genre!</title>
	<link rel="stylesheet" href="entree.css">
	<meta charset="utf-8">
	</head>

	<body>
		<div class="page">
		<?php
		include('fonctions_affichage.php');
		header_accueil();
		afficher_col1();
		echo '<div id="col2">';
		affiche_formulaire_recherche();
		if(isset($_GET['recherche'])){
			$articles_num=rechercher_articles($_GET['recherche']);
			$compteur=0;
			foreach($articles_num as $article){
				if($article['valeur']==0){
					break;
				}
				if($article['article']['valide']=='true'){
					$compteur++;
				}
			}
			if($compteur==0){
				echo '<p>Navré, mais aucun résultat ne correspond à votre recherche...</p>';
			}
			else{
				foreach($articles_num as $article){
					if($article['valeur']==0){
						break;
					}
					affiche_article($article['article']);
				}
			}
		}
		?>
		</div>
		<?php
			footer_accueil();
		?>
	</div>
	</body>
</html>
