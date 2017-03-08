<?php
	include __ROOT__.'/model/shoppingCartModel.php';
	include_once __ROOT__.'/bean/jsonBean.php';
	include_once __ROOT__.'/JWT/JWT.php';
	use \Firebase\JWT\JWT;
	Class shoppingCartController {
		public static function getList($userId) {
			$arr = shoppingCartModel::getListByUserId($userId);
			$result = array();
			foreach ($arr as $key => $value) {
				if ($value['saleCount'] == $value['price']) {
					$result = shoppingCartModel::deleteData($value['shoppingCartId'],shoppingCartModel::getTable());
				}
				if ($value['amount'] > $value['price']-$value['saleCount']) {
					$value['amount'] = $value['price']-$value['saleCount'];
				}
				$productObj = array(
					'title'=>$value['title'],
					'singlePrice'=>intval($value['singlePrice']),
					'thumbnailUrl'=>$value['thumbnailUrl']
					);
				array_push($result, array('shoppingCartId'=>$value['shoppingCartId'],'amount'=>intval($value['amount']),'product'=>$productObj,'yungou'=>intval($value['yungouId'])));
			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		}
		public static function add($userId) {
			if(isset($_POST['yungouId']) && isset($_POST['amount'])) {
				$yungouId = $_POST['yungouId'];
				include __ROOT__.'/model/yungouModel.php';
				$arr = yungouModel::getLastYungouByYungou($yungouId);
				if (count($arr) == 0) {
					return json_encode(new jsonBean(405,array(),'云购已下架'),JSON_UNESCAPED_UNICODE);
				}
				$arr = $arr[0];
				$yungouId = $arr->id;
				$amount = $_POST['amount'];
				include __ROOT__.'/model/productModel.php';
				$arr = productModel::getProductById($arr->productId);
				$product = $arr[0];

				$arr = shoppingCartModel::getshoppingCart($userId,$yungouId);
				if (count($arr) > 0) {
					$shoppingCart = $arr[0];
					$amount = $shoppingCart->amount + $amount;
					if ($amount < 1) {
						$_POST['id'] = $shoppingCart->id;
						return self::remove();
					}
					if ($amount > $product->price) {
						$amount = $product->price;
					}
					$result = shoppingCartModel::updateData($shoppingCart->id,array('amount'=>$amount),shoppingCartModel::getTable());
					return json_encode(new jsonBean(200,$result,'插入成功1'),JSON_UNESCAPED_UNICODE);
				} else{
					if ($amount < 1) {
						$amount = 1;
					}
					if ($amount > $product->price) {
						$amount = $product->price;
					}
					$result = shoppingCartModel::insertData(array('userId'=>$userId,'yungouId'=>$yungouId,'amount'=>$amount),shoppingCartModel::getTable());
					return json_encode(new jsonBean(200,$result,'插入成功2'),JSON_UNESCAPED_UNICODE);
				}
			} else {
				return json_encode(new jsonBean(405,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
			}
		}
		public static function remove() {
			if (isset($_POST['id'])) {
				$shoppingCartId = $_POST['id'];
				$result = shoppingCartModel::deleteData($shoppingCartId,shoppingCartModel::getTable());
				return json_encode(new jsonBean(200,$result,'删除成功'),JSON_UNESCAPED_UNICODE);
			} else {
				return json_encode(new jsonBean(405,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
			}
		}
		public static function removeByYungou($userId) {
			if (isset($_POST['yungouId'])) {
				$yungouId = $_POST['yungouId'];
				shoppingCartModel::deleteByYungou($userId,$yungouId);
			}
		}
		public static function removeLot() {
			if (isset($_POST['ids'])) {
				$shoppingCartIds = $_POST['ids'];
				$result = true;
				if (count($shoppingCartIds) > 0) {
					$result &= shoppingCartModel::deleteLot($shoppingCartIds);
				}
				return json_encode(new jsonBean($result ? 200 : 403,$result,$result ? '删除成功' : '删除失败'),JSON_UNESCAPED_UNICODE);
			} else {
				return json_encode(new jsonBean(405,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
			}
		}
		public static function intoCart ($userId) {
			$arr = shoppingCartModel::getIntoCartByUserId($userId);
			$result = array();
			foreach ($arr as $key => $value) {
				if ($value['price'] == $value['saleCount']) {
					shoppingCartModel::deleteData($value['shoppingCartId'],shoppingCartModel::getTable());
				} else {
					$obj = array(
						'title'=>$value['title'],
						'price'=>intval($value['price']),
						'amount'=>intval(($value['amount']>($value['price']-$value['saleCount']) ? ($value['price']-$value['saleCount']) : $value['amount'])),
						'productId'=>intval($value['productId']),
						'yungouId'=>intval($value['yungouId']),
						'shoppingCartId'=>intval($value['shoppingCartId']),
						'singlePrice'=>intval($value['singlePrice']),
						'thumbnailUrl'=>$value['thumbnailUrl'],
						'term'=>intval($value['term']),
						'saleCount'=>intval($value['saleCount'])
						);
					array_push($result,$obj);
				}
			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		}
	}
?>