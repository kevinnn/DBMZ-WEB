<?php
include __ROOT__.'/model/articleModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
Class articleController {
	/*后台接口*/
	public static function addArticle() {
		if (isset($_POST['category']) && isset($_POST['title']) && isset($_POST['keywords']) && isset($_POST['content'])) {
			return json_encode(new jsonBean(200,articleModel::insertData($_POST,articleModel::getTable()),'插入成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function articleList() {
		return json_encode(new jsonBean(200,articleModel::getArticleList(),'获取成功'),JSON_UNESCAPED_UNICODE);
	}
	public static function articleEdit() {
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			return json_encode(new jsonBean(200,articleModel::getArticleById($id),'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function updateArticle() {
		if (isset($_POST['id']) && isset($_POST['category']) && isset($_POST['title']) && isset($_POST['keywords']) && isset($_POST['content'])) {
			return json_encode(new jsonBean(200,articleModel::updateData($_POST['id'],$_POST,articleModel::getTable()),'修改成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function deleteArticle() {
		if (isset($_POST['id'])) {
			return json_encode(new jsonBean(200,articleModel::deleteData($_POST['id'],articleModel::getTable()),'删除成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
}
?>