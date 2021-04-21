<?php 
// foreach ($_SERVER as $key => $value) {
// 	echo "<br>".$key." = ".$value;
// }
$data = unserialize($_COOKIE["sql_info"], ["allowed_classes" => false]);
$servername = $data[0];
$login = $data[1];
$pass = $data[2];

$mysqli = new mysqli($servername,$login,$pass,"rem_karablin");
	if ($mysqli->connect_error) {
		echo "Połączenie nie powiodło się: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

$sql_user = "SELECT * FROM user WHERE adres = '".$_SERVER['REMOTE_ADDR']."'";
if (!$mysqli->query($sql_user)) {
	if (!$mysqli->query("INSERT INTO user (adres) VALUES ('".$_SERVER['REMOTE_ADDR']."')")){
			echo "Nie udało się wkleić dane do tabeli: (" . $mysqli->errno . ") " . $mysqli->error;
	}
}else {
	$res = $mysqli->query($sql_user);
}

while ($row = $res->fetch_assoc()) {
$user_info = $row["id"];
}

// var_dump($_POST);
$p_name = $_POST["name"];
$p_id = $_POST["product"];
$p_pr = $_POST["price"];
$p_gr = $_POST["gram"];
$p_img = $_POST["img"];

$sql_cart = "INSERT INTO cart (user_id,product_id,price,gram) VALUES (
'".$user_info."',
'".$p_id."',
'".$p_pr."',
'".$p_gr."'
)";

if (!$mysqli->query($sql_cart)){
	echo "Nie udało się wkleić dane do tabeli: (" . $mysqli->errno . ") " . $mysqli->error;
}

$p_info2 = ["id" => $p_id,"img"=>$p_img, "name"=>$p_name,"price"=>$p_pr,"gram"=>$p_gr];

setcookie("product_loc", serialize($p_info2), time()+3600,'/');
setcookie("select_way","true", time()+3600,'/');

header('Location: ' . $_SERVER['HTTP_REFERER']);

?>