<?php
/*
 * Created on 08 nov. 2008
 * by Salah Abdelkader Seif Eddine
 * using PHPeclipse
 */
session_start();
if(isset($_SESSION["alogged"])) {
	header("Location: index.php");
	exit(0);
}
$username = "";
$username_err = "";
$passwd_err = "";

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

$v = "";
if (isset($_POST["v"])) {
	$v = $_POST["v"];
}
if($v == "1") {
	$hasserr = false;
	$username = trim($_REQUEST["username"]);
	if($username == "") {
		$username_err = "Champs Identifiant vide";
		$hasserr = true;
	}
	$passwd = trim($_REQUEST["passwd"]);
	if($passwd == "") {
		$passwd_err = "Champs mot de passe vide";
		$hasserr = true;
	}
	if($hasserr == false) {
		$sql = "SELECT * FROM admins WHERE login = '$username'";
		$rs = mysqli_query($link, $sql) or die ('Erreur mysql : ' . mysqli_error());
		if(mysqli_num_rows($rs) == 0) {
			$username_err = "Identifiant invalide";
		} else {
			$row = mysqli_fetch_object($rs);
			if($row->passwd != $passwd) {
				$passwd_err = "Mot de passe incorrect";
			} else {
				$_SESSION["alogged"] = $username;
				header("Location: index.php");
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administration du Jeu Tiger</title>
<style type="text/css">
body.admin {
	margin:0px; padding:0px;
	font-family:Tahoma,Arial,sans-serif;
	font-size: 12px;
	color:black;
	background-color:#e1e1e1;
}

input.admin {
	color:black;
	background-color:white;
	font-family:Tahoma,Arial,sans-serif;
	font-size: 12px;
	width: 150px;
}

select.admin {
	color:black;
	background-color:white;
	font-family:Tahoma,Arial,sans-serif;
	font-size: 12px;
	width: 150px;
}

h1.admin {
	font-family:Tahoma,Arial,sans-serif;
	color:white;
	background-color:#525D76;
	font-size:22px;
}

h2.admin {
	font-family:Tahoma,Arial,sans-serif;
	color:white;
	background-color:#525D76;
	font-size:16px;
}

h3.admin {
	font-family:Tahoma,Arial,sans-serif;
	color:white;
	background-color:#525D76;
	font-size:14px;
}

h4.admin {
	font-family:Tahoma,Arial,sans-serif;
	color:white;
	background-color:#525D76;
	font-size:12px;
}

b.admin {
	font-family:Tahoma,Arial,sans-serif;
	color:white;
	background-color:#525D76;
}

a.admin {
	color : black;
	font-size: 12px;
}

hr.admin {
	color : #525D76;
}

tr.admin {
	background-color: #eeeeee;
}

th.admin {
	background-color: #eeeeee;
}

td.a1 {
	background-color: #cccccc;
}

td.a2 {
	background-color: #aaaaaa;
}
th.a1 {
	background-color: #cccccc;
}

th.a2 {
	background-color: #aaaaaa;
}
td.txtbg {
	background-color: white;
}
</style>
</head>

<body class="admin">
<table width="50%" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
	<td colspan="3" height="69px" style="">
		<a href="http://www.d-clicks.com" target="_blank"><img src="images/bkoffice_01.jpg" alt="D-Clicks" border="0" /></a>
	</td>
</tr>
<tr>
	<td width="29px" height="21px" style="background-image:url(images/bkoffice_03.jpg); background-position:bottom right; background-repeat:no-repeat;"></td>
    <td height="21px" style="background-image:url(images/bkoffice_04.jpg); background-position:bottom center; background-repeat:repeat-x;"></td>
    <td width="27px" height="21px" style="background-image:url(images/bkoffice_05.jpg); background-position:bottom left; background-repeat:no-repeat;"></td>
</tr>
<tr>
	<td style="background-image:url(images/bkoffice_07.jpg); background-position:right; background-repeat:repeat-y;"></td>
    <td style="background-color:#FFFFFF;">
		<form action="login.php" method="post">
			<table width="100%" align="center" border="0">
			<tr class="admin">
				<th colspan="2" class="a1">Identification</th>
			</tr>
			<?php
			if($username_err != "") {
			?>
			<tr class="admin">
				<th colspan="2" align="left"> <?php echo $username_err; ?> </th>
			</tr>
			<?php
			}
			?>
			<tr class="admin">
				<th align="right" width="50%"> Identifiant : &nbsp; </th>
				<td><input type="text" id="username" name="username" value="<?php echo $username; ?>" style="width: 150px;" /></td>
			</tr>
			<?php
			if($passwd_err != "") {
			?>
			<tr class="admin">
				<th colspan="2" align="left"> <?php echo $passwd_err; ?> </th>
			</tr>
			<?php
			}
			?>
			<tr class="admin">
				<th align="right"> mot de passe : &nbsp; </th>
				<td><input type="password" id="passwd" name="passwd" style="width: 150px;" /></td>
			</tr>
			<tr class="admin">
				<th colspan="2"> <input name="v" type="hidden" value="1" /><input type="submit" value="Login" /> </th>
			</tr>
			</table>
		</form>
	</td>
    <td style="background-image:url(images/bkoffice_09.jpg); background-position:left; background-repeat:repeat-y;"></td>
</tr>
<tr>
	<td width="29px" height="35px" style="background-image:url(images/bkoffice_11.jpg); background-position:top right; background-repeat:no-repeat;"></td>
    <td height="35px" style="background-image:url(images/bkoffice_12.jpg); background-position:top center; background-repeat:repeat-x;"></td>
    <td width="27px" height="35px" style="background-image:url(images/bkoffice_13.jpg); background-position:top left; background-repeat:no-repeat;"></td>
</tr>
</table>
</body>
</html>