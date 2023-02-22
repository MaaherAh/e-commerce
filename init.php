<?php
	
	include "conn.php";

	$funcs 		= "includes/func/";
	$temp 		= "includes/temp/";
	$cssLips 	= "includes/libs/css/";
	$jsLips 	= "includes/libs/js/";
	$lang 		= "includes/lang/";
	$cssAdm 	= "layout/css/";
	$jsAdm 		= "layout/js/";

	include $funcs . "funcs.php";
	include $lang . "en.php";
	include $temp . "header.php";
	if(!isset($noNavbar)) {include $temp . "navbar.php";}
?>