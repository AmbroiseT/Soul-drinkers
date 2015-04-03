<?php
	session_start();
	unset($_SESSION['pseudo']);
	unset($_SESSION["admin"]);
	header("Location: entree.php");
?>
