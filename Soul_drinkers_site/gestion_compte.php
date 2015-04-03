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
		if(!isset($_SESSION['pseudo'])){
			echo '<p>Vous n\'avez pas accès à cette page désolé :-(</p>';
		}

		else{
			echo "<h3>Bonjour ".$_SESSION['pseudo'].", bienvenue sur la page de gestion de votre profil</h3>";
			afficher_detail_compte();
		
			
			echo '<form action="modifier_pass.php" method="POST">
					<input type="submit" value="Modifier votre mot de passe">
				</form>';
			if(!isset($_POST['titre_edition']) || !isset($_POST['texte_edition']) || !isset($_POST['_id_edition']) || !isset($_POST['mots_cles_edition'])){
				echo '<h4>Voici vos articles:</h4>';
				affiche_articles_edition(chercher_articles_utilisateur($_SESSION['pseudo']));
			}
			else{
				$mots_cles=decoupage_virgule($_POST['mots_cles_edition']);
				editer_article_mongo($_POST['_id_edition'],$_SESSION['pseudo'],$_POST['titre_edition'],$_POST['texte_edition'],$mots_cles);
				echo '<p>Votre modification a été enregistée, attendez la validation pour que l\'article apparaisse à l\'accueil</p>';
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
