<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/categoryController.php';
$url = explode("/", $_SERVER['PHP_SELF']);
$method = $url[count($url)-1];
switch($method) {
	case 'getAll' :
		echo categoryController::allCategory();
		break;
	case 'getById':
		echo categoryController::category($_GET['id']);
		break;
}
?>