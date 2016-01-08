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

$sql = "DELETE FROM winners WHERE used != TRUE AND num <= (SELECT MAX(id) FROM players)";
mysqli_query($link, $sql) or die ("Impossible d'executer la requete 1");

header('Location:index.php?op=cadeaux');
?>