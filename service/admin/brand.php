<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/adminController.php';
$check = json_decode(adminController::isLogin(),true);
if ($check['code'] == 200) {
	include __ROOT__.'/controller/brandController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);
	$method = $url[count($url)-1];
	switch($method) {
		case 'getAllBrand' :
			echo brandController::allBrand();
			break;
		case 'brandList' : 
			echo brandController::brandList();
			break;
		case 'brandCount' :
			echo brandController::brandCount();
			break;
		case 'brandEdit' :
			echo brandController::brandEdit();
			break;
		case 'updateBrand' :
			echo brandController::updateBrand();
			break;
		case 'addBrand' :
			echo brandController::addBrand();
			break;
		case 'deleteBrand' :
			echo brandController::deleteBrand();
			break;
	}
}
?>