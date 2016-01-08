<?php
$type = "";
if (isset($_REQUEST["type"])) {
	$type = $_REQUEST["type"];
}
$ami = "";
if (isset($_REQUEST["to"])) {
	$ami = $_REQUEST["to"];
}
$code = "";
if (isset($_REQUEST["code"])) {
	$code = $_REQUEST["code"];
}
$cadeau = "";
if (isset($_REQUEST["cadeau"])) {
	$cadeau = $_REQUEST["cadeau"];
}
if($type == "parrainage") {
	$to      = 'sinechine@gmail.com';
	$subject = 'Parainage Tiger';
	$message = 'Parainage : ami = '.$ami." ; code = ".$code;
	$headers = 'From: webmaster@sasedev.net' . "\r\n" .
	'Reply-To: seif@sasedev.net' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	mail($to, $subject, $message, $headers);
	$to      = 'seif.salah@gmail.com';
	mail($to, $subject, $message, $headers);
}
if($type == "recharge") {
	$to      = 'sinechine@gmail.com';
	$subject = 'Recharge Tiger';
	$message = 'Recharge : code = '.$code;
	$headers = 'From: webmaster@sasedev.net' . "\r\n" .
	'Reply-To: seif@sasedev.net' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	mail($to, $subject, $message, $headers);
	$to      = 'seif.salah@gmail.com';
	mail($to, $subject, $message, $headers);
}
if($type == "cadeau") {
	$to      = 'sinechine@gmail.com';
	$subject = 'Recharge Tiger';
	$message = 'Recharge : code = '.$code."; cadeau = ".$cadeau;
	$headers = 'From: webmaster@sasedev.net' . "\r\n" .
	'Reply-To: seif@sasedev.net' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	mail($to, $subject, $message, $headers);
	$to      = 'seif.salah@gmail.com';
	mail($to, $subject, $message, $headers);
}
?>
