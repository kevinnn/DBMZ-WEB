<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/adminController.php';
$check = json_decode(adminController::isLogin(),true);
if ($check['code'] == 200) {
	include __ROOT__.'/controller/productController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);
	$method = $url[count($url)-1];
	switch($method) {
		case 'getProduct' :
			$id = $_GET['id'];
			echo productController::productById($id);
			break;
		case 'addProduct' :
			echo productController::addProduct();
			break;
		case 'productList' :
			echo productController::productList();
			break;
		case 'productCount' :
			echo productController::productCount();
			break;
		case 'updateProduct' :
			echo productController::updateProduct();
			break;
	}
}
?>