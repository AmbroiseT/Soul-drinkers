<?php
require_once('fonctions_formulaire.php');

function header_accueil(){
	echo '<a href="entree.php" alt="retour a l\'entree">'."\n";
	echo '<header>'."\n";

	echo '<img src="images/epees/sword_by_sarafiel-d5g7v0q.jpg" alt="épee">';
	echo '<h1>Soul drinkers</h1>';
	echo '<h3>Le site consacré aux épées maudites dans la fiction et l\'histoire</h3>';
	echo '</header>';
	echo "</a>\n";
}

function footer_accueil(){
	echo "<footer><p><em>Ce site a été crée par Ambroise Thomine et Quentin Perrachon
		dans le cadre du projet d'IO2</em></p></footer>";
}

function afficher_formulaire_inscription(){
	if(!isset($_SESSION['pseudo'])){
		echo '<div class="inscription">';
		if(valider_inscription()){
			echo '<p>Votre inscription a été prise en compte</p>';
		}
		else{
			echo "<h4>Inscription au site</h4>";
			formulaire_inscription();
			echo "<p><em>Vos données personelles ne seront pas utilisées
		à des fins douteuses!</em></p>";
		}
		echo '</div>';
	}
}

function afficher_formulaire_connection(){
	echo '<div id="connection">';
	verifier_connection();
	if(!isset($_SESSION['pseudo'])){
	 formulaire_connection();
	}
	else{
		echo "<p>Vous etes connecté en tant que ".$_SESSION['pseudo'].'</p>';
		echo '<p><a href="deconnection.php" alt="deco">Se deconnecter</a></p>';
		echo '<p><a href="gestion_compte.php" alt="gestion">Gestion du compte</a></p>';
		if($_SESSION['admin']){
			echo '<p><a href="page_admin.php" alt="page admin">Page administrateur</a></p>';
		}
	}
	echo '</div>';
}

function afficher_formulaire_ecriture(){
	echo '<section id="redaction">';
	formulaire_ecriture_article();	
	echo '<p><em>Attention, vous êtes tenus de ne pas écrire de propos discriminatoires ou insultants (on vérifiera)</em></p>';
	echo '</section>';
}


function afficher_menu_navigation(){
	echo "<nav>\n";
	echo '<ul>';
	echo element_liste("Accueil","entree.php");
	echo element_liste("Ecrire un article","redaction.php");
	echo element_liste("Archives","archive.php");
	echo '</ul>';
	echo '</nav>'; 
}

function afficher_col1(){
	echo '<div id="col1">';
	afficher_formulaire_connection();
	afficher_menu_navigation();
	affiche_formulaire_recherche();
	echo '</div>';
}

function element_liste($texte,$url){
	$retour="";
	$retour=$retour.'<a href="'.$url.'"><li>'.$texte.'</li></a>';
	return $retour;
}

function affiche_articles($donnees){
	foreach($donnees as $article){
		affiche_article($article);
	}
}

function affiche_article($donnees){
	echo '<article>';
	echo '<a href="article.php?_id='.$donnees['_id'].'" >';
	echo '<h3>'.$donnees['titre'].'</h3>';
	echo '</a>';
	echo '<p><em>Par '.$donnees['auteur'].' le '.$donnees['date'].'</em></p>';
	if(!est_archive($donnees) || isset($_SESSION['pseudo'])){
		if(!empty($donnees['url_image'])){
			echo '<img src="'.htmlspecialchars($donnees['url_image']).'">';
		}
		echo '<p>'.$donnees['texte'].'</p>';
		if(!empty($donnees['url_video'])){
			echo '<iframe width="420" height="315" src="'.$donnees['url_video'].'" frameborder="0" allowfullscreen></iframe>';
		}
	}
	else{
		echo '<p>Cet article est une archive, connectez vous pour le lire.</p>';
	}
	echo '</article>';
	
}

function affiche_article_accueil($donnees){
	if($donnees['valide']=='true'){
		if(!est_archive($donnees)){
			affiche_article($donnees);
		}
	}
}

function affiche_articles_accueil($donnees){
	if(!isset($_GET['numero'])){
		$_GET['numero']=1;
	}
	$num=($_GET['numero']-1)*2;
	$compt=0;
	foreach($donnees as $article){
		if(!est_archive($article)){
			$compt++;
			if($compt>$num && $compt<=$num+2){
				affiche_article_accueil($article);
			}
		}
	}
	affiche_liens_navigation_accueil($_GET['numero']);
}

function affiche_articles_edition($articles){
	foreach($articles as $article){
		formulaire_edition_article($article);
	}
}

function affiche_commentaire($commentaire){
	echo '<div class="commentaire">';
	echo '<h4>'.$commentaire['auteur'].' a écrit:</h4>';
	echo '<p>'.$commentaire['texte'].'</p>';
	echo '</div>';
}
function affiche_commentaires_article($article){
	$tab=chercher_commentaires_article_mongo($article);
	foreach($tab as $commentaire){
		affiche_commentaire($commentaire);
	}
}

function affiche_formulaire_recherche(){
	echo '<p>Recherche:</p>';
	formulaire_recherche();
}
function affiche_mot_cle($mot_cle){
	echo '<a href="recherche.php?recherche='.$mot_cle.'">'.$mot_cle.'</a> ';
}

function affiche_mots_cles_article($article){
	foreach($article['mots_cles'] as $mot){
		affiche_mot_cle($mot);
	}
}

function afficher_detail_compte(){
	echo '<h4>Details du compte</h4>';
	$detail=get_detail_compte_mongo();
	echo '  <p>Nom : '.$detail['nom'].'<br/>
			Prenom : '.$detail['prenom'].'<br/>
			E-Mail : '.$detail['mail'].'<br/>
			Age : '.$detail['age'].'<br/>
			Sexe : '.$detail['sexe'].'<br/></p>';
	echo '  <form action="modifer_detail.php" method="POST">
				<input type="hidden" name="nom_modifier_detail" value="'.$detail['nom'].'">
				<input type="hidden" name="prenom_modifier_detail" value="'.$detail['prenom'].'">
				<input type="submit" value="Modifer les détails">
			</form>';
}

function afficher_formulaire_modifier_detail(){
	echo '<div id="modifier_detail">';
		if(valider_modifier_detail()){
			echo "<p>Ces modifications ont été prises en compte.</p>";
		}
		else{
			formulaire_modifier_detail();
		}
		echo '</div>';
}

function afficher_formulaire_modifier_pass(){
	echo '<div id="modifier_detail">';
		if(valider_modifier_pass()){
			echo "<p>Votre mot de passe à été modifié.</p>";
		}
		else{
			formulaire_modifier_pass();
		}
		echo '</div>';
}
function affiche_article_archive($donnees){
	if($donnees['valide']=='true'){
		if(est_archive($donnees)){
			affiche_article($donnees);
		}
	}
}


function affiche_articles_archive($articles){
	if(isset($_SESSION['pseudo'])){
		if(!isset($_GET['numero'])){
			$_GET['numero']=1;
		}
		$num=($_GET['numero']-1)*2;
		$compt=0;
		foreach($articles as $article){
			if(est_archive($article)){
				$compt++;
				if($compt>$num && $compt<=$num+2){
					affiche_article_archive($article);
				}
			}
		}
		affiche_liens_navigation_archives($_GET['numero']);
	
	}
	else{
		echo '<p>Pour consulter les archives, veuillez vous connecter.</p>';
	}
}

function affiche_liens_navigation_accueil($page){
	$nombre=nombre_articles_recents_mongo();
	if($nombre%2!=0){
		$nombre++;
	}
	echo '<p class="liens">';
	$nombre=$nombre/2;//2 articles par page
	for($i=1;$i<=$nombre;$i++){
		if($i!=$page){
			echo '<a href="entree.php?numero='.$i.'">'.$i.'</a> ';
		}
		else{
			echo $i.' ';
		}
	}
	echo '</p>';
}

function affiche_liens_navigation_archives($page){
	$nombre=nombre_articles_archives_mongo();
	if($nombre%2!=0){
		$nombre++;
	}
	echo '<p class="liens">';
	$nombre=$nombre/2;//2 articles par page
	for($i=1;$i<=$nombre;$i++){
		if($i!=$page){
			echo '<a href="archive.php?numero='.$i.'">'.$i.'</a> ';
		}
		else{
			echo $i.' ';
		}
	}
	echo '</p>';
}

?>

