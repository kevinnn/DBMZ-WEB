<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/yungouController.php';
include __ROOT__.'/controller/productController.php';
$url = explode("/", $_SERVER['PHP_SELF']);
$method = $url[count($url)-1];
switch($method) {
	case 'getAll' :
		echo yungouController::getAllNotEnd();
		break;
	case 'getCategory':
		echo yungouController::getAllByCategory();
		break;
	case "fastStart" :
		echo yungouController::fastStart();
		break;

	case "fastNotStart" :
		echo yungouController::fastNotStart();
		break;

	case 'getHistory' :
		echo yungouController::getHistory();
		break;

	case 'getHistoryCount' :
		echo yungouController::getHistoryCount();
		break;

	case 'getHistoryMobile' :
		echo yungouController::getHistoryMobile();
		break;

	case 'getWin':
		echo yungouController::getwin(null);
		break;

	case 'compute':
		echo yungouController::compute();
		break;

	case 'getResult':
		echo yungouController::getResult();
		break;

	case 'getByProductAndTerm':
		echo yungouController::yungouDetailByTerm();
		break;

	case 'getByYungou':
		echo yungouController::yungouDetailByYungou();
		break;
	case 'getTenZone':
		echo yungouController::getTenZone();
		break;

	case 'getCountTenZone':
		echo yungouController::getCountTenZone();
		break;

	case 'getLatestYungou':
		echo yungouController::getLatestYungou();
		break;

	case 'getLatestByProduct':
		echo yungouController::yungouProductId();
		break;
}
?>