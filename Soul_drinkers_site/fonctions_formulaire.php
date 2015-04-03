<?php

require_once('fonctions_mongo.php');
function formulaire_inscription(){
	echo '<form action="" method="POST" id="inscription">'."\n";
	echo champ_formulaire("pseudo_inscription","Pseudo :","text");
	echo champ_formulaire("mail_inscription","Votre mail :","mail");
	echo champ_formulaire("nom_inscription","Votre nom :","text");
	echo champ_formulaire("prenom_inscription","Votre prenom :","text");
	echo champ_formulaire("age_inscription","Votre age :","number");
	echo '<label for="sexe_inscripion">Votre sexe :</label><select name="sexe_inscription" id="sexe_inscription">
			<option>Homme</option>
			<option>Femme</option>
			<option>Autre</option>
		 </select>';
	echo "<br/>";
	echo '<input type="submit" value="S\'inscrire">';
	echo "</form>";
}

function formulaire_connection(){
	echo '<form action="" method="POST" >'."\n";
	echo champ_formulaire("pseudo_connection","Pseudo","text");
	echo champ_formulaire("pass_connection", "Mot de passe","password");
	echo '<input type="submit" value="Se connecter">';
	echo '</form>';
	echo '<p>Pas encore inscrit? <a href="inscription.php" alt="inscription">S\'inscrire</a></p>';
}

function formulaire_commentaire(){
	echo '<form action="" method="POST">'."\n";
	echo '<textarea row="5" cols="70" name="texte_commentaire">';
	echo '</textarea>';
	echo '<input type="submit" value="commenter!">';
	echo '</form>';
}
function formulaire_ecriture_article(){
	echo '<form action="" method="POST">';
	echo champ_formulaire("titre_article","Titre : ","text");
	echo '<textarea rows="15" cols="50" name="texte_article">';
	if(isset($_POST['texte_article'])){
		echo htmlspecialchars($_POST['texte_article']);
	}
	echo '</textarea><br>';
	echo champ_formulaire("url_image","URL d'une image :","text");
	echo champ_formulaire("url_video", "URL d'une video :",'text');
	echo '<label for="mots_cles">Mots clés:</label><input id="mots_cles" type="text" name="mots_cles" placeholder="motcle1, motcle2, motcle3...">';
	echo '<input type="submit" value="Envoyer!"><br>';
	echo '</form>';
}

function formulaire_modification_mot_de_passe(){
	echo '<form action="" method="POST">';
	echo champ_formulaire("pass_nouveau","Nouveau mot de passe : ","password");
	echo '<input type="submit" value="Envoyer!">';
	echo '</form>';
}

function formulaire_edition_article($article){
	echo '<form action="" method="POST">';
	echo '<input type="text" name="titre_edition" value="'.$article['titre'].'"><br>';
	echo '<textarea rows="10" cols="100" name="texte_edition">'.$article['texte'].'</textarea><br>';
	echo '<input type="text" name="mots_cles_edition" value="';
	foreach($article['mots_cles'] as $mot){
		echo $mot.', ';
	}
	echo '"><br>';
	echo '<input type="hidden" name="_id_edition" value="'.$article['_id'].'">';
	echo '<input type="submit" value="Editer">';
	echo '</form>';
}

function formulaire_recherche(){
		echo '<form action="recherche.php" method="GET">
				<input type="text" placeholder="mot cle1, mot cle2,..." name="recherche"><br>
				<input type="submit" value="rechercher">
			</form>';
	}

function verifier_connection(){
	if(!formulaire_bon(array("pseudo_connection","pass_connection"))){
		return false;
	}
	if(!verifier_connection_mongo()&&!isset($_SESSION["pseudo"])){
		return false;
	}
	if(!isset($_SESSION["pseudo"])){
		$_SESSION['pseudo']=$_POST['pseudo_connection'];
	}
	return true;
}

function valider_inscription(){
	if(!formulaire_bon(array("pseudo_inscription","mail_inscription","nom_inscription","prenom_inscription","age_inscription"))){
		return false;
	}
	if(verifier_inscription_mongo()){
		return true;
	}
	return false;
	
}

function champ_formulaire($id_champ,$texte_champ,$type_champ){	
	$retour='<div class="champ"><label for="'.$id_champ.'">'."\n";
	$retour=$retour.$texte_champ.'</label>';
	$retour=$retour.'<input type="'.$type_champ.'" id="'.$id_champ.'" name="'.$id_champ.'"';	
	if(isset($_POST[$id_champ])){
		$retour=$retour.' value="'.htmlspecialchars($_POST[$id_champ]).'">';
	}
	else{
		$retour=$retour.">";
	}
	if(!est_rempli($id_champ)){
		$retour=$retour.'<span class="alerte">Vous n\'avez pas rempli ce champ!</span>';
	}
	return $retour."</div>\n";
}

function envoyer_article_post(){
	if(isset($_SESSION['pseudo'])){
		if(isset($_POST['titre_article']) && isset($_POST['texte_article']) && isset($_POST['mots_cles']) && isset($_POST['url_video']) && isset($_POST['url_image'])){
			$mots_cles=decoupage_virgule($_POST['mots_cles']);
			setlocale (LC_ALL,'fr_FR.UTF-8');
			$date=strftime("%A %d %B %Y.");
			$donnees=array("titre"=>htmlspecialchars($_POST['titre_article']),"texte"=>htmlspecialchars($_POST['texte_article']),"auteur"=>$_SESSION['pseudo'],"date"=>$date,"time"=>time(),"url_image"=>htmlspecialchars($_POST['url_image']),"url_video"=>htmlspecialchars($_POST['url_video']),"mots_cles"=>$mots_cles);
			ajouter_article_mongo($donnees);
		}
	}
}

function envoyer_commentaire_post(){
	if(isset($_SESSION['pseudo'])){
		if(isset($_GET['_id']) && isset($_POST['texte_commentaire'])){
			$donnees=array("texte"=>htmlspecialchars($_POST['texte_commentaire']),"auteur"=>$_SESSION['pseudo'],"article"=>$_GET['_id']);
			ajouter_commentaire_mongo($donnees);
		}
	}
}

function formulaire_bon($tableau_id_champs){
	foreach($tableau_id_champs as $valeur){
		if(!isset($_POST[$valeur]) || $_POST[$valeur]==""){
			return false;
		}
	}
	return true;
}


function est_rempli($id_champ){
	if(isset($_POST[$id_champ]) && $_POST[$id_champ]==""){
		return false;
	}
	else{
		return true;
	}
}

function decoupage_virgule($entree){
		$mots_cles=array();
		$chaine="";
		$taille=strlen($entree);
		for($i=0;$i<$taille;$i++){
			$lu=$entree[$i];
			if($lu==','){
				$mots_cles[]=$chaine;
				$chaine="";
			}
			else if($lu!=' '){
				$chaine=$chaine.$lu;
			}
		}
		$mots_cles[]=$chaine;
		return $mots_cles;
	}

function numeroter($mots_cles, $donnees){
		$tab_numerote=array();
		foreach($donnees as $article){
			$compteur=0;
			$contenu=$article["mots_cles"];
			foreach($mots_cles as $mot){
				$compteur2=0;
				if($mot==$article['auteur']){
					$compteur2++;
				}
				foreach($contenu as $cle){
					if($cle==$mot){
						$compteur2++;
					}
				}
				$compteur=$compteur+$compteur2;
			}
			if($compteur!=0){
				$tab_numerote[]=array("article"=>$article,"valeur"=>$compteur);
			}
		}
		return $tab_numerote;
	}
	

	function trier_tab_numerote($tab_numerote){
				
		foreach($tab_numerote as $clei=>$valeuri){
			for($clej=$clei+1;isset($tab_numerote[$clej]);$clej++){
				if($valeuri['valeur']<$tab_numerote[$clej]['valeur']){
					$tmp=$tab_numerote[$clej];
					$tab_numerote[$clej]=$valeuri;
					$tab_numerote[$clei]=$tmp;
				}
			}
		}
		return $tab_numerote;
	}

	function rechercher_articles($requete){
		global $db;
		$collection=$db->articles;
		$donnees=articles_requete_mongo(array());
		$tab_requete=decoupage_virgule($requete);
		$resultat=numeroter($tab_requete,$donnees);
		$resultat=trier_tab_numerote($resultat);
		return $resultat;
	}
	
	function valider_modifier_detail(){
	if(!formulaire_bon(array("nom_modifier_detail","prenom_modifier_detail","age_modifier_detail"))){
		return false;
	}
	if(verifier_modifier_detail_mongo()){
		return true;
	}
	return false;
	}

	function formulaire_modifier_detail(){
	echo '<form action="" method="POST" id="modifier_detail">'."\n";
	echo champ_formulaire("nom_modifier_detail","Votre nom :","text");
	echo champ_formulaire("prenom_modifier_detail","Votre prenom :","text");
	echo champ_formulaire("age_modifier_detail","Votre age :","number");
	echo '<label for="sexe_modifer_detail">Votre sexe :</label><select name="sexe_modifier_detail" id="sexe_modifier_detail">
			<option>Homme</option>
			<option>Femme</option>
			<option>Autre</option>
		 </select>';
	echo "<br/>";
	echo '<input type="submit" value="Modifer">';
	echo "</form>";
	}
	
	function formulaire_modifier_pass(){
		echo '<form action="" method="POST" id="modifier_pass">'."\n";
		echo champ_formulaire("pass_modifier_pass","Votre mot de passe actuel :","password");
		echo champ_formulaire("newpass_modifier_pass","Votre nouveau mot de passe :","password");
		echo '<input type="submit" value="Modifier">';
		echo "</form>";
	}
	
	function valider_modifier_pass(){
		if(!formulaire_bon(array("pass_modifier_pass","newpass_modifier_pass"))){
			return false;
		}
		if(verifier_modifier_pass_mongo()){
			return true;
		}
		return false;
	}


?>
