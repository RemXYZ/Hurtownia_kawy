<?php 
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);


$show_products = false;

$data2 = unserialize($_COOKIE["sql_info"], ["allowed_classes" => false]);
$servername = $data2[0];
$login = $data2[1];
$pass = $data2[2];

$mysqli = new mysqli($servername,$login,$pass,"rem_karablin");
	if ($mysqli->connect_error) {
		$error =  "Połączenie nie powiodło się: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

$data_product = [];


//TRYB OFFLINE

if (isset($_COOKIE["product"])) {
$data_product = unserialize($_COOKIE["product"], ["allowed_classes" => false]);

foreach ($data_product as $key => $value) {
	$name_cart = $data_product[$key]["name"] ?? NULL;
	$price_cart = $data_product[$key]["price"] ?? NULL;
	$gram_cart = $data_product[$key]["gram"] ?? NULL;
}

$show_products = true;

}

//TRYB ONLINE

if ($_COOKIE['close_PUH'] == "Mode_online") {


//SZYKAMY ID USERA 
$sql_user = "SELECT * FROM user WHERE adres = '".$_SERVER['REMOTE_ADDR']."'";
	$user_info = $mysqli->query($sql_user)->fetch_assoc()["id"];

//JESLI JESTESMY W TRYBIE ONLINE POKAZY SIE TO

if ($_COOKIE['close_PUH'] == "Mode_online"){
//ZAPYTAMY O PRODUKCIE W KOSZYKU
$sql_cart = "SELECT * FROM cart WHERE user_id = '".$user_info."'";
	$res = $mysqli->query($sql_cart);
	while ($row = $res->fetch_assoc()) {
		$id_cart = $row["id"] ?? NULL;
		$product_cart = $row["product_id"] ?? NULL;
		$name_cart = $row["name"] ?? NULL;
		$sql_product = "SELECT * FROM products WHERE id = '".$product_cart."'";
		$name_cart = $mysqli->query($sql_product)->fetch_assoc()["name"];
		$img_cart = $mysqli->query($sql_product)->fetch_assoc()["img"];
		$price_cart = $row["price"] ?? NULL;
		$gram_cart = $row["gram"] ?? NULL;
		$ordered_cart = $row["ordered"] ?? NULL;
	array_push($data_product, [
		"id"=>$id_cart,
		"name"=>$name_cart,
		"price"=>$price_cart,
		"gram"=>$gram_cart,
		"img"=>$img_cart,
		"ordered"=>$ordered_cart
	]
	);
		// var_dump($data);
}
}


$show_products = true;
}

function delate_product () {
	global $mysqli;
	global $user_info;
	$sql_cart = "SELECT id FROM cart WHERE user_id = '".$user_info."'";
	$res = $mysqli->query($sql_cart);
	while ($row = $res->fetch_assoc()) {
		if ($_POST['post_id'] == $row["id"]) {
			$mysqli->query("DELETE FROM cart WHERE id = '".$_POST['post_id']."'");
		}
	}

}

if (isset($_POST['delete_product'])) {
	delate_product ();
	$previousurl = $_SERVER['HTTP_REFERER'];
	$new_url=explode("index.php",$previousurl);
	// echo $new_url[0];
	// header('Location: ' .$new_url[0].'koszyk.php');
	header('Location: ' .$previousurl);
}


// var_dump($_POST);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Rem Karablin</title>
	<script src="js/Rlibrary.js"></script>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="css/bootstrap-grid.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/style2.css">

	<script>
	const prices_record = {};
	</script>
</head>
<body>

<?php 
require_once "parts/header.php";


echo "<script>const post_id = {};</script>";
$cart_msg = "";
$email_PUP = "";
$comm_PUP = "";

if (isset($_COOKIE['cart_msg'])) {
echo "<style>.pop_up_product{display:block;}</style>";
$product_loc = unserialize($_COOKIE["product_loc_cart"], ["allowed_classes" => false]);

$img_PUP = $product_loc["img"];
$name_PUP = $product_loc["name"];
$price_PUP = $product_loc["price"];
$gram_PUP = $product_loc["gram"];
$email_PUP = $product_loc["email"];
$comm_PUP = $product_loc["comm"];

$cart_msg = $_COOKIE['cart_msg'];

echo "<script>post_id.id = '".$product_loc["id"]."';</script>";

setcookie('cart_msg', null, -1, '/');
setcookie('product_loc_cart', null, -1, '/');

}

// var_dump($product_loc);

?>



<div class="pop_up_product">
<form action="php/add_order.php" method="post">
<input type="hidden" name="post_id" class="order_id" value="">
<input type="hidden" name="product" >
	<div class="row-PUP">
		<h2 class="PUP-title">Zamówienie</h2>
		<button type="submit" class="close_PUP" name="PUP_but1" onclick="">X</button>
	</div>
	<hr class="hr-PUP">
	<div class="PUP-content">
		<img src="<?php echo $img_PUP; ?>" class="PUP_img order_img" alt=""> 
		<div class="pup_info">
			<div class="pup_name order_name"><?php echo $name_PUP; ?></div>
			<input class="pup_price pup_inp order_price" value="<?php echo $price_PUP; ?>" readonly><br>
			<input class="pup_gram pup_inp order_gram" value="<?php echo $gram_PUP; ?>" readonly>

		</div>
	</div>
	<div class="PUP_order_input">
		<p>Proszę podać e-mail *</p>
		<input type="text" class="order_inp1" name="email" value="<?php echo $email_PUP; ?>">
		<p>Komentarz do sprzedawcy </p>
		<textarea name="comment" value="<?php echo $comm_PUP; ?>"></textarea>
	</div>	
		<?php
	echo "<p style='color:#e22222; text-area:center;'>".$cart_msg."</p>";
	?>
	<div class="row-PUP row_cart">
		<button type="submit" name="order" onclick="insertId()" class="PUP-but2 PUP-but">Zamówić</button>
	</div>
</form>
</div>



<div class="container cont-koszyk">
	<div class="row align-items-center mb-4 offer">
		<div class="col-9">
			<h2 class="section-title">
				Koszyk
			</h2>
		</div>
		<div class="col-3 d-flex justify-content-end">
			<a href="#" class="more">Więcej</a>
		</div>
	</div>

<div class="row offers_slide d-flex">

<?php if ($show_products):
foreach ($data_product as $key => $value): 

echo "<script>
	prices_record['".$value["id"]."']={
		'img':'".$data_product[$key]["img"]."',
		'name':'".$data_product[$key]["name"]."',
		'p':'".$data_product[$key]["price"]."',
		'g':'".$data_product[$key]["gram"]."'
}
</script>";?>

<div class="col-3 product">
<form action="koszyk.php" method="post">
	<div class="row-PUP row_cart">
		<?php 
			if ($data_product[$key]["ordered"] != "1"):
		?>
		<button class="delete_product" name="delete_product">X</button>
	<?php endif; ?>
	</div>
	<div class="PUP-content">
		<img src="<?php echo $data_product[$key]["img"]; ?>" class="PUP_img koszyk_PUP_img" alt=""> 
		<div class="pup_info">
			<div class="pup_name"><?php echo $data_product[$key]["name"]; ?></div>
			<input class="pup_price pup_inp" value="<?php echo $data_product[$key]["price"]; ?>" readonly><br>
			<input class="pup_gram pup_inp" value="<?php echo $data_product[$key]["gram"]; ?>" readonly>

		</div>
	</div>
	<div class="row-PUP row_cart">
	<?php 
		if ($data_product[$key]["ordered"] != "1"):
	?>
		<button type="button" name="order" class="PUP-but2 PUP-but" value="<?php echo $data_product[$key]['id'] ?>" onclick="openPUP(this);">Zamówić</button>
	<?php else: ?>
		<button type="button" name="order" class="PUP-but2 PUP-but PUP-zamowione">Zamówione</button>
	<?php endif; ?>
	</div>
	<input type="hidden" name="post_id" value="<?=$data_product[$key]["id"]?>">
</form>
</div>

<?php endforeach; endif; ?>
</div>

</div>

<script src="js/koszyk.js"></script>

<!-- <script src="js/skrypt.js"></script> -->
<div class="footer_2">
<footer class="footer">
		<div class="container">
			<div class="row  footer-row1">

				<div class="col-9">
					<nav>
						<ul class="footer-menu d-flex">
							<li class="footer-menu-item"><a href="#" class="footer-menu-link">Sklep</a></li>
							<li class="footer-menu-item"><a href="#" class="footer-menu-link">O nas</a></li>
							<li class="footer-menu-item"><a href="#" class="footer-menu-link">Blog</a></li>
							<li class="footer-menu-item"><a href="#" class="footer-menu-link">Kontakt</a></li>
							<li class="footer-menu-item"><a href="#" class="footer-menu-link">Rodzaje dostawy</a></li>
						</ul>
					</nav>
				</div>
				<div class="col-3 d-flex align-items-center justify-content-end">
					<span class="footer-text">Follow Us</span>
					<span class="footer-social d-inline-flex align-items-center">
						<a href="#" class="social-link"><img src="img/Facebook.svg" alt=""></a>
						<a href="#" class="social-link"><img src="img/Twitter.svg" alt=""></a>
						<a href="#" class="social-link"><img src="img/Instagram.svg" alt=""></a>
					</span>
				</div>
			</div>
			<hr class="footer-line mt-4 mb-4">
			<div class="creator">
				<span class="footer-text">Stronę przygotował: Rem Karablin</span>
			</div>
		</div>
</footer>


<div class="source">
	<div class="container">
		<p class="source-title">Żródła:</p>
		1.<a href='https://www.freepik.com/vectors/logo'>Logo vector created by rawpixel.com - www.freepik.com</a>
		2.<a href='https://www.freepik.com/photos/background'>Background photo created by valeria_aksakova - www.freepik.com</a>
		3.<a href='https://www.freepik.com/vectors/food'>Food vector created by catalyststuff - www.freepik.com</a> <br>

		4.<a href='https://www.freepik.com/vectors/background'>Background vector created by starline - www.freepik.com</a>
		5.<a href='https://www.freepik.com/vectors/background'>Background vector created by starline - www.freepik.com</a>

		6.<a href='https://b2b.coffeedesk.pl/?gclid=CjwKCAiAm-2BBhANEiwAe7eyFKXq9Vt6vGTZDp0AzTpvBvu0FjnT_kOHefhDD1NwEyVpPpejmhjaJhoCpDgQAvD_BwE'>Text</a>

		7.<a href='https://www.manucafe.pl/'>Zdjecia kawy</a>

		8.<div>Icons made by <a href="https://www.flaticon.com/authors/kiranshastry" title="Kiranshastry">Kiranshastry</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
		<div>Icons made by <a href="https://www.flaticon.com/authors/becris" title="Becris">Becris</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
	</div>
</div>
</div>


<?php 
$mysqli->close();
?>

</body>
</html>