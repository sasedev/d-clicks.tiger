<?php
/*
 * Created on 08 nov. 2008
 * by Salah Abdelkader Seif Eddine
 * using PHPeclipse
 */
session_start();
if(!isset($_SESSION["alogged"])) {
	header("Location: login.php");
	exit(0);
}
require_once("../cnf/cfg.php");
$link = mysqli_connect($dbhost, $dbuser, $dbpass);
if (!$link) {
	die('Impossible de se connecter : ' . mysqli_error());
}

// Rendre la base de données $dbname, la base courante
$dbln = mysqli_select_db($link, $dbname);
if (!$dbln) {
	die ('Impossible de sélectionner la base de donn&eacute;es : ' . mysqli_error());
}
$cadeau = "";
if (isset($_POST["type"])) {
	$cadeau = $_POST['type'];
}
$num = "";
if (isset($_POST["freq"])) {
	$num = $_POST['freq'];
}
if($cadeau == 1 || $cadeau == 2 || $cadeau == 3 || $cadeau == 4 || $cadeau == 5) {
	if(is_numeric($num) && $num >=1) {
		$num = intval($num);
		$sql = "SELECT MAX( id ) AS m FROM players";
		$res = mysqli_query($link, $sql) or die ("Impossible d'executer la requete 1");
		$resultat = mysqli_fetch_object($res);
		if($resultat->m == NULL) {
			$idp = 1;
		} else {
			$idp = $resultat->m;
		}
		$sql = "SELECT MAX( num ) AS m FROM winners ";
		$res = mysqli_query($link, $sql) or die ("Impossible d'executer la requete 2");
		$resultat = mysqli_fetch_object($res);
		if($resultat->m == NULL) {
			$idw = 1;
		} else {
			$idw = $resultat->m ;
		}
		if($idw > $idp) {
			$num = $idw + $num;
		} else {
			$num = $idp + $num;
		}
		$sql = "INSERT INTO winners (num, cadeau, used, createdat) VALUES ($num, $cadeau, FALSE, NOW())";
		mysqli_query($link, $sql) or die ("Impossible d'executer la requete 3");
	}
}
header('Location:index.php?op=cadeaux');
?>