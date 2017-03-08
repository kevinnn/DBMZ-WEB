<?php
include __ROOT__.'/model/categoryModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
Class categoryController {
	/*
	**获取所有分类
	*/
	public static function allCategory() {
		$arr = categoryModel::getAll();
		$jsonBean = new jsonBean(200,$arr,"获取成功");
		return json_encode($jsonBean,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}
	/*
	**获取某个分类
	**parameter 分类id
	*/
	public static function category($id) {
		$arr = categoryModel::getById($id);
		$jsonBean = new jsonBean(200,$arr[0],"获取成功");
		return json_encode($jsonBean,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}
	/*
	**获取要显示在首页的分类
	*/
	public static function categoryIsOnIndex() {
		$arr = categoryModel::getByIsOnIndex();
		$jsonBean = new jsonBean(200,$arr,"获取成功");
		return json_encode($jsonBean,JSON_UNESCAPED_UNICODE);
	}

	/*后台接口*/
	public static function categoryEdit() {
		$result = array();
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$arr = categoryModel::getById($id);
			if (count($arr) > 0) {
				$result = $arr[0];
			} else {
				return json_encode(new jsonBean(403,$result,'参数错误'));
			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,$result,'参数错误'));
		}
	}
	public static function updateCategory() {
		$result = categoryModel::updateData($_POST['id'],$_POST,categoryModel::getTable());
		return json_encode(new jsonBean(200,$result,'修改成功'),JSON_UNESCAPED_UNICODE);
	}
	public static function addCategory() {
		$result = categoryModel::insertData($_POST,categoryModel::getTable());
		return json_encode(new jsonBean(200,$result,'插入成功'),JSON_UNESCAPED_UNICODE);
	}
	public static function deleteCategory() {
		$result = categoryModel::deleteData($_POST['id'],categoryModel::getTable());
		return json_encode(new jsonBean(200,$result,'删除成功'),JSON_UNESCAPED_UNICODE);
	}
}
?>