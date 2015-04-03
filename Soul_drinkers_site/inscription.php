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
		afficher_formulaire_inscription();
		echo '</div>';
		footer_accueil();
		?>
		</div>
	</body>
</html>
