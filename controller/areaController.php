<?php
include __ROOT__.'/model/areaModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
Class areaController {
	public static function getAllByCity() {
		if (isset($_GET['cityId'])) {
			$cityId = $_GET['cityId'];
			return json_encode(new jsonBean(200,areaModel::getAllByCity($cityId),'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
}
?>