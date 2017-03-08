<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/userController.php';
$check = json_decode(userController::isLogin(),true);
if ($check['code'] == 200 && count($check['data']) == 1) {
	include __ROOT__.'/controller/paymentController.php';
	$users = $check['data'];
	$user = $users[0];
	$cashierid = $_POST['cashierid'];
	echo paymentController::paySuccess($user,$cashierid);
} else {
	echo json_encode(new jsonBean(403,array(),'未登录'),JSON_UNESCAPED_UNICODE);
}
?>