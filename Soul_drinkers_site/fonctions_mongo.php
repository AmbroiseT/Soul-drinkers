<?php		
	$m=new Mongo();
	$db=$m->projet;	
	//require_once('../connectBase.php');
	//$db=$m->athomi29;
	function verifier_connection_mongo(){
		global $db;
		$collection=$db->utilisateurs;
		$utilisateur=$collection->findOne(array("pseudo"=>$_POST['pseudo_connection']));
		if($utilisateur['pass']!=sha1($_POST['pass_connection'])){
			return false;
		}
		else{
			if(isset($utilisateur['admin'])&&$utilisateur['admin']=='true'){
				$_SESSION['admin']=true;
			}
			else{
				$_SESSION['admin']=false;
			}		
			return true;
		}
		return false;
	}

	function verifier_inscription_mongo(){
		$form_inscription=array($_POST['pseudo_inscription'],$_POST['nom_inscription'],$_POST['prenom_inscription']);
		$pattern='/[a-z0-9]/';
		foreach($form_inscription as $subject){
			if(!preg_match($pattern,$subject,$matches)){
				echo '<p>Vous avez utilisé un caractere interdit</p>';
				return false;
			}
		}
		
		global $db;
		$collection=$db->utilisateurs;
		$cursor=$collection->find();
		
		foreach($cursor as $utilisateur){
			if($utilisateur['pseudo']==$_POST['pseudo_inscription']){
				echo '<p>Ce pseudo est deja pris.</p>';
				return false;
			}
			if($utilisateur['mail']==$_POST['mail_inscription']){
				echo '<p>Il existe déja un compte pour cette adresse.</p>';
				return false;
			}
		}
		$passe=uniqid();
		$tab=array("pseudo"=>htmlspecialchars($_POST['pseudo_inscription']),"mail"=>htmlspecialchars($_POST['mail_inscription']),"nom"=>htmlspecialchars($_POST['nom_inscription']),"prenom"=>htmlspecialchars($_POST['prenom_inscription']),"age"=>htmlspecialchars($_POST['age_inscription']),"sexe"=>htmlspecialchars($_POST['sexe_inscription']),"pass"=>sha1($passe));
		$collection->insert($tab);
		echo '<p>Mot de passe : '.$passe.'</p>';//On affiche le mot de passe directement sur la page car la fonction mail marche assez mal
		mail($_POST['mail_inscription'],"Inscription au site soul drinkers","Bonjour".$_POST['pseudo_inscription']."\nFélicitations, vous avez bien été inscrit au site soul dinkers, votre mot de passe est :".$passe."\n Nous éspérons vous voir bientôt sur notre site\nCordialement\n \n Les administrateurs Ambroise Thomine et Quentin Perrachon");
		return true;
	}

	function ajouter_article_mongo($donnees){
		global $db;
		$collection=$db->articles;
		$donnees['valide']='false';
		$collection->insert($donnees);
	}

	function editer_article_mongo($id,$auteur,$titre,$texte,$mots_cles){//On pense bien à re-verifier l'auteur au cas où un petit malin s'amuse avec POST
		global $db;
		$collection=$db->articles;
		$_id=new MongoId($id);
		$collection->update(array('_id'=>$_id,'auteur'=>$auteur), array('$set'=>array('titre'=>$titre,'texte'=>$texte,'valide'=>'false', "mots_cles"=>$mots_cles)));
		//L'admin devra re-verifier l'article édité (permet d'éviter que des vilains créent un article tout gentil puis le modifient de manière nazie
	}

	function articles_requete_mongo($requete){
		global $db;		
		$collection=$db->articles;
		$retour=$collection->find($requete);
		return $retour;
	}
	
	function valider_article_mongo($id,$valide){
		$_id=new MongoId($id);
		global $db;
		$collection=$db->articles;
		$collection->update(array('_id'=>$_id),array('$set'=>array('valide'=>$valide)));
	}

	function chercher_article_id_mongo($id){
		$_id=new MongoId($id);
		global $db;
		$collection=$db->articles;
		$retour=$collection->findOne(array('_id'=>$_id));
		return $retour;
	}
	
	function chercher_articles_utilisateur($auteur){
		global $db;
		$collection=$db->articles;
		$retour=$collection->find(array("auteur"=>$auteur));
		return $retour; 
	}

	function supprimer_article_mongo($id){
		global $db;
		$_id=new MongoId($id);
		$collection=$db->articles;
		$collection->remove(array('_id'=>$_id));
	}

	function ajouter_commentaire_mongo($donnees){
		$donnees['article']=new MongoId($donnees['article']);
		global $db;
		$collection=$db->commentaires;
		$collection->insert($donnees);
	}

	function chercher_commentaires_article_mongo($id){
		$_id=new MongoId($id);
		global $db;
		$collection=$db->commentaires;
		$retour=$collection->find(array("article"=>$_id));
		return $retour;
	}

	function supprimer_commentaires_article($id){
		global $db;
		$collection=$db->commentaires;
		$collection->remove(array("article"=>$_id));
	}
	
	function changer_mot_de_passe_mongo($pseudo,$pass){
		global $db;
		$collection=$db->utilisateurs;
		$collection->update(array('pseudo'=>$pseudo),array('$set'=>array("pass"=>sha1($pass))));
	}

	function renvoyer_liste_utilisateurs_mongo(){
		global $db;
		$collection=$db->utilisateurs;
		$retour=$collection->find();
		return $retour;
	}
	
	function compter_articles_utilisateur_mongo($pseudo){
		global $db;
		$collection=$db->articles;
		$curseur=$collection->find(array('auteur'=>$pseudo));
		$compteur=0;
		foreach($curseur as $val){
			$compteur++;
		}
		return $compteur;
	}
	
	function virer_utilisateur_mongo($pseudo){
		global $db;
		$collection=$db->utilisateurs;
		$collection->remove(array('pseudo'=>$pseudo));
	}

	function changer_statut_utilisateur_mongo($pseudo){
		global $db;
		$collection=$db->utilisateurs;
		$utilisateur=$collection->findOne(array("pseudo"=>$pseudo));
		if(isset($utilisateur)){
			if(!isset($utilisateur['admin'])){
				$collection->update(array("pseudo"=>$pseudo),array('$set'=>array("admin"=>true)));
			}
			else{
				if($utilisateur['admin']){
					$collection->update(array("pseudo"=>$pseudo),array('$set'=>array("admin"=>false)));
				}
				else{
					$collection->update(array("pseudo"=>$pseudo),array('$set'=>array("admin"=>true)));
				}
			}
		}
	}

	function renvoyer_liste_articles_mongo(){
		global $db;
		$collection=$db->articles;
		$retour=$collection->find();
		return $retour;
	}
	
	function get_detail_compte_mongo(){
		global $db;
		$collection=$db->utilisateurs;
		$utilisateur=$collection->findOne(array("pseudo"=>$_SESSION["pseudo"]));
		return $utilisateur;
	}
	
	function verifier_modifier_detail_mongo(){
		$form_inscription=array($_POST['nom_modifier_detail'],$_POST['prenom_modifier_detail']);
			$pattern='/[a-z0-9]/';
			foreach($form_inscription as $subject){
				if(!preg_match($pattern,$subject,$matches)){
					echo '<p>Vous avez utilisé un caractere interdit</p>';
					return false;
				}	
			}
		global $db;
		$collection=$db->utilisateurs;
		$collection->update(array("pseudo"=>$_SESSION['pseudo']),array('$set'=>array("nom"=>$_POST['nom_modifier_detail'],"prenom"=>$_POST['prenom_modifier_detail'],"age"=>$_POST['age_modifier_detail'],"sexe"=>$_POST["sexe_modifier_detail"])));
		return true;
	}
	
	function verifier_modifier_pass_mongo(){
		global $db;
		$collection=$db->utilisateurs;
		$utilisateur=$collection->findOne(array("pseudo"=>$_SESSION['pseudo']));
		if($utilisateur['pass']!=sha1($_POST['pass_modifier_pass'])){
			return false;
		}
		else{
			$collection->update(array("pseudo"=>$_SESSION['pseudo']),array('$set'=>array("pass"=>sha1($_POST['newpass_modifier_pass']))));
			return true;
		}
	}
	
	function est_archive($donnee){
	$timearchive=mktime(0, 0, 0, date("m"),   date("d"),   date("Y")-1);
		if($donnee['time']<$timearchive){
			return true;
		}
		return false;
}
	
	function nombre_articles_recents_mongo(){
		global $db;
		$collection=$db->articles;
		$compteur=0;
		$requete=$collection->find();
		foreach($requete as $article){
			if(!est_archive($article)){
				$compteur++;
			}
		}
		return $compteur;
	}

	function nombre_articles_archives_mongo(){
		global $db;
		$collection=$db->articles;
		$compteur=0;
		$requete=$collection->find();
		foreach($requete as $article){
			if(est_archive($article)){
				$compteur++;
			}
		}
		return $compteur;
	}

?>
