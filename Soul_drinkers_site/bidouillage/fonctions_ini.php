<?php
	$m=new Mongo();
	$db=$m->projet;
	$collection=$db->articles;
	function ajout_article($titre,$texte,$auteur, $mots_cles,$date,$time_stamp,$valide,$image, $video){
		global $collection;		
		$collection->insert(array('titre'=>$titre,'texte'=>$texte,'valide'=>'true', "mots_cles"=>$mots_cles,"date"=>$date,"time"=>$time_stamp, "auteur"=>$auteur,'valide'=>$valide,'url_image'=>$image,'url_video'=>$video));
	}

	function ajout_membre($pseudo, $mail, $pass, $admin, $nom, $prenom, $age, $sexe){
		global $db;
		$collection=$db->utilisateurs;
		$collection->insert(array('pseudo'=>$pseudo,'mail'=>$mail,'nom'=>$nom,'prenom'=>$prenom,'age'=>$age,"sexe"=>$sexe,'pass'=>sha1($pass),'admin'=>$admin));
	}
	
	function nettoyage(){
		global $db;
		$db->utilisateurs->remove();
		$db->articles->remove();
		$db->commentaires->remove();
	}
	
?>
