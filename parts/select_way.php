<?php 

if (isset($_POST['PUP_but1'])) {
	// unset($_COOKIE["select_way"]);
	setcookie('select_way', null, -1, '/');
	setcookie('product_loc', null, -1, '/');
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}

if (isset($_POST['PUP_but2'])) {
	setcookie('select_way', null, -1, '/');
	setcookie('product_loc', null, -1, '/');
	$previousurl = $_SERVER['HTTP_REFERER'];
	$new_url=explode("index.php",$previousurl);
	// echo $new_url[0];
	header('Location: ' .$new_url[0].'koszyk.php');
}



// if (isset($_POST["product"])) {
// 	$p_id = $_POST["product"] ?? NULL;
// 	$p_name = $_POST["name"] ?? NULL;
// 	$p_price = $_POST["price"] ?? NULL;
// 	$p_gram = $_POST["gram"] ?? NULL;
// 	$p_info = [$p_id ,$p_name,$p_price,$p_gram];
// 	setcookie($p_id, serialize($p_info), time()+3600);
// 	setcookie("id","hah");
// 	// $data = unserialize($_COOKIE[$p_id], ["allowed_classes" => false]);
// 	header('Location: ' . $_SERVER['HTTP_REFERER']);
// 	// header('Location:/index.php');

// // 	echo '
// // <script type="text/javascript">
// // location.reload();
// // </script>';
	
// }
?>