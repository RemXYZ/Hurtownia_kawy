<?php

	// $previousurl = $_SERVER['HTTP_REFERER'];
	// $new_url=explode("index.php",$previousurl);
	// echo $new_url[0];
	// echo "hello";

if (isset($_COOKIE["sql_info"])) {
require_once "parts/bd.php";

$sql = "SELECT * FROM products ORDER BY id ASC";
$res = $mysqli->query($sql);

$value = [];

while ($row = $res->fetch_assoc()) {
$value = [
	"id"=>$row["id"],
	"name" => $row["name"],
	"price" => $row["price"],
	"gram" => $row["gram"],
	"img"  => $row["img"]
];


echo '<div class="col-3">
		<form action="php/add_product_sql.php" method="post">
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

}

?>