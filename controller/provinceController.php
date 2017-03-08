<?php
include __ROOT__.'/model/provinceModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
Class provinceController {
	public static function getAll() {
		return json_encode(new jsonBean(200,provinceModel::getAll(),'获取成功'),JSON_UNESCAPED_UNICODE);
	}
}
?>