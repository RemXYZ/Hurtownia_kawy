<?php 


$data_bd = unserialize($_COOKIE["sql_info"], ["allowed_classes" => false]);
$servername = $data_bd[0];
$login = $data_bd[1];
$pass = $data_bd[2];

$mysqli = new mysqli($servername,$login,$pass,"rem_karablin");
	if ($mysqli->connect_error) {
		echo "Połączenie nie powiodło się: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

?>