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
		if(isset($_SESSION['pseudo'])){
			if(!isset($_POST['titre_article']) || !isset($_POST['texte_article'])){
				afficher_formulaire_ecriture();
			}
			else{
				$erreur="";
				if(!empty($_POST['url_image']) || !empty($_POST['url_video'])){
					if(!empty($_POST['url_image'])){
						if(!filter_var($_POST['url_image'],FILTER_VALIDATE_URL)){
							$erreur=$erreur."<p>L'url de l'image donnée est mauvaise</p>";					
							echo 'Bonjour image';
						}
					}
					if(!empty($_POST['url_video'])){
						if(!preg_match("#^//www.youtube.com/embed/#",$_POST['url_video'])){
							echo 'Bonjour video';
							$erreur=$erreur."<p>L'url de la video donnée est mauvaise.</p>";
						}
					}
					if($erreur==""){
						envoyer_article_post();				
						echo '<p id="redaction">Votre article a bien été envoyé, attendez la confirmation de l\'administrateur pour qu\'il soit publié.</p>';
					}
					else{
						afficher_formulaire_ecriture();
						echo 'coucou';
						echo $erreur;
					}
				}
				else{
					envoyer_article_post();				
					echo '<p id="redaction">Votre article a bien été envoyé, attendez la comfirmation de l\'administrateur pour qu\'il soit publié.</p>';
				}
			}
		}
		else{
			echo '<p id="redaction">Vous devez vous connecter pour ecrire un article</p>';
		}
		echo '</div>';
		footer_accueil();
		
		?>
		</div>
	</body>
</html>
