<?php
include __ROOT__.'/model/bannerModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
Class bannerController {
	/*
	**获取轮播图片
	**parameter 数量
	*/
	public static function all () {
		$arr = bannerModel::getAllIsOn();
		$bannerArr = array();
		foreach ($arr as $key => $value) {
			array_push($bannerArr,array('imgUrl'=>$value->imgUrl,'url'=>$value->url));
		}
		return json_encode(new jsonBean(200,$bannerArr,"获取成功"),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}

	public static function getAll() {
		return json_encode(new jsonBean(200,bannerModel::getAllIsOn(),'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}



	/*后台接口*/
	public static function bannerList() {
		return json_encode(new jsonBean(200,bannerModel::getAll(),"获取成功"),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}
	public static function addBanner() {
		if (isset($_POST['imgUrl']) && isset($_POST['url']) && isset($_POST['isOn'])) {
			return json_encode(new jsonBean(200,bannerModel::insertData($_POST,bannerModel::getTable()),'插入成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function bannerEdit() {
		if (isset($_GET['id'])) {
			return json_encode(new jsonBean(200,bannerModel::getById($_GET['id']),'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function updateBanner() {
		if (isset($_POST['id']) && isset($_POST['url']) && isset($_POST['imgUrl']) && isset($_POST['isOn'])) {
			return json_encode(new jsonBean(200,bannerModel::updateData($_POST['id'],$_POST,bannerModel::getTable()),'修改成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function deleteBanner() {
		if (isset($_POST['id'])) {
			return json_encode(new jsonBean(200,bannerModel::deleteData($_POST['id'],bannerModel::getTable()),'删除成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
}
// echo bannerController::banner();
?>