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
		affiche_articles_accueil(articles_requete_mongo(array()));
	?>
</div>

	<?php
		footer_accueil();
	?>
    </div>
  </body>
</html>
