<?php
	session_start();
	
	if(!isset($_SESSION['admin']) || !$_SESSION['admin']){
		header('Location:entree.php');
	}
	require_once('fonctions_mongo.php');
	require_once('fonctions_affichage.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Page administrateur</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="admin.css">
	</head>

	<body>
		<header>
			<h1>Gestion du site Soul Drinkers</h1>
			<h5>Bienvenue, admin</h5>
		</header>
		<article class="utilisateurs">
			<h3>Utilisateurs</h3>
			<table class="utilisateurs" border="1">
			<tr>
				<td>Pseudo</td>
				<td>Mail</td>
				<td>Statut</td>
				<td>Nombre d'articles</td>
				<td>Commandes</td>
			</tr>
			<?php
				$curseur=renvoyer_liste_utilisateurs_mongo();
				foreach($curseur as $utilisateur){
						echo '<tr>';
						echo '<td>'.$utilisateur['pseudo'].'</td>';
						echo '<td>'.$utilisateur['mail'].'</td>';
						if(isset($utilisateur['admin']) && $utilisateur['admin']){
							echo '<td>Administrateur</td>';
						}
						else{
							echo '<td>Utilisateur lambda</td>';
						}
						echo '<td>'.compter_articles_utilisateur_mongo($utilisateur['pseudo']).'</td>';
						echo '<td><a href="virer.php?qui='.htmlspecialchars($utilisateur['pseudo']).'">Virer</a><br>
							<a href="promouvoir.php?qui='.htmlspecialchars($utilisateur['pseudo']).'">Changer le statut</a>
							</td>';
						echo '</tr>';
				}
			?>
			</table>
		</article>

		<article class="articles">
			<h3>Articles</h3>
			<?php
				$curseur=renvoyer_liste_articles_mongo();
				foreach($curseur as $article){
					affiche_article($article);
					echo '<p class="commandes">';
					echo '<a href="prive.php?valide='.$article['valide'].'&_id='.$article['_id'].'" ';
					if($article['valide']=='true'){
						echo 'class="prive">';
						echo 'Rendre privé';
					}
					else{
						echo 'class="public">';
						echo 'Rendre public';
					}
					echo '</a><br>';
					echo '<a href="supprimer_article.php?_id='.$article['_id'].'" class="supprimer">Supprimer</a>';

					echo '</p>';
				}
			?>
		</article>
		
	</body>
</html>
