<?php
include '../../config/config.php';
include __ROOT__.'/controller/yungouController.php';
if (isset($_GET['pid'])) {
	$productId = $_GET['pid'];
	$json = yungouController::yungouProductId($productId);
	$arr = json_decode($json,true);
	if ($arr['code'] == 200) {
		$data = $arr['data'];
		if (count($data) > 0) {
			$yungou = $data[0];
			$term = $yungou['term'];
			header("location:/detail/".$productId."-".$term.".html");
		} else {
			header("location:/");
		}
	} else {
		header("location:/");
	}
} else {
	header("location:/");
}
?>