<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/adminController.php';
$check = json_decode(adminController::isLogin(),true);
if ($check['code'] == 200) {
	include __ROOT__.'/controller/yungouController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);
	$method = $url[count($url)-1];
	switch($method) {
		case 'yungouList' : 
			echo yungouController::yungouList();
			break;

		case 'yungouCount' :
			echo yungouController::yungouCount();
			break;
		
		case 'yungouDetail' :
			echo yungouController::yungouDetail();
			break;
	}
}
?>