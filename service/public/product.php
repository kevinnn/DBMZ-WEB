<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/productController.php';
$url = explode("/", $_SERVER['PHP_SELF']);
$method = $url[count($url)-1];
switch($method) {
	case 'recommend' :
		echo productController::productIsRecommend(null);
		break;
}
?>