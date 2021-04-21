<?php 

if ($_POST["product"] !== NULL) {
	$p_id = $_POST["product"] ?? NULL;
	$p_img = $_POST["img"] ?? NULL;
	$p_name = $_POST["name"] ?? NULL;
	$p_price = $_POST["price"] ?? NULL;
	$p_gram = $_POST["gram"] ?? NULL;
	$p_info = [$p_id => ["img"=>$p_img, "name"=>$p_name,"price"=>$p_price,"gram"=>$p_gram]];
	$p_info2 = ["id" => $p_id,"img"=>$p_img, "name"=>$p_name,"price"=>$p_price,"gram"=>$p_gram];

	if (isset($_COOKIE["product"])) {
		$data = unserialize($_COOKIE["product"]);
		foreach ($data as $key => $value) {
			var_dump($data);
			if ($p_id != $key) {
				array_push($p_info,$data[$key]);
			}
			echo "<br>";
		}

	}
	// var_dump($p_info);
		
	setcookie("product", serialize($p_info), time()+3600,'/');
	setcookie("product_loc", serialize($p_info2), time()+3600,'/');
	setcookie("select_way","true", time()+3600,'/');

	// var_dump($_COOKIE);
	header('Location: ' . $_SERVER['HTTP_REFERER']);

	// 	echo '
// <script type="text/javascript">
// location.reload();
// </script>';
}
// var_dump($_COOKIE);

?>