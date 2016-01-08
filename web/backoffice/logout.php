<?php
/*
 * Created on 08 nov. 2008 
 * by Salah Abdelkader Seif Eddine 
 * using PHPeclipse
 */
session_start();
$_SESSION["alogged"] = null;
header("Location: login.php");
exit(0);
?>
