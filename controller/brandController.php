<?php
include __ROOT__.'/model/brandModel.php';
include_once __ROOT__.'/bean/jsonBean.php';

Class brandController {
	public static function allBrand() {
		$arr = brandModel::getAllBrand();
		return json_encode(new jsonBean(200,$arr,'获取成功'),JSON_UNESCAPED_UNICODE);
	}

	/*后台接口*/
	public static function brandList() {
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$result = brandModel::getBrandList($page);
		return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE);
	}
	public static function brandCount() {
		$result = brandModel::getBrandCount();
		return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE);
	}
	public static function brandEdit() {
		$result = brandModel::getById($_GET['id']);
		return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE);
	}
	public static function updateBrand() {
		$id = $_POST['id'];
		$result = brandModel::updateData($id,$_POST,brandModel::getTable());
		return json_encode(new jsonBean(200,$result,'修改成功'),JSON_UNESCAPED_UNICODE);
	}
	public static function addBrand() {
		if (isset($_POST)) {
			$result = brandModel::insertData($_POST,brandModel::getTable());
			return json_encode(new jsonBean(200,$result,'添加成功'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function deleteBrand() {
		if (isset($_POST)) {
			$result = brandModel::deleteData($_POST['id'],brandModel::getTable());
			return json_encode(new jsonBean(200,$result,'删除成功'),JSON_UNESCAPED_UNICODE);
		}
	}
}
?>