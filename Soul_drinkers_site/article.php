<!DOCTYPE html>
<?php
session_start();
//unset($_SESSION['pseudo']);
//$_SESSION['pseudo']="admin";
?>
<html>
  <head>
    <link rel="stylesheet" href="entree.css">
    <meta charset="utf-8" >
    <title>Epées maudites en tout genre!</title>
  </head>

  <body>
	<?php
	require_once('fonctions_affichage.php');
	?>
    <div class="page">
      
      <?php
	header_accueil();
	afficher_col1();
	 ?>
<div id="col2">
	<?php		
		if(isset($_GET['_id'])){
			$article=chercher_article_id_mongo($_GET['_id']);
			if($article && $article['valide']=='true'){
				affiche_article($article);
				envoyer_commentaire_post();		//cette fonction ne fera rien si aucun commentaire n'a été envoyé
				echo '<p>Mots clés associés:</p>';
				affiche_mots_cles_article($article);
				if(isset($_SESSION['pseudo'])){
					formulaire_commentaire();
				}
				else{
					echo '<p>Vous devez être connecté pour écrire un commentaire</p>';
				}
				affiche_commentaires_article($_GET['_id']);
			}
			else{
				echo 'L\'accés à cette page est interdit!!!!';
			}
		}
		else{
			echo 'L\'article que vous avez demandé n\'existe pas ou n\'est pas accessible... :-(';
		}
	?>
</div>

	<?php
		footer_accueil();
	?>
    </div>
  </body>
</html>
