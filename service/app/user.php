<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/userController.php';
include __ROOT__.'/controller/verifyController.php';
$url = explode("/", $_SERVER['PHP_SELF']);
$method = $url[count($url)-1];
switch($method) {
	case 'login' :
		echo userController::login();
		break;
	case 'logout' :
		echo userController::logout();
		break;
	case 'register' :
		echo userController::register();
		break;
	case 'isLogin' :
		echo userController::isLogin();
		break;
	case 'isUserExit' :
		echo userController::isUserExit();
		break;
	case 'sendVerificationCode' :
		echo verifyController::sendVerificationCode();
		break;
	case 'updateNickname' :
		$check = json_decode(userController::isLogin(),true);
		if ($check['code'] == 200 && count($check['data']) == 1) {
			$users = $check['data'];
			$user = $users[0];
			$userId = $user['id'];
			echo userController::updateNickname($userId);
		}else{
			echo json_encode(new jsonBean(403,array(),'未登录'),JSON_UNESCAPED_UNICODE);
		}
		break;
	case 'updatePassword' :
		$check = json_decode(userController::isLogin(),true);
		if ($check['code'] == 200 && count($check['data']) == 1) {
			$users = $check['data'];
			$user = $users[0];
			$userId = $user['id'];
			echo userController::updatePassword($userId);
		}else{
			echo json_encode(new jsonBean(403,array(),'未登录'),JSON_UNESCAPED_UNICODE);
		}
		break;
	case 'getHbRecords' :
		$check = json_decode(userController::isLogin(),true);
		if ($check['code'] == 200 && count($check['data']) == 1) {
			$users = $check['data'];
			$user = $users[0];
			$userId = $user['id'];
			echo userController::getHbRecords($userId);
		}else{
			echo json_encode(new jsonBean(403,array(),'未登录'),JSON_UNESCAPED_UNICODE);
		}
		break;
	case 'getUserByInviteCode' :
		$inviteCode = $_GET['inviteCode'];
		echo userController::getUserByInviteCode($inviteCode);
		break;
	case 'suggest' :
		$check = json_decode(userController::isLogin(),true);
		if ($check['code'] == 200 && count($check['data']) == 1) {
			$users = $check['data'];
			$user = $users[0];
			$userId = $user['id'];
			echo userController::suggest($userId);
		}else{
			echo json_encode(new jsonBean(403,array(),'未登录'),JSON_UNESCAPED_UNICODE);
		}
		break;
	case 'notShowHb' :
		$check = json_decode(userController::isLogin(),true);
		if ($check['code'] == 200 && count($check['data']) == 1) {
			$users = $check['data'];
			$user = $users[0];
			$userId = $user['id'];
			echo userController::notShowHb($userId);
		}else{
			echo json_encode(new jsonBean(403,array(),'未登录'),JSON_UNESCAPED_UNICODE);
		}
		break;
}

?>