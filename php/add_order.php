<?php 

if ($_COOKIE['close_PUH'] != "Mode_online") {
	$previousurl = $_SERVER['HTTP_REFERER'];
	header('Location: ' .$previousurl);
	die();
}

$data_bd = unserialize($_COOKIE["sql_info"], ["allowed_classes" => false]);
$servername = $data_bd[0];
$login = $data_bd[1];
$pass = $data_bd[2];

$mysqli = new mysqli($servername,$login,$pass,"rem_karablin");
	if ($mysqli->connect_error) {
		echo "Połączenie nie powiodło się: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}


$previousurl = $_SERVER['HTTP_REFERER'];
// var_dump($_POST);
if (isset($_POST['PUP_but1'])) {
	if (isset($_COOKIE['cart_msg'])) {
		setcookie('cart_msg', null, -1, '/');
	}
	// $new_url=explode("index.php",$previousurl);
	header('Location: ' .$previousurl);
	die();
}
if (isset($_POST['order'])) {

$id = $_POST["post_id"];
$email = $_POST['email'];
$comm = $_POST["comment"];
//SPRAWDZAM CZY JEST ID, JESLI NIE MA TO ODRAZU DO DOMU, bo id musi byc zawsze

// var_dump($id);

// $id = "";

if ($id == "" || $id == "undefined") {
	header('Location: ' .$previousurl);
	die();
}


//pakuje info o produkcie dla PHP

$sql_cart = "SELECT * FROM cart WHERE id = '".$id."'";
$res = $mysqli->query($sql_cart);

while ($row = $res->fetch_assoc()) {
$p_user_id = $row["user_id"];
$p_id = $row["product_id"];
$p_pr = $row["price"];
$p_gr = $row["gram"];
$p_ordered = $row['ordered'];
}

if ($p_ordered == "1") {
	header('Location: ' .$previousurl);
	die();
}

$sql_produkt = "SELECT * FROM products WHERE id = '".$p_id."'";

$res = $mysqli->query($sql_produkt);

while ($row = $res->fetch_assoc()) {
$p_name = $row["name"];
$p_img = $row["img"];
}

$p_info = ["id" => $id,"img"=>$p_img, "name"=>$p_name,"price"=>$p_pr,"gram"=>$p_gr,"email"=>$email,"comm"=>$comm];

setcookie("product_loc_cart", serialize($p_info), time()+3600,'/');

// var_dump($p_info);

if ($email == "") {
	setcookie("cart_msg","Pole e-mail musi być uzupełnione", time()+3600,'/');
	header('Location: ' .$previousurl);
	die();
}

$email_f = filter_var($email, FILTER_VALIDATE_EMAIL);

if (!$email_f) {
	setcookie("cart_msg","E-mail nie jest poprawny", time()+3600,'/');
	header('Location: ' .$previousurl);
	die();
}


$mysqli->query("UPDATE cart SET ordered = '1' WHERE id = '".$id."'");

$sql_order = "INSERT INTO orders (user_id,product_id,price,gram,email,comment,data) VALUES (
'".$p_user_id."',
'".$p_id."',
'".$p_pr."',
'".$p_gr."',
'".$email_f."',
'".$comm."',
NOW()
)";

$mysqli->query($sql_order);


$mysqli->close();

header('Location: ' .$previousurl);


// var_dump($email_f);

}

?>