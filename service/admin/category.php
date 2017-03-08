<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/adminController.php';
$check = json_decode(adminController::isLogin(),true);
if ($check['code'] == 200) {
	include __ROOT__.'/controller/categoryController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);
	$method = $url[count($url)-1];
	switch($method) {
		case 'categoryList' :
			echo categoryController::allCategory();
			break;
		case 'categoryEdit' :
			echo categoryController::categoryEdit();
			break;
		case 'updateCategory' :
			echo categoryController::updateCategory();
			break;
		case 'addCategory' :
			echo categoryController::addCategory();
			break;
		case 'deleteCategory' :
			echo categoryController::deleteCategory();
			break;
	}
}
?>