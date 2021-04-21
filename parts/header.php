<?php 
$qt_pr = "";
if (isset($_COOKIE["product"])) {
	$data = unserialize($_COOKIE["product"], ["allowed_classes" => false]);
	$qt_pr = "(".count($data).")" ?? NULL;
}
if ($_COOKIE['close_PUH'] == "Mode_online") {
	require_once "parts/bd.php";
	$sql_user_Header = "SELECT * FROM user WHERE adres = '".$_SERVER['REMOTE_ADDR']."'";
	$user_info_Header = $mysqli->query($sql_user_Header)->fetch_assoc()["id"];
	$sql_cart_Header = "SELECT COUNT(id) as quantity FROM cart WHERE user_id = '".$user_info_Header."'";
	$qt = $mysqli->query($sql_cart_Header)->fetch_assoc()['quantity'];
	if ($qt > 0) {
		$qt_pr = "(".$qt.")";
	}else {
		$qt_pr = "";
	}
}

?>

<header class="header">
<div class="container">
	<div class="row d-flex justify-content-between align-items-center">
		<div class="col-2">
			<div class="logo">
				<a href="index.php"><img src="img/3594497/coffeeLOGO3.png" alt="LOGO"></a>
			</div>
		</div>
		<div class="col-6">
			<nav>
				<ul class="navigation d-flex justify-content-around">
					<li class="navigation-item"><a href="index.php" class="navigation-link">Strona główna</a></li>
					<li class="navigation-item"><a href="#" class="navigation-link">Oferta</a></li>
					<li class="navigation-item"><a href="#" class="navigation-link">Blog o kawie</a></li>
					<li class="navigation-item"><a href="#" class="navigation-link">Odwiedż także</a></li>
				</ul>
			</nav>
		</div>
		<div class="col-2 d-flex justify-content-end">
		<a href="koszyk.php" class="button-link">
			<button class="button" value="hello">
				<img class="button-icon" src="img/icon/cart.svg" alt="icon:cart">
				<span class="button-text">Koszyk <?php echo $qt_pr ?></span>
			</button>
		</a>
		<form action="index.php" method="post" class="clear_cookie">
			<button type="submit" name="clear_cookies"><img src="img/icon/trash_white.png" alt=""></button>
			<?php if ($_COOKIE['close_PUH'] == "Mode_online"): ?>
			<button type="submit" class="online" title="You are online" name="online"></button>
		<?php endif; ?>
		</form>
		</div>
	</div>
</div>
</header>