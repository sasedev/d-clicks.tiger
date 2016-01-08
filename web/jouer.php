<?php
require_once("cnf/cfg.php");
$code = "";
if (isset($_POST['code'])) {
	$code = $_POST['code'];
}
$content = "";
if (isset($_POST["content"])) {
	$content = $_POST['content'];
}
$error = 0;
if($content != "vform" && $content != "vgame") {
	$content = "vcode";
}
if($content == "vform") {
	$code = "";
	if (isset($_POST["code"])) {
		$code = $_POST['code'];
	}
	$link = mysqli_connect($dbhost, $dbuser, $dbpass);
	if (!$link) {
		die('Impossible de se connecter : ' . mysqli_error());
	}

	// Rendre la base de données $dbname, la base courante
	$dbln = mysqli_select_db($link, $dbname);
	if (!$dbln) {
		die ('Impossible de sélectionner la base de donn&eacute;es : ' . mysqli_error());
	}
	$sql = "SELECT * FROM codegens WHERE code = \"$code\"";
	$res = mysqli_query($link, $sql) or die ("Impossible d'executer la requete");
	if(mysqli_num_rows($res) == 0) {
		$error = 1;
		$content = "vcode";
	} else {
		$codegen = mysqli_fetch_object($res);
		if($codegen->used == TRUE) {
			$error = 2;
			$content = "vcode";
		}
	}
}
if($content == "vgame") {
	if (isset($_POST["code"])) {
		$code = $_POST['code'];
	}
	$nom = "";
	if (isset($_POST["nom"])) {
		$nom = $_POST['nom'];
	}
	$prenom = "";
	if (isset($_POST["prenom"])) {
		$prenom = $_POST['prenom'];
	}
	$code = $_POST['code'];
	if (isset($_POST["code"])) {
		$code = $_POST['code'];
	}
	$age = "";
	if (isset($_POST["age"])) {
		$age = $_POST['age'];
	}
	$gsm = "";
	if (isset($_POST["gsm"])) {
		$gsm = $_POST['gsm'];
	}
	$email = "";
	if (isset($_POST["email"])) {
		$email = $_POST['email'];
	}
	$ami = "";
	if (isset($_POST["ami"])) {
		$ami = $_POST['ami'];
	}
	$link = mysqli_connect($dbhost, $dbuser, $dbpass);
	if (!$link) {
		die('Impossible de se connecter : ' . mysqli_error());
	}

	// Rendre la base de données $dbname, la base courante
	$dbln = mysqli_select_db($link, $dbname);
	if (!$dbln) {
		die ('Impossible de sélectionner la base de donn&eacute;es : ' . mysqli_error());
	}
	$sql = "SELECT * FROM codegens WHERE code = \"$code\"";
	$res = mysqli_query($link, $sql) or die ("Impossible d'executer la requete 1");
	if(mysqli_num_rows($res) == 0) {
		$error = 1;
		$content = "vcode";
	} else {
		$codegen = mysqli_fetch_object($res);
		if($codegen->used == TRUE) {
			$error = 2;
			$content = "vcode";
		} else {
			if($codegen->phone != $gsm) {
				$error = 3;
				$content = "vform";
			} else {
				// verification s'il a deja gagner deux fois
				$sql = "SELECT COUNT(id) AS w FROM players WHERE gsm = '$gsm' and cadeau != 0";
				$res = mysqli_query($link, $sql) or die ("Impossible d'executer la requete 10");
				$resultat = mysqli_fetch_object($res);
				if($resultat->w >= 2) {
					$cadeau = 0;
					$ami = trim($ami);
					if($ami != "") {
						$sql = "INSERT INTO oplayers (code, gsm, nom, prenom, age, email, ami, createdat) VALUES (\"$code\", \"$gsm\", \"$nom\", \"$prenom\", \"$age\", \"$email\", \"$ami\", NOW())";
					} else {
						$sql = "INSERT INTO oplayers (code, gsm, nom, prenom, age, email, ami, createdat) VALUES (\"$code\", \"$gsm\", \"$nom\", \"$prenom\", \"$age\", \"$email\", NULL, NOW())";
					}
					// insertion du player dans la base
					mysqli_query($link, $sql) or die ("Impossible d'executer la requete 11");
				} else {
					// génération du code player
					$sql = "SELECT MAX( id ) AS m FROM players";
					$res = mysqli_query($link, $sql) or die ("Impossible d'executer la requete 2");
					$resultat = mysqli_fetch_object($res);
					if($resultat->m == NULL) {
						$id = 1;
					} else {
						$id = $resultat->m + 1;
					}
					// verification si le code player correspond à un numéro gagnant
					$sql = "SELECT * FROM winners WHERE num = $id AND used = FALSE";
					$res = mysqli_query($link, $sql) or die ("Impossible d'executer la requete 3");
					if(mysqli_num_rows($res) == 0) {
						// pas de cadeau
						$cadeau = 0;
					} else {
						// cadeau
						$resultat = mysqli_fetch_object($res);
						$cadeau = $resultat->cadeau;
						// suppression du winner de la liste
						$sql = "UPDATE winners SET used = TRUE WHERE num = $id";
						mysqli_query($link, $sql) or die ("Impossible d'executer la requete 4");
					}
					$ami = trim($ami);
					if($ami != "") {
						$sql = "INSERT INTO players (id, code, gsm, nom, prenom, age, email, ami, cadeau, createdat) VALUES ($id, \"$code\", \"$gsm\", \"$nom\", \"$prenom\", \"$age\", \"$email\", \"$ami\", $cadeau, NOW())";
					} else {
						$sql = "INSERT INTO players (id, code, gsm, nom, prenom, age, email, ami, cadeau, createdat) VALUES ($id, \"$code\", \"$gsm\", \"$nom\", \"$prenom\", \"$age\", \"$email\", NULL, $cadeau, NOW())";
					}
					// insertion du player dans la base
					mysqli_query($link, $sql) or die ("Impossible d'executer la requete 5");
				}
				// suppression du code de la liste
				$sql = "UPDATE codegens SET used = TRUE WHERE code = \"$code\"";
				mysqli_query($link, $sql) or die ("Impossible d'executer la requete 6");
				// ajout des requetes serveur SMS
				///*
				if($ami != "") {
					// http://tiger.nesmacom.com/url.aspx?type=parrainage&to=$ami&code=$code
					@fopen("http://196.203.44.42/L2TJeuTom/url.aspx?type=parrainage&to=$ami&code=$code", "r");
					@fopen("http://tiger.d-clicks.sasedev.net/url.php?type=parrainage&to=$ami&code=$code", "r");
				}
				if($cadeau == 1) {
					// http://tiger.nesmacom.com/url.aspx?type=recharge&code=$code
					@fopen("http://196.203.44.42/L2TJeuTom/url.aspx?type=recharge&code=$code", "r");
					@fopen("http://tiger.d-clicks.sasedev.net/url.php?type=recharge&code=$code", "r");
				}
				if($cadeau >1) {
					// http://tiger.nesmacom.com/url.aspx?type=cadeau&code=$code
					//@fopen("http://tiger.nesmacom.com/url.aspx?type=cadeau&code=$code", "r");
					if($cadeau == 2) {
						@fopen("http://196.203.44.42/L2TJeuTom/url.aspx?type=cadeau&code=$code&cadeau=IPOD", "r");
						@fopen("tiger.d-clicks.sasedev.net/url.php?type=cadeau&code=$code&cadeau=IPOD", "r");
					}
					if($cadeau == 3) {
						@fopen("http://196.203.44.42/L2TJeuTom/url.aspx?type=cadeau&code=$code&cadeau=IPhone", "r");
						@fopen("tiger.d-clicks.sasedev.net/url.php?type=cadeau&code=$code&cadeau=IPhone", "r");
					}
					if($cadeau == 4) {
						@fopen("http://196.203.44.42/L2TJeuTom/url.aspx?type=cadeau&code=$code&cadeau=Wii", "r");
						@fopen("tiger.d-clicks.sasedev.net/url.php?type=cadeau&code=$code&cadeau=Wii", "r");
					}
					if($cadeau == 5) {
						@fopen("http://196.203.44.42/L2TJeuTom/url.aspx?type=cadeau&code=$code&cadeau=PcPortable", "r");
						@fopen("tiger.d-clicks.sasedev.net/url.php?type=cadeau&code=$code&cadeau=PcPortable", "r");
					}
				}//*/
			}
		}
	}
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="keywords" content="75, consoles, des, gsm, ipod, loading, pc, portables, recharges, shuffle, touch, wii"/>
<title>Tiger - Gagnez des MILLIER de CADEAUX</title>
<style type="text/css">
#page {
position: absolute;
width: 192px;        /* selon la largeur voulue */
margin-left: -512px;  /* moitie de width */
left: 50%;          /* constant, toujours 50% */
height: 548px;       /* selon la quantite de texte */
margin-top: -274px;   /* moitie de height */
top: 50%;           /* constant, toujours 50% */
border: 0px;
background-color: #f08825;
}
</style>
<link rel="stylesheet" type="text/css" href="messages.css" />
<script src="AC_RunActiveContent.js" type="text/javascript" language="javascript"></script>
<script src="js/jquery-1.4.min.js" type="text/javascript" language="javascript"></script>
<script type="text/javascript" language="javascript">
function IsNumeric(strString) {
	var strValidChars = "0123456789";
	var strChar;
	var blnResult = true;
	if (strString.length == 0) return false;
	for (i = 0; i < strString.length && blnResult == true; i++) {
		strChar = strString.charAt(i);
		if (strValidChars.indexOf(strChar) == -1) {
			blnResult = false;
		}
	}
	return blnResult;
}

var MSGTIMER = 20;
var MSGSPEED = 5;
var MSGOFFSET = 3;
var MSGHIDE = 3;

function inlineMsg(target,string,autohide) {
	var msg;
	var msgcontent;
	if(!document.getElementById('msg')) {
		msg = document.createElement('div');
		msg.id = 'msg';
		msgcontent = document.createElement('div');
		msgcontent.id = 'msgcontent';
		document.body.appendChild(msg);
		msg.appendChild(msgcontent);
		msg.style.filter = 'alpha(opacity=0)';
		msg.style.opacity = 0;
		msg.alpha = 0;
	} else {
		msg = document.getElementById('msg');
		msgcontent = document.getElementById('msgcontent');
	}
	msgcontent.innerHTML = string;
	msg.style.display = 'block';
	var msgheight = msg.offsetHeight;
	var targetdiv = document.getElementById(target);
	targetdiv.focus();
	var targetheight = targetdiv.offsetHeight;
	var targetwidth = targetdiv.offsetWidth;
	var topposition = topPosition(targetdiv) - ((msgheight - targetheight) / 2);
	var leftposition = leftPosition(targetdiv) + targetwidth + MSGOFFSET;
	msg.style.top = topposition + 'px';
	msg.style.left = leftposition + 'px';
	clearInterval(msg.timer);
	msg.timer = setInterval("fadeMsg(1)", MSGTIMER);
	if(!autohide) {
		autohide = MSGHIDE;
	}
	window.setTimeout("hideMsg()", (autohide * 1000));
}
function hideMsg(msg) {
	var msg = document.getElementById('msg');
	if(!msg.timer) {
		msg.timer = setInterval("fadeMsg(0)", MSGTIMER);
	}
}
function fadeMsg(flag) {
	if(flag == null) {
		flag = 1;
	}
	var msg = document.getElementById('msg');
	var value;
	if(flag == 1) {
		value = msg.alpha + MSGSPEED;
	} else {
		value = msg.alpha - MSGSPEED;
	}
	msg.alpha = value;
	msg.style.opacity = (value / 100);
	msg.style.filter = 'alpha(opacity=' + value + ')';
	if(value >= 99) {
		clearInterval(msg.timer);
		msg.timer = null;
	} else if(value <= 1) {
		msg.style.display = "none";
		clearInterval(msg.timer);
	}
}

// calculate the position of the element in relation to the left of the browser //
function leftPosition(target) {
	var left = 0;
	if(target.offsetParent) {
		while(1) {
			left += target.offsetLeft;
			if(!target.offsetParent) {
				break;
			}
			target = target.offsetParent;
		}
	} else if(target.x) {
		left += target.x;
	}
	return left;
}

// calculate the position of the element in relation to the top of the browser window //
function topPosition(target) {
	var top = 0;
	if(target.offsetParent) {
		while(1) {
			top += target.offsetTop;
			if(!target.offsetParent) {
				break;
			}
			target = target.offsetParent;
		}
	} else if(target.y) {
		top += target.y;
	}
	return top;
}
// preload the arrow //
if(document.images) {
	arrow = new Image(7,80);
	arrow.src = "images/msg_arrow.gif";
}

function validate1(form1) {
	var code = form1.code.value;
	code.replace(/(?:^\s+|\s+$)/g, "");
	if(code == "" || code == " ") {
		inlineMsg('code','<strong>Erreur</strong><br />Vous devez entrer le code.',2);
		return false;
	}
	if(code.length != 4) {
		inlineMsg('code','<strong>Erreur</strong><br />Le code que vous avez entrer n\'est pas valide.',2);
		return false;
	}
	return true;
}

function validate2(form2) {
	var nom = form2.nom.value;
	nom.replace(/(?:^\s+|\s+$)/g, "");
	var prenom = form2.prenom.value;
	prenom.replace(/(?:^\s+|\s+$)/g, "");
	var age = form2.age.value;
	age.replace(/(?:^\s+|\s+$)/g, "");
	var gsm = form2.gsm.value;
	gsm.replace(/(?:^\s+|\s+$)/g, "");
	var email = form2.email.value;
	email.replace(/(?:^\s+|\s+$)/g, "");
	var ami = form2.ami.value;
	ami.replace(/(?:^\s+|\s+$)/g, "");
	var nameRegex = /^[a-zA-Z]+(([\'\,\.\- ][a-zA-Z ])?[a-zA-Z]*)*$/;
	var phoneRegex = /^[2|9]+([0-9]*)$/;
	var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;

	if(nom == "" || nom.length < 3 || !nom.match(nameRegex)) {
		inlineMsg('nom','<strong>Erreur</strong><br />Vous devez entrer votre nom.',2);
		return false;
	}
	if(prenom == "" || prenom.length < 3 || !prenom.match(nameRegex)) {
		inlineMsg('prenom','<strong>Erreur</strong><br />Vous devez entrer votre prénom.',2);
		return false;
	}
	if(age == "") {
		inlineMsg('age','<strong>Erreur</strong><br />Vous devez entrer votre age.',2);
		return false;
	}
	if(!IsNumeric(age) || age.length > 3) {
		inlineMsg('age','<strong>Erreur</strong><br />Vous avez entrer un age invalide.',2);
		return false;
	}
	if(gsm == "") {
		inlineMsg('gsm','<strong>Erreur</strong><br />Vous devez entrer votre numéro de GSM.',2);
		return false;
	}
	if(gsm.length != 8 || !gsm.match(phoneRegex)) {
		inlineMsg('gsm','<strong>Erreur</strong><br />Vous avez entrer un numéro de GSM invalide.',2);
		return false;
	}
	if(email == "") {
		inlineMsg('email','<strong>Erreur</strong><br />Vous devez entrer votre email.',2);
		return false;
	}
	if(!email.match(emailRegex)) {
		inlineMsg('email','<strong>Erreur</strong><br />Vous avez entrer un email invalide.',2);
		return false;
	}
	if(ami.length >= 1) {
		if(ami.length != 8 || !ami.match(phoneRegex)) {
			inlineMsg('ami','<strong>Erreur</strong><br />Vous avez entrer un numéro de GSM ami invalide.',2);
			return false;
		}
	}
	return true;
}
function validateForm1() {
	var valid = validate1(document.game);
	if(valid == true) {
		document.game.submit();
	}
}
function validateForm2() {
	var valid = validate2(document.game);
	if(valid == true) {
		document.game.submit();
	}
}
</script>
</head>
<body bgcolor="#f08825">
<div id="page">
<table cellpadding="0" width="1024" cellspacing="0" border="0" style="table-layout:fixed; background-image:url(fond.jpg); background-repeat:no-repeat; background-position:top center; height: 584">
<tr>
	<td width="270">
		<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','270','height','584','title','Tigre','src','tigre','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','tigre' );</script>
		<noscript>
			<object
	        classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
	        codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0"
	        id="tigre"
	        width="270" height="584"
	      >
	        <param name="movie" value="tigre.swf"/>
	        <param name="wmode" value="transparent"/>
	        <param name="quality" value="high"/>
	        <param name="seamlesstabbing" value="false"/>
	        <param name="allowscriptaccess" value="samedomain"/>
	      </object>
      </noscript>
	</td>
	<td align="left" valign="middle">
	<?php
	?>
	<?php
	if($content == "vcode") {
	?>
		<form method="post" action="" id="game" name="game" onsubmit="return validate1(this)">
		<table cellpadding="2" cellspacing="0" border="0" style="width:500px; padding-top:90px; padding-left:50px;">
		<tr>
			<td align="right" height="34">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','130','height','34','title','code','src','code','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','code' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="130" height="34" title="code">
						<param name="movie" value="code.swf"/>
						<param name="quality" value="high"/>
						<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
			<td align="left"> &nbsp;<input style="width: 300" name="code" id="code"  value="<?php echo $code; ?>" /><input type="hidden" name="content" id="content" value="vform" />
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" height="281">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','281','title','Entrer le Code','src','entrez-code','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','entrez-code' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="281">
						<param name="movie" value="entrez-code.swf" />
						<param name="quality" value="high" />
						<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" valign="middle" height="144">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','205','height','144','title','Jouer','src','jouer1','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','jouer1' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="205" height="144" title="Jouer">
              			<param name="movie" value="jouer1.swf"/>
              			<param name="quality" value="high"/>
              			<param name="wmode" value="transparent"/>
              		</object>
              	</noscript>
			</td>
		</tr>
		</table>
		</form>
	<?php
	}
	if($content == "vform") {
	?>
		<form method="post" action="" id="game" name="game" onsubmit="return validate2(this)">
		<table cellpadding="2" cellspacing="0" border="0" style="width:500px; padding-top:90px; padding-left:50px;">
		<tr>
			<td width="130">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','130','height','34','title','Nom','src','nom','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','nom' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="130" height="34" title="Nom">
						<param name="movie" value="nom.swf"/>
						<param name="quality" value="high"/>
						<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
			<td> &nbsp;<input style="width: 300" name="nom" id="nom"  value="<?php echo $nom; ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','130','height','34','title','Pr&eacute;nom','src','prenom','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','prenom' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="130" height="34" title="Pr&eacute;nom">
						<param name="movie" value="prenom.swf"/>
						<param name="quality" value="high"/>
						<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
			<td> &nbsp;<input style="width: 300" name="prenom" id="prenom"  value="<?php echo $prenom; ?>"  />
			</td>
		</tr>
		<tr>
			<td>
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','130','height','34','title','Age','src','age','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','age' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="130" height="34" title="Age">
						<param name="movie" value="age.swf"/>
						<param name="quality" value="high"/>
						<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
			<td> &nbsp;<input style="width: 300" name="age" id="age"  value="<?php echo $age; ?>"  />
			</td>
		</tr>
		<tr>
			<td>
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','130','height','34','title','GSM','src','gsm','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','gsm' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="130" height="34" title="GSM">
						<param name="movie" value="gsm.swf"/>
						<param name="quality" value="high"/>
						<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
			<td> &nbsp;<input style="width: 300" name="gsm" id="gsm"  value="<?php echo $gsm; ?>"  />
			</td>
		</tr>
		<tr>
			<td>
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','130','height','34','title','Email','src','email','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','email' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="130" height="34" title="EMAIL">
						<param name="movie" value="email.swf"/>
						<param name="quality" value="high"/>
						<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
			<td> &nbsp;<input style="width: 300" name="email" id="email"  value="<?php echo $email; ?>"  />
			</td>
		</tr>
		<tr>
			<td>
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','130','height','34','title','Gsm d\'un Ami','src','gsm-ami','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','gsm-ami' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="130" height="34" title="Gsm d'un Ami">
						<param name="movie" value="gsm-ami.swf"/>
						<param name="quality" value="high"/>
						<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
			<td> &nbsp;<input style="width: 300" name="ami" id="ami"  value="<?php echo $ami; ?>"  />
				<input type="hidden" name="code" id="code" value="<?php echo $code; ?>"/>
				<input type="hidden" name="content" id="content" value="vgame" />
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" valign="middle">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','205','height','144','title','Jouer','src','jouer2','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','jouer2' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="205" height="144" title="Jouer">
              			<param name="movie" value="jouer2.swf"/>
              			<param name="quality" value="high"/>
              			<param name="wmode" value="transparent"/>
              		</object>
              	</noscript>
			</td>
		</tr>
		</table>
		</form>
	<?php
	}
	if($content == "vgame") {
		// pas de cadeau
		if($cadeau == 0) {
	?>
		<table cellpadding="2" cellspacing="0" border="0" style="width:500px; padding-top:90px; padding-left:50px;">
		<tr>
			<td align="center">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','338','height','115','title','Vous avez perdu','src','vous-avez-perdu','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','vous-avez-perdu' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="338" height="115" title="Vous avez perdu">
						<param name="movie" value="vous-avez-perdu.swf" />
						<param name="quality" value="high" />
              			<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
		</tr>
		<tr>
			<td align="center">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','281','title','Vous avez perdu','src','gagne-coffre-vide','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','gagne-coffre-vide' )</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="281" title="Vous avez perdu">
						<param name="movie" value="gagne-coffre-vide.swf" />
						<param name="quality" value="high" />
              			<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
		</tr>
		</table>
	<?php
		}
		// recharge
		if($cadeau == 1) {
	?>
		<table cellpadding="2" cellspacing="0" border="0" style="width:500px; padding-top:90px; padding-left:50px;">
		<tr>
			<td align="center">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','338','height','115','title','Vous avez gagné !','src','vous-avez-gagne-recharge-5dinars','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','vous-avez-gagne-recharge-5dinars' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="338" height="115" title="Vous avez gagné !">
						<param name="movie" value="vous-avez-gagne-recharge-5dinars.swf" />
						<param name="quality" value="high" />
              			<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
		</tr>
		<tr>
			<td align="center"><b style="color: black;">Vous allez bientôt recevoir un SMS avec votre code de recharge</b></td>
		</tr>
		<tr>
			<td align="center">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','281','title','Vous avez gagné !','src','gagne-recharge','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','gagne-recharge' )</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="281" title="Vous avez gagné !">
						<param name="movie" value="gagne-recharge.swf" />
						<param name="quality" value="high" />
              			<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
		</tr>
		</table>
	<?php
		}
		// IPOD
		if($cadeau == 2) {
	?>
		<table cellpadding="2" cellspacing="0" border="0" style="width:500px; padding-top:90px; padding-left:50px;">
		<tr>
			<td align="center">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','338','height','115','title','Vous avez gagné !','src','vous-avez-gagne-ipod-shuffle','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','vous-avez-gagne-ipod-shuffle' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="338" height="115" title="Vous avez gagné !">
						<param name="movie" value="vous-avez-gagne-ipod-shuffle.swf" />
						<param name="quality" value="high" />
              			<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
		</tr>
		<tr>
			<td align="center">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','281','title','Vous avez gagné !','src','gagne-ipad','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','gagne-ipad' )</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="281" title="Vous avez gagné !">
						<param name="movie" value="gagne-ipad.swf" />
						<param name="quality" value="high" />
              			<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
		</tr>
		</table>
	<?php
		}
		// IPHONE
		if($cadeau == 3) {
	?>
		<table cellpadding="2" cellspacing="0" border="0" style="width:500px; padding-top:90px; padding-left:50px;">
		<tr>
			<td align="center">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','338','height','115','title','Vous avez gagné !','src','vous-avez-gagne-ipod-touch','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','vous-avez-gagne-ipod-touch' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="338" height="115" title="Vous avez gagné !">
						<param name="movie" value="vous-avez-gagne-ipod-touch.swf" />
						<param name="quality" value="high" />
              			<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
		</tr>
		<tr>
			<td align="center">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','281','title','Vous avez gagné !','src','gagne-iphone','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','gagne-iphone' )</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="281" title="Vous avez gagné !">
						<param name="movie" value="gagne-iphone.swf" />
						<param name="quality" value="high" />
              			<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
		</tr>
		</table>
	<?php
		}
		// Wii
		if($cadeau == 4) {
	?>
		<table cellpadding="2" cellspacing="0" border="0" style="width:500px; padding-top:90px; padding-left:50px;">
		<tr>
			<td align="center">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','338','height','115','title','Vous avez gagné !','src','vous-avez-gagne-console-wii','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','vous-avez-gagne-console-wii' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="338" height="115" title="Vous avez gagné !">
						<param name="movie" value="vous-avez-gagne-console-wii.swf" />
						<param name="quality" value="high" />
              			<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
		</tr>
		<tr>
			<td align="center">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','281','title','Vous avez gagné !','src','gagne-wii','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','gagne-wii' )</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="281" title="Vous avez gagné !">
						<param name="movie" value="gagne-wii.swf" />
						<param name="quality" value="high" />
              			<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
		</tr>
		</table>
	<?php
		}
		// PC
		if($cadeau == 5) {
	?>
		<table cellpadding="2" cellspacing="0" border="0" style="width:500px; padding-top:90px; padding-left:50px;">
		<tr>
			<td align="center">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','338','height','115','title','Vous avez gagné !','src','vous-avez-gagne-pc-portable','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','vous-avez-gagne-pc-portable' );</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="338" height="115" title="Vous avez gagné !">
						<param name="movie" value="vous-avez-gagne-pc-portable.swf" />
						<param name="quality" value="high" />
              			<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
		</tr>
   		<tr>
			<td align="center">
				<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','280','height','281','title','Vous avez gagné !','src','gagne-pc-portable','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','gagne-pc-portable' )</script>
				<noscript>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="280" height="281" title="Vous avez gagné !">
						<param name="movie" value="gagne-pc-portable.swf" />
						<param name="quality" value="high" />
              			<param name="wmode" value="transparent"/>
					</object>
				</noscript>
			</td>
		</tr>
		</table>
	<?php
		}
	}
	?>
	</td>
    <td width="192">
    	<script type="text/javascript">AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','192','height','584','title','Menu-droit','src','menu-droit','quality','high','wmode','transparent','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','menu-droit' );</script>
		<noscript>
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
			 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0"
			 id="menu-droit"
			 width="192" height="584">
				<param name="movie" value="menu-droit.swf"/>
				<param name="wmode" value="transparent"/>
				<param name="quality" value="high"/>
				<param name="seamlesstabbing" value="false"/>
				<param name="allowscriptaccess" value="samedomain"/>
			</object>
		</noscript>
    </td>
</tr>
</table>
</div>
<?php
if($error != 0) {
	if($error == 1) {
?>
<script type="text/javascript" language="javascript">
inlineMsg('code',"<strong>Erreur</strong><br />Le code que vous avez entrer n\'est pas valide.",2);
</script>
<?php
	}
	if($error == 2) {
?>
<script type="text/javascript" language="javascript">
inlineMsg('code',"<strong>Erreur</strong><br />Le code que vous avez entrer a déja été utilisé auparavant.",2);
</script>
<?php
	}
	if($error == 3) {
?>
<script type="text/javascript" language="javascript">
inlineMsg('gsm',"<strong>Erreur</strong><br />Votre code ne correspond pas à ce numéro de GSM.",2);
</script>
<?php
	}
}
?>
	<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	try {
	var pageTracker = _gat._getTracker("UA-15683877-1");
	pageTracker._trackPageview();
	} catch(err) {}</script>
</body>
</html>