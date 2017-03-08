<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/adminController.php';
$url = explode("/", $_SERVER['PHP_SELF']);
$method = $url[count($url)-1];
switch($method) {
	case 'login' :
		echo adminController::login();
		break;
	case 'logout' :
		echo adminController::logout();
		break;
	case 'isLogin' :
		echo adminController::isLogin();
		break;
	default :
		$check = json_decode(adminController::isLogin(),true);
		if ($check['code'] == 200) {
			switch ($method) {
				case 'adminList':
					echo adminController::adminList();
					break;
				case 'register':
					echo adminController::register();
					break;
				case 'adminDelete' :
					echo adminController::adminDelete();
					break;
				case 'adminUpdate' :
					echo adminController::adminUpdate();
					break;
			}
		}
	
}
?>