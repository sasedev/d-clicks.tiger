<?php
require_once("cnf/cfg.php");
$code = "";
if (isset($_REQUEST['code'])) {
	$code = $_REQUEST['code'];
}
$tel = "";
if (isset($_REQUEST['tel'])) {
	$tel = $_REQUEST['tel'];
}
if($code != "" && $tel != "") {
	$link = mysqli_connect($dbhost, $dbuser, $dbpass);
	if (!$link) {
		die('Impossible de se connecter : ' . mysqli_error());
	}

	// Rendre la base de données $dbname, la base courante
	$dbln = mysqli_select_db($link, $dbname);
	if (!$dbln) {
		die ('Impossible de sélectionner la base de donn&eacute;es : ' . mysqli_error());
	}
	$sql = "INSERT INTO codegens (code, phone, used, createdat) VALUES (\"$code\", \"$tel\", FALSE, NOW())";
	mysqli_query($link, $sql) or die ("Impossible d'executer la requete");
	//echo $sql;
}
?>