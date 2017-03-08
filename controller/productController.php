<?php
include __ROOT__.'/model/productModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
Class productController {
	public static function allProduct() {
		$arr = productModel::getAllProduct();
		return json_encode(new jsonBean(200,$arr,"获取成功"),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}
	public static function productByCategoryId($id) {
		$arr = productModel::getProductByCategoryId($id,0);
		$jsonBean = new jsonBean(200,$arr,"获取成功");
		return json_encode($jsonBean,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}

	public static function productIsHot($limit) {
		$arr = productModel::getProductByIsHot($limit);
		$jsonBean = new jsonBean(200,$arr,"获取成功");
		return json_encode($jsonBean,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}

	public static function productIsNew($limit) {
		$arr = productModel::getProductByIsNew($limit);
		$jsonBean = new jsonBean(200,$arr,"获取成功");
		return json_encode($jsonBean,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}

	public static function productIsRecommend($limit) {
		$limit = isset($_GET['limit']) ? $_GET['limit'] : $limit;
		if ($limit == null) {
			return json_encode(new jsonBean(403,$arr,"未传参数"),JSON_UNESCAPED_UNICODE);
		}
		$arr = productModel::getProductByIsRecommend($limit);
		return json_encode(new jsonBean(200,$arr,"获取成功"),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}

	public static function productById($id) {
		$arr = productModel::getProductById($id);
		if ($arr < 1) {
			return json_encode(new jsonBean(403,$arr,"查找不到"),JSON_UNESCAPED_UNICODE);
		}
		return json_encode(new jsonBean(200,$arr,"获取成功"),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}

	public static function addProduct() {
		$result = productModel::insertData($_POST,productModel::getTable());
		if ($result) {
			$data = productModel::getLastInsertId();
			$data = $data[0];
			$productId = $data['id'];
			if ($_POST['isOn'] == 1) {
				include __ROOT__.'/model/yungouModel.php';
				yungouModel::insertData(array('productId'=>$productId,'term'=>100000001), yungouModel::getTable());
			}
			$jsonBean = new jsonBean(200,$result,'添加成功');
		} else {
			$jsonBean = new jsonBean(403,$result,'添加失败');
		}
		return json_encode($jsonBean,JSON_UNESCAPED_UNICODE);
	}

	public static function productList() {
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$categoryId = isset($_GET['categoryId']) ? $_GET['categoryId'] : 0;
		$brandId = isset($_GET['brandId']) ? $_GET['brandId'] : 0;
		$result = productModel::getProductList($page,$categoryId,$brandId);
		return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE);
	}

	public static function productCount() {
		$categoryId = isset($_GET['categoryId']) ? $_GET['categoryId'] : 0;
		$brandId = isset($_GET['brandId']) ? $_GET['brandId'] : 0;
		$result = productModel::getProductCount($categoryId,$brandId);
		return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE);
	}

	public static function updateProduct() {
		$result = productModel::updateData($_POST['id'],$_POST,productModel::getTable());

		if ($_POST['isOn'] == 1) {
			include __ROOT__.'/model/yungouModel.php';
			$arr = yungouModel::getLastYungouByProduct($_POST['id']);
			if (count($arr) > 0) {
				$obj = $arr[0];
				if ($obj->status != 0) {
					$result &= yungouModel::insertData(array('productId'=>$_POST['id'],'term'=>$obj->term+1), yungouModel::getTable());
				}
			} else {
				$result &= yungouModel::insertData(array('productId'=>$_POST['id'],'term'=>100000001), yungouModel::getTable());
			}
		}
		return json_encode(new jsonBean(200,$result,'修改成功'),JSON_UNESCAPED_UNICODE);
	}
}
?>