<?php
include __ROOT__.'/model/cityModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
Class cityController {
	public static function getAllByProvince() {
		if (isset($_GET['provinceId'])) {
			$provinceId = $_GET['provinceId'];
			return json_encode(new jsonBean(200,cityModel::getAllByProvince($provinceId),'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
}
?>