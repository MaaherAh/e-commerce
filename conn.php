<?php

$dsn = "mysql:host=localhost;dbname=shop";
$user = "root";
$pass = "";
$options = array(
	PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
	PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION,
);

try{
	$conn = new PDO($dsn, $user, $pass, $options);
}catch(PDOException $e){
	echo "Failed to connect " . $e -> getMessage() . "<br/>";
}

?>
