<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/userController.php';
$check = json_decode(userController::isLogin(),true);
if ($check['code'] == 200 && count($check['data']) == 1) {
	include __ROOT__.'/controller/uploaderController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);

	$users = $check['data'];
	$user = $users[0];
	$userId = $user['id'];

	$head = $url[count($url)-1];

	date_default_timezone_set('PRC');
	$t = explode('.', microtime(true));
	$tail = date('Y-m-d_H:i:s').':'.$t[1];

	$name = $head.'_'.$userId.'_'.$tail;

	$val = uploaderController::uploader($name);
	$data = json_decode($val,true);
	if ($data['code'] == 200) {
		if ($head == "user_avatorUrl") {
			unset($_POST);
			$_POST['avatorUrl'] = $data['data'];
			userController::modify($userId);
		}
		echo $val;
	} else {
		echo $val;
	}
}
?>