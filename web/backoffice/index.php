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
<table width="100%" cellpadding="0" cellspacing="0" border="0">
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
    	<table align="center" width="100%" >
		<tr class="admin"  valign="top">
			<td width="20%"><a href="index.php" class="admin"> Index </a></td>
			<td width="20%"><a class="admin" href="index.php?op=cadeaux">Cadeaux</a></td>
			<td width="20%"><a class="admin" href="excel.php">Exporter vers Excel</a></td>
			<td width="40%" align="right"><a class="admin" href="logout.php">D&eacute;connexion</a></td>
		</tr>
		</table>
		<hr size="1" noshade="noshade"/>
		<table align="center" width="100%" >
		<tr class="admin"  valign="top">
			<td align="left"> &nbsp;
			</td><td align="right" class="a1">Nombre de code générés : &nbsp;</td>
			<td class="a2"> &nbsp;
		<?php
		$sql = "SELECT COUNT(*) AS j FROM codegens";
		$rs = mysqli_query($link, $sql) or die("error mysql ".$sql." ".mysqli_error());
		$row = mysqli_fetch_object($rs) or die("error mysql ".$sql." ".mysqli_error());
		echo $row->j;
		?>
			</td>
			<td align="left" width="50%" colspan="7"> Indique le nombre de code qui ont été généré par le serveur SMS (selon le communiqué du serveur SMS)
			</td>
		</tr>
		<tr class="admin"  valign="top">
			<td align="left"> &nbsp;
			</td><td align="right" class="a1">Nombre de personnes ayant joué : &nbsp;</td>
			<td class="a2"> &nbsp;
		<?php
		$sql = "SELECT COUNT(*) AS j FROM players";
		$rs = mysqli_query($link, $sql) or die("error mysql ".$sql." ".mysqli_error());
		$row = mysqli_fetch_object($rs) or die("error mysql ".$sql." ".mysqli_error());
		$j = $row->j;
		if($j > 0) {
			echo "<a href=\"?op=inscrits\" class=\"admin\">".$row->j."</a>";
		} else {
			echo $row->j;
		}
		?>	+
		<?php
		$sql = "SELECT COUNT(*) AS o FROM oplayers";
		$rs = mysqli_query($link, $sql) or die("error mysql ".$sql." ".mysqli_error());
		$row = mysqli_fetch_object($rs) or die("error mysql ".$sql." ".mysqli_error());
		$o = $row->o;
		if($o > 0) {
			echo "<a href=\"?op=revenants\" class=\"admin\">".$row->o."</a>";
		} else {
			echo $row->o;
		}
		?>
		(joueurs ayant deja gagner 2 cadeaux ou plus)
			</td>
			<td align="left" width="50%" colspan="7"> Indique le nombre de personnes ayant joué sur le site.
			</td>
		</tr>
		<tr class="admin"  valign="top">
			<td align="left"> &nbsp;
			</td><td align="right" class="a1">Nombre de gagnants : &nbsp;</td>
			<td class="a2"> &nbsp;
		<?php
		$sql = "SELECT COUNT(*) AS g FROM players WHERE cadeau != 0";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_object($rs);
		$g = $row->g;
		if($g > 0) {
			echo "<a href=\"?op=gagnants\" class=\"admin\">".$row->g."</a>";
		} else {
			echo $row->g;
		}
		?>
			</td>
			<td align="left" colspan="7"> Indique le nombre de personnes ayant joué et gagné un cadeau
			</td>
		</tr>

		<tr class="admin"  valign="top">
			<td align="left"> &nbsp;
			</td><td align="right" class="a1">Nombre de cadeaux restants : &nbsp;</td>
			<td class="a2"> &nbsp;
		<?php
		if($g>0) {
			$sql = "SELECT COUNT(*) AS c FROM winners WHERE used != TRUE AND num > (SELECT MAX(id) FROM players)";
		} else {
			$sql = "SELECT COUNT(*) AS c FROM winners WHERE used != TRUE";
		}
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_object($rs);
		if($row->c > 0) {
			echo "<a href=\"?op=cadeaux\" class=\"admin\">".$row->c."</a>";
		} else {
			echo $row->c;
		}
		$c = $row->c;
		?>
			</td>
			<td align="left" colspan="7"> Indique le nombre de cadeau qui n'ont pas encore été gagnés.
			</td>
		</tr>
		<tr class="admin"  valign="top">
			<td align="left"> &nbsp;
			</td><td align="right" class="a1">Nombre de cadeaux perdus : &nbsp;</td>
			<td class="a2"> &nbsp;
		<?php
		$sql = "SELECT COUNT(*) AS cp FROM winners WHERE used != TRUE AND num <= (SELECT MAX(id) FROM players)";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_object($rs);
		if($row->cp > 0) {
			echo "<a href=\"?op=cadeaux\" class=\"admin\">".$row->cp."</a>";
		} else {
			echo $row->cp;
		}
		$cp = $row->cp;
		?>
			</td>
			<td align="left" colspan="7"> Indique le nombre de cadeau dont le numéro gagnant a été dépassé par les ID des joueurs (en cas de pb de synchro).
			</td>
		</tr>
		</table>
		<hr size="1" noshade="noshade"/>
		<?php
		$op = "";
		if (isset($_REQUEST["op"])) {
			$op = $_REQUEST["op"];
		}
		if($op == "gagnants") {
			if($g > 0) {
				$sql = "SELECT * FROM players WHERE cadeau != 0";
				$order = "";
				if (isset($_REQUEST["oreder"])) {
					$order = $_REQUEST["order"];
				}
				$dir = "";
				if (isset($_REQUEST["dir"])) {
					$dir = $_REQUEST["dir"];
				}
				if($order == "id") {
					$sql .=" ORDER BY id";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "code") {
					$sql .=" ORDER BY code";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "gsm") {
					$sql .=" ORDER BY gsm";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "nom") {
					$sql .=" ORDER BY nom";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "prenom") {
					$sql .=" ORDER BY prenom";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "age") {
					$sql .=" ORDER BY age";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "email") {
					$sql .=" ORDER BY email";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "cadeau") {
					$sql .=" ORDER BY cadeau";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "ami") {
					$sql .=" ORDER BY ami";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "date") {
					$sql .=" ORDER BY createdat";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				//echo $sql;
				$rs = mysqli_query($link, $sql);
		?>
		<table align="center" width="100%" >
		<tr>
			<th class="a2" colspan="10" align="center">Liste des gagnants&nbsp;</th>
		</tr>
		<tr class="admin">
			<th align="center" class="a1">
			<a href="?op=gagnants&order=id&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; ID &nbsp;
			<a href="?op=gagnants&order=id&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=gagnants&order=code&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; CODE &nbsp;
			<a href="?op=gagnants&order=code&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=gagnants&order=gsm&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; GSM &nbsp;
			<a href="?op=gagnants&order=gsm&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=gagnants&order=nom&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Nom &nbsp;
			<a href="?op=gagnants&order=nom&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=gagnants&order=prenom&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Prenom &nbsp;
			<a href="?op=gagnants&order=prenom&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=gagnants&order=age&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Age &nbsp;
			<a href="?op=gagnants&order=age&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=gagnants&order=email&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Email &nbsp;
			<a href="?op=gagnants&order=email&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=gagnants&order=cadeau&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Cadeau &nbsp;
			<a href="?op=gagnants&order=cadeau&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=gagnants&order=ami&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Gsm Ami &nbsp;
			<a href="?op=gagnants&order=ami&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=gagnants&order=date&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Date &nbsp;
			<a href="?op=gagnants&order=date&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
		</tr>
		<?php

				while($row = mysqli_fetch_object($rs)) {
		?>
		<tr class="admin">
			<td align="center">
			<?php echo $row->id; ?>
			</td>
			<td align="center">
			<?php echo $row->code; ?>
			</td>
			<td align="center">
			<?php echo $row->gsm; ?>
			</td>
			<td align="center">
			<?php echo $row->nom; ?>
			</td>
			<td align="center">
			<?php echo $row->prenom; ?>
			</td>
			<td align="center">
			<?php echo $row->age; ?>
			</td>
			<td align="center">
			<?php echo $row->email; ?>
			</td>
			<td align="center">
			<?php
			switch ($row->cadeau) {
				case 0: echo "Aucun"; break;
				case 1: echo "Recharge GSM"; break;
				case 2: echo "IPod"; break;
				case 3: echo "IPhone"; break;
				case 4: echo "Wii"; break;
				case 5: echo "PC Portable"; break;
			}
			?>
			</td>
			<td align="center">
			<?php echo $row->ami; ?>
			</td>
			<td align="center">
			<?php echo $row->createdat; ?>
			</td>
		</tr>
		<?php
				}
		?>
		</table>
		<?php
			} else {
		?>
				Aucun gagnant trouvé en base de donnée
		<?php
			}
		?>
		<hr size="1" noshade="noshade"/>
		<?php
		}
		?>


		<?php
		if($op=="inscrits") {
			if($j > 0) {
				$sql = "SELECT * FROM players";
				$order = $_REQUEST["order"];
				$dir = $_REQUEST["dir"];
				if($order == "id") {
					$sql .=" ORDER BY id";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "code") {
					$sql .=" ORDER BY code";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "gsm") {
					$sql .=" ORDER BY gsm";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "nom") {
					$sql .=" ORDER BY nom";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "prenom") {
					$sql .=" ORDER BY prenom";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "age") {
					$sql .=" ORDER BY age";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "email") {
					$sql .=" ORDER BY email";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "cadeau") {
					$sql .=" ORDER BY cadeau";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "ami") {
					$sql .=" ORDER BY ami";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "date") {
					$sql .=" ORDER BY createdat";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				$rs = mysqli_query($link, $sql);
		?>
		<table align="center" width="100%" >
		<tr>
			<th class="a2" colspan="10" align="center">Liste des personnes ayant joué&nbsp;</th>
		</tr>
		<tr class="admin">
			<th align="center" class="a1">
			<a href="?op=inscrits&order=id&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; ID &nbsp;
			<a href="?op=inscrits&order=id&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=code&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; CODE &nbsp;
			<a href="?op=inscrits&order=code&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=gsm&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; GSM &nbsp;
			<a href="?op=inscrits&order=gsm&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=nom&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Nom &nbsp;
			<a href="?op=inscrits&order=nom&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=prenom&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Prenom &nbsp;
			<a href="?op=inscrits&order=prenom&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=age&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Age &nbsp;
			<a href="?op=inscrits&order=age&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=email&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Email &nbsp;
			<a href="?op=inscrits&order=email&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=cadeau&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Cadeau &nbsp;
			<a href="?op=inscrits&order=cadeau&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=ami&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Gsm Ami &nbsp;
			<a href="?op=inscrits&order=ami&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=date&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Date &nbsp;
			<a href="?op=inscrits&order=date&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
		</tr>
		<?php

				while($row = mysqli_fetch_object($rs)) {
		?>
		<tr class="admin">
			<td align="center">
			<?php echo $row->id; ?>
			</td>
			<td align="center">
			<?php echo $row->code; ?>
			</td>
			<td align="center">
			<?php echo $row->gsm; ?>
			</td>
			<td align="center">
			<?php echo $row->nom; ?>
			</td>
			<td align="center">
			<?php echo $row->prenom; ?>
			</td>
			<td align="center">
			<?php echo $row->age; ?>
			</td>
			<td align="center">
			<?php echo $row->email; ?>
			</td>
			<td align="center">
			<?php
			switch ($row->cadeau) {
				case 0: echo "Aucun"; break;
				case 1: echo "Recharge GSM"; break;
				case 2: echo "IPod"; break;
				case 3: echo "IPhone"; break;
				case 4: echo "Wii"; break;
				case 5: echo "PC Portable"; break;
			}
			?>
			</td>
			<td align="center">
			<?php echo $row->ami; ?>
			</td>
			<td align="center">
			<?php echo $row->createdat; ?>
			</td>
		</tr>
		<?php
				}
		?>
		</table>
		<?php
			} else {
		?>
		Aucun joueur trouv&eacute; en base de donn&eacute;e
		<?php
			}
		?>
		<hr size="1" noshade="noshade"/>
		<?php
		}
		?>

		<?php
		if($op=="revenants") {
			if($o > 0) {
				$sql = "SELECT * FROM oplayers";
				$order = $_REQUEST["order"];
				$dir = $_REQUEST["dir"];
				if($order == "id") {
					$sql .=" ORDER BY id";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "code") {
					$sql .=" ORDER BY code";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "gsm") {
					$sql .=" ORDER BY gsm";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "nom") {
					$sql .=" ORDER BY nom";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "prenom") {
					$sql .=" ORDER BY prenom";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "age") {
					$sql .=" ORDER BY age";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "email") {
					$sql .=" ORDER BY email";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "ami") {
					$sql .=" ORDER BY ami";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				if($order == "date") {
					$sql .=" ORDER BY createdat";
					if($dir == "asc") {
						$sql .=" ASC";
					}
					if($dir == "desc") {
						$sql .=" DESC";
					}
				}
				$rs = mysqli_query($link, $sql);
		?>
		<table align="center" width="100%" >
		<tr>
			<th class="a2" colspan="10" align="center">Liste des personnes ayant joué&nbsp;</th>
		</tr>
		<tr class="admin">
			<th align="center" class="a1">
			<a href="?op=inscrits&order=id&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; ID &nbsp;
			<a href="?op=inscrits&order=id&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=code&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; CODE &nbsp;
			<a href="?op=inscrits&order=code&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=gsm&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; GSM &nbsp;
			<a href="?op=inscrits&order=gsm&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=nom&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Nom &nbsp;
			<a href="?op=inscrits&order=nom&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=prenom&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Prenom &nbsp;
			<a href="?op=inscrits&order=prenom&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=age&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Age &nbsp;
			<a href="?op=inscrits&order=age&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=email&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Email &nbsp;
			<a href="?op=inscrits&order=email&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=ami&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Gsm Ami &nbsp;
			<a href="?op=inscrits&order=ami&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
			<th align="center" class="a1">
			<a href="?op=inscrits&order=date&dir=asc" class="admin"><img src="images/bas.png" alt="ASC" border="0" width="12px" height="12px" /></a>
			 &nbsp; Date &nbsp;
			<a href="?op=inscrits&order=date&dir=desc" class="admin"><img src="images/haut.png" alt="DESC" border="0" width="12px" height="12px" /></a>
			</th>
		</tr>
		<?php

				while($row = mysqli_fetch_object($rs)) {
		?>
		<tr class="admin">
			<td align="center">
			<?php echo $row->id; ?>
			</td>
			<td align="center">
			<?php echo $row->code; ?>
			</td>
			<td align="center">
			<?php echo $row->gsm; ?>
			</td>
			<td align="center">
			<?php echo $row->nom; ?>
			</td>
			<td align="center">
			<?php echo $row->prenom; ?>
			</td>
			<td align="center">
			<?php echo $row->age; ?>
			</td>
			<td align="center">
			<?php echo $row->email; ?>
			</td>
			<td align="center">
			<?php echo $row->ami; ?>
			</td>
			<td align="center">
			<?php echo $row->createdat; ?>
			</td>
		</tr>
		<?php
				}
		?>
		</table>
		<?php
			} else {
		?>
		Aucun joueur trouv&eacute; en base de donn&eacute;e
		<?php
			}
		?>
		<hr size="1" noshade="noshade"/>
		<?php
		}
		?>

		<?php
		if($op == "cadeaux") {
		?>
		<table align="center" width="100%" >
		<tr>
			<th class="a2" colspan="2" align="center">Ajout de Cadeaux :&nbsp;</th>
		</tr>
		<tr class="admin">
			<th align="center" class="a1" width="50%">
				Ajout d'un ensemble de cadeaux :
			</th>
			<th align="center" class="a1">
				Ajout d'un seul cadeau :
			</th>
		</tr>
		<tr class="admin">
			<td align="center">
				<form action="addcadeaux.php" method="post">
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<th align="right">Type de cadeaux : &nbsp; </th>
							<td>
								<select name="type" style="width: 150px">
									<option value="1">Recharge</option>
									<option value="2">IPod</option>
									<option value="3">IPhone</option>
									<option value="4">WII</option>
									<option value="5">PC Portable</option>
								</select>
							</td>
						</tr>
						<tr>
							<th align="right">Nombre de cadeaux : &nbsp; </th>
							<td> <input type="text" name="nbrcadeaux" size="5" style="width: 150px"/> </td>
						</tr>
						<tr>
							<th align="right">Fréquence des cadeaux : &nbsp; </th>
							<td> <input type="text" name="freq" size="5" style="width: 150px"/> </td>
						</tr>
						<tr>
							<td align="center" colspan="2"> <input type="submit" value="Ajouter" /> </td>
						</tr>
					</table>
					(Les cadeaux à gagné seront ajouté à liste après la liste de cadeaux diponibles s'ils existent)<br/><br/>
				</form>
			</td>
			<td align="center">
				<form action="addcadeau.php" method="post">
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<th align="right">Type de cadeaux : &nbsp; </th>
							<td>
								<select name="type" style="width: 150px">
									<option value="1">Recharge</option>
									<option value="2">IPod</option>
									<option value="3">IPhone</option>
									<option value="4">WII</option>
									<option value="5">PC Portable</option>
								</select>
							</td>
						</tr>
						<tr>
							<th align="right">numéro du prochain gagnant : &nbsp; </th>
							<td> <input type="text" name="freq" size="5" style="width: 150px"/> </td>
						</tr>
						<tr>
							<td align="center" colspan="2"> <input type="submit" value="Ajouter" /> </td>
						</tr>
					</table>
					(Le cadeaux à gagné sera ajouté à liste après la liste de cadeaux diponibles s'ils existent)<br/><br/>
				</form>
			</td>
		</tr>
		</table>
		<hr size="1" noshade="noshade"/>
		<?php
			if($c == 0) {
		?>
		Aucun cadeau restant n'a été trouvé dans la base de donnée
		<?php
			} else {
		?>
		<table align="center" width="100%" >
		<tr>
			<th class="a2" colspan="4" align="center">Cadeaux restants:&nbsp;</th>
		</tr>
		<tr class="admin">
			<th align="center" class="a1" width="20%">
				ID Gagnant :
			</th>
			<th align="center" class="a1" width="20%">
				Cadeau :
			</th>
			<th align="center" class="a1" width="40%">
				Date d'ajout :
			</th>
			<th align="center" class="a1" width="20%">
				&nbsp;
			</th>
		</tr>
		<?php
				if($g>0) {
					$sql = "SELECT * FROM winners WHERE used != TRUE AND num > (SELECT MAX(id) FROM players)";
				} else {
					$sql = "SELECT * FROM winners WHERE used != TRUE";
				}
				$rs = mysqli_query($link, $sql);
				while($row = mysqli_fetch_object($rs)) {
		?>
		<tr class="admin">
			<td align="center"><?php echo $row->num; ?></td>
			<td align="center">
			<?php
					switch ($row->cadeau) {
						case 0: echo "Aucun"; break;
						case 1: echo "Recharge GSM"; break;
						case 2: echo "IPod"; break;
						case 3: echo "IPhone"; break;
						case 4: echo "Wii"; break;
						case 5: echo "PC Portable"; break;
					}
			?>
			</td>
			<td align="center">
			<?php echo $row->createdat; ?>
			</td>
			<td align="center">
			<a href="delcadeau.php?num=<?php echo $row->num; ?>">Supprimer</a>
			</td>
		</tr>
		<?php
				}
		?>
		</table>
		<?php
			}
		?>
		<hr size="1" noshade="noshade"/>
		<?php
			if($cp == 0) {
		?>
		Aucun cadeau perdu n'a été trouvé dans la base de donnée
		<?php
			} else {
		?>
		<div align="center"><a href="delcadeauxperdu.php">Supprimer les cadeaux asynchrones</a></div>
		<table align="center" width="100%" >
		<tr>
			<th class="a2" colspan="4" align="center">Cadeaux perdus:&nbsp;</th>
		</tr>
		<tr class="admin">
			<th align="center" class="a1" width="20%">
				ID Gagnant :
			</th>
			<th align="center" class="a1" width="20%">
				Cadeau :
			</th>
			<th align="center" class="a1" width="40%">
				Date d'ajout :
			</th>
			<th align="center" class="a1" width="20%">
				&nbsp;
			</th>
		</tr>
		<?php
				$sql = "SELECT * FROM winners WHERE used != TRUE AND num <= (SELECT MAX(id) FROM players)";
				$rs = mysqli_query($link, $sql);
				while($row = mysqli_fetch_object($rs)) {
		?>
		<tr class="admin">
			<td align="center"><?php echo $row->num; ?></td>
			<td align="center">
			<?php
					switch ($row->cadeau) {
						case 0: echo "Aucun"; break;
						case 1: echo "Recharge GSM"; break;
						case 2: echo "IPod"; break;
						case 3: echo "IPhone"; break;
						case 4: echo "Wii"; break;
						case 5: echo "PC Portable"; break;
					}
			?>
			</td>
			<td align="center">
			<?php echo $row->createdat; ?>
			</td>
			<td align="center">
			<a href="delcadeau.php?num=<?php echo $row->num; ?>">Supprimer</a>
			</td>
		</tr>
		<?php
				}
		?>
		</table>
		<div align="center"><a href="delcadeauxperdu.php">Supprimer les cadeaux asynchrones</a></div>
		<?php
			}
		?>
		<hr size="1" noshade="noshade"/>
		<?php
			$sql = "SELECT * FROM winners WHERE used = TRUE";
			$res = mysqli_query($link, $sql);
			if(mysqli_num_rows($res) == 0) {
				echo "Aucun cadeau gagné trouvé dans la base de donnée";
			} else {
		?>
		<table align="center" width="100%" >
		<tr>
			<th class="a2" colspan="4" align="center">Cadeaux gagné:&nbsp;</th>
		</tr>
		<tr class="admin">
			<th align="center" class="a1" width="30%">
				ID Gagnant :
			</th>
			<th align="center" class="a1" width="30%">
				Cadeau :
			</th>
			<th align="center" class="a1" width="40%">
				Date d'ajout :
			</th>
		</tr>
		<?php
				while($row = mysqli_fetch_object($res)) {
		?>
		<tr class="admin">
			<td align="center"><?php echo $row->num; ?></td>
			<td align="center">
			<?php
					switch ($row->cadeau) {
						case 0: echo "Aucun"; break;
						case 1: echo "Recharge GSM"; break;
						case 2: echo "IPod"; break;
						case 3: echo "IPhone"; break;
						case 4: echo "Wii"; break;
						case 5: echo "PC Portable"; break;
					}
			?>
			</td>
			<td align="center">
			<?php echo $row->createdat; ?>
			</td>
		</tr>
		<?php
				}
		?>
		</table>
		<?php
			}
		?>
		<hr size="1" noshade="noshade"/>
		<?php
		}
		?>
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