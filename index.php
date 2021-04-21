<?php
require_once "parts/goods.php";

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

if (isset($_POST['clear_cookies'])) {
	if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
}

// if ($_POST["product"] !== NULL) {
// 	$p_id = $_POST["product"] ?? NULL;
// 	$p_name = $_POST["name"] ?? NULL;
// 	$p_price = $_POST["price"] ?? NULL;
// 	$p_gram = $_POST["gram"] ?? NULL;
// 	$p_info = [$p_id ,$p_name,$p_price,$p_gram];
// 	setcookie("product", serialize($p_info), time()+3600);
// 	setcookie("select_way","true");
// 	var_dump($_COOKIE);
// 	// header('Location: ' . $_SERVER['HTTP_REFERER']);
// 	// 	echo '
// // <script type="text/javascript">
// // location.reload();
// // </script>';
// }

if (isset($_COOKIE["product_loc"])) {
	$data2 = unserialize($_COOKIE["product_loc"], ["allowed_classes" => false]);
	$img_PUP = $data2["img"] ?? NULL;
	$name_PUP = $data2["name"] ?? NULL;
	$price_PUP = $data2["price"] ?? NULL;
	$gram_PUP = $data2["gram"] ?? NULL;
}

// var_dump(isset($_COOKIE["product"]));

// foreach ($_SERVER as $key => $value) {
// 	echo $key." = ".$value."<br>";
// }



// var_dump($_COOKIE);


// var_dump($data);
// var_dump($_COOKIE);

if (isset($_POST['close_PUH'])) {
	setcookie("close_PUH","true", time()+3600,'/');
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}

if (isset($_POST['tryb_Online'])) {
	setcookie("close_PUH","online", time()+3600,'/');
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}

$error = "";


// CESC Z TWORZENIEM BAZY DANYCH

if (isset($_POST['turn_on_MySQL'])) {
	$servername = $_POST['name_MySQL'];
	$login = $_POST['loginSQL'];
	$pass = $_POST['passSQL'];

	$sql_info = [$servername,$login,$pass];
	setcookie("sql_info", serialize($sql_info), time()+3600,'/');

	$mysqli = new mysqli($servername,$login,$pass);
	if ($mysqli->connect_error) {
		$error = "Połączenie nie powiodło się: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}else {
		if (!$mysqli->query("DROP DATABASE IF EXISTS rem_karablin") ||
		    !$mysqli->query("CREATE DATABASE rem_karablin")) {
		    $error = "Nie udało się stworzyć bazę danych: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		if (!$mysqli->select_db("rem_karablin")) {
			$error = "Nie udało się połączyć z bazą danych: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		$sql_products = "CREATE TABLE products (
				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				name varchar(32) NOT NULL,
				price varchar(32) NOT NULL,
				gram varchar(32) NOT NULL,
				img varchar(64) NOT NULL,
				PRIMARY KEY (id)
				)ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$sql_cart = "CREATE TABLE cart(
				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				user_id varchar(10) NOT NULL,
				product_id varchar(10) NOT NULL,
				price varchar(16) NOT NULL,
				gram varchar(16) NOT NULL,
				ordered varchar(4),
				PRIMARY KEY (id)
				)ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$sql_orders = "CREATE TABLE orders(
				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				user_id varchar(10) NOT NULL,
				product_id varchar(10) NOT NULL,
				price varchar(16) NOT NULL,
				gram varchar(16) NOT NULL,
				email varchar(32) NOT NULL,
				comment varchar(64) NOT NULL,
				data date,
				PRIMARY KEY (id)
				)ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$sql_user = "CREATE TABLE user(
				id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				adres varchar(32) NOT NULL,
				PRIMARY KEY (id)
				)ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		if (!$mysqli->query($sql_products) ||
			!$mysqli->query($sql_cart) ||
			!$mysqli->query($sql_user) || 
			!$mysqli->query($sql_orders)
			) {
			$error = "Nie udało się stworzyć tabelę: (" . $mysqli->errno . ") " . $mysqli->error;
		}

	foreach ($post_info as $key => $value) {
			$sql = "INSERT INTO products (name,price,gram,img) VALUES (
			'".$value['name']."',
			'".$value['price']."',
			'".$value['gram']."',
			'".$value['img']."'
			)";

		if (!$mysqli->query($sql)){
			$error = "Nie udało się wkleić dane do tabeli: (" . $mysqli->errno . ") " . $mysqli->error;
		}
	}

		$sql2 = $sql = "INSERT INTO products (name,price,gram,img) VALUES ('Kawa BIO','105','1000','img/coffee-img/new/BIO.jpg')";
		if (!$mysqli->query($sql2)){
			$error = "Nie udało się wkleić dane do tabeli: (" . $mysqli->errno . ") " . $mysqli->error;
		}

		if (!$mysqli->query("INSERT INTO user (adres) VALUES ('".$_SERVER['REMOTE_ADDR']."')")){
			$error = "Nie udało się wkleić dane do tabeli: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		

		if ($error == "") {
			setcookie('close_PUH', null, -1, '/');
			setcookie("close_PUH","Mode_online", time()+3600,'/');
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}

		$mysqli->close();
	}
}

// var_dump($_COOKIE);

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
	<script>
		const prices_record = {};
	</script>
</head>
<body>

<?php require_once "parts/header.php"; ?>

<!-- POP UP POWITALNY -->

<?php if ($_COOKIE['close_PUH'] == "true") {
	echo "<style>
		.pop_up_hello{display: none;}
	</style>";
}else {
	echo "<style>
		.pop_up_hello{display: block;}
	</style>";
} 

if ($_COOKIE['close_PUH']=="online") {
	echo "<style>
		.PUPstage1{display: none;}
		.PUPstage2{display: block;}
	</style>";
}
if ($_COOKIE['close_PUH'] == "Mode_online") {
	echo "<style>
		.pop_up_hello{display: none;}
	</style>";
}


?>

<div class="pop_up_hello">
<div class="blackscreen"></div>

<form action="index.php" method="post">
	<div class="row-PUP">
		<h2 class="PUH-title">Rem Karablin</h2>
		<button type="submit" class="close_PUP" name="close_PUH" onclick="">X</button>
	</div>
	<hr class="hr-PUP">
<div class="PUPstage1">
	<p class="PUH-title-text">Dzień dobry !</p>
	<p class="PUH-text_main">
		Wersja 2.0<br>
		Witryna używa plików cookie.<br> Wszystkie pliki cookie można usunąć za pomocą ikony, <img class="PUH_img_text" src="img/icon/trash_white.png" alt="">;<br>
		<br>Tryby:<br>w trybie offline maksymalnie można dodać 2 produkty,<br> w trybie online nie ma ograniczeń.<br>
		tryb online działa za pomocą MySQL i jest lepszy niż offline,<br> tryb offline jest gorszą wersją, ponieważ chciałem zrobić stronę szybko,<br> dlatego  korzystałem z cookie. Robiąc tą stronę internetową,<br> spędziłem około 21 godzin, i miałem dobrą okazję<br> wykorzystać wszystkie swoję umiętności takie jak:<br> Design strony internetowej, HTML, CSS, Bootstrap, JS, PHP i MySQL.<br>
		W wersji 2.0 było dodane:<br>
		1.Obsługa MySQL,<br>
		2.Możliwość zamówienia i usuwania towaru z koszyku,<br>
		3.Nowy towar "kawa BIO",<br>
		4.Bezpieczeństwo, ponieważ za pomocą MySQL można sprawdzić<br> więcej warunków i rówineż dodałem bezpieczeństwo ze strony JS,<br>
		5.I dużo różnych okienek typu pop-up !

	</p>
		<div class="row-PUH">
			<button type="submit" class="PUH-but PUH-butCK" name="close_PUH">Tryb offline (cookie)</button>
			<button type="submit" class="PUH-but" name="tryb_Online">Tryb online (MySQL)</button>
		</div>
</div>

<div class="PUPstage2">

	<p class="PUH-title-text">Tryb online</p>
	<p class="PUH-text_main">Aby korzystać z MySQL, niezbędne jest połączenie z bazą danych, <br>następnie program <u>usunie</u> i za tym stworzy nową bazę danych "rem_karablin",<br> w tej bazie utworzą się tabeli "products", "cart", "users","orders"</p><br>
	<p class="PUH-text">Proszę podać login, hasło i nazwę od phpmyadmin</p>

	<div class="PUH-inp-text">
		<p class="PUH-text">Nazwa serwera: (domyślnie jest "localhost"): </p>
		<input type="text" value="localhost" name="name_MySQL">
	</div>
	<div class="PUH-inp-text">
		<p class="PUH-text">Login (domyślnie jest "root"): </p>
		<input type="text" value="root" name="loginSQL">
	</div>
	<div class="PUH-inp-text">
		<p class="PUH-text">Hasło (domyślnie jest "" (puste)): </p>
		<input type="text" value="" name="passSQL"><br>
	</div>

<?php
	echo "<p style='color:#e22222;'>".$error."</p>";
?>

		<div class="row-PUH">
			<button type="submit" onclick="waitpls(this)" class="PUH-but" name="turn_on_MySQL">Ok</button>
		</div>
		<span class="waitpls" style="color:white;"></span>

</div>

</form>
</div>
</div>

<script>
	function waitpls(el) {
		getEl('.waitpls').innerHTML = "Proszę poczekać..."
	}
</script>

<!-- POP UP DLA PRODUKTOW -->

<?php if ($_COOKIE['select_way']): ?>
	<style>
		.pop_up_product{display: block;}
	</style>
<?php endif; ?>

<div class="pop_up_product">
<form action="parts/select_way.php" method="post">
<input type="hidden" name="product" >
	<div class="row-PUP">
		<h2 class="PUP-title">Dodałeś przedmiot do koszyka</h2>
		<button type="submit" class="close_PUP" name="PUP_but1" onclick="">X</button>
	</div>
	<hr class="hr-PUP">
	<div class="PUP-content">
		<img src="<?php echo $img_PUP; ?>" class="PUP_img" alt=""> 
		<div class="pup_info">
			<div class="pup_name"><?php echo $name_PUP; ?></div>
			<input class="pup_price pup_inp" value="<?php echo $price_PUP; ?>" readonly><br>
			<input class="pup_gram pup_inp" value="<?php echo $gram_PUP; ?>" readonly>

		</div>
	</div>
	<div class="row-PUP">
		<button type="submit" name="PUP_but1" class="PUP-but1 PUP-but" onclick="">Kupuj dalej</button>
		<button type="submit" name="PUP_but2" class="PUP-but2 PUP-but">Idź do koszyka</button>
	</div>
</form>
</div>




<!-- BANNER -->

<div class="banner-content">

	<div class="container bnn-content">
			<div class="row">
				<div class="bnn-text">
					<h2 class="bnn-title">Hurtownia kawy i herbaty</h2>
					<p class="bnn-description">
						Zamawiaj wszystkie produkty w jednym miejscu i w najlepszej cenie!
					</p>
				</div>
			</div>
	</div>


	<div class="dark-film">
	</div>
	<img class="img-1" src="img/coffee-beans-with-props-making-coffee.jpg" alt="">
	<div class="triangle-up1"></div>
	<div class="triangle-up2"></div>
</div>

<div class="container">
	<div class="row align-items-center mb-4 offer">
		<div class="col-9">
			<h2 class="section-title">
				Oferta
			</h2>
		</div>
		<div class="col-3 d-flex justify-content-end">
			<a href="#" class="more">Więcej</a>
		</div>
	</div>

<div class="row offers_slide d-flex">
<?php 
	if ($_COOKIE['close_PUH'] != "Mode_online") {
		foreach ($post_info as $key => $value) {
			echo '<div class="col-3">
		<form action="parts/add_product.php" method="post">
		<input type="hidden" name="product" value="'.$value["id"].'">
			<div class="goods-card">
			<div class="goods-card-main">
				<img src="'.$value["img"].'" alt="" class="goods-image">
				<h3 class="goods-title">'.$value["name"].'</h3>
				<!-- <p class="goods-description">Bajka dla smakoszy! Ta kawa plantacyjna z południowego</p> -->
				<p class="goods-text-weight">Podaj wagę w gramach</p>
				<input type="number" class="goods_weight '.$value["id"].'">
				<button type="button" onclick="submit_f(this)" class="goods-but" name="product_first" value="'.$value["id"].'">Do koszyka</button>
			</div>
				<span class="goods_price"><span>'.$value["price"].'zł</span><sub>/'.$value["gram"].'g</sub></span>
			</div>
			<input type="hidden" name="img" class="img_inp_n" value="'.$value["img"].'">
			<input type="hidden" name="name" class="name_inp_n" value="'.$value["name"].'">
			<input type="hidden" name="price" class="price_inp_n" value="'.$value["price"].'">
			<input type="hidden" name="gram" class="gram_inp_n" value="'.$value["gram"].'">
		</form>
		</div>';
		echo "<script>
		prices_record['".$value["id"]."']={
			'img':'".$value["img"]."',
			'name':'".$value["name"]."',
			'p':'".$value["price"]."',
			'g':'".$value["gram"]."'
		}
		</script>";
		}
	}else {
		require_once "php/select_products.php";
	}
		// echo $post2;
		// echo $post3;
		// echo $post4;
?>
	</div>

</div>

<script src="js/skrypt.js"></script>

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


<?php 
$mysqli->close();
?>

</body>
</html>