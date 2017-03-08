<?php
include __ROOT__.'/model/winOrderModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
Class winOrderController {
	public static function getWinOrderById($userId) {
		if (isset($_GET['winOrderId'])) {
			$winOrderId = $_GET['winOrderId'];
			$arr = winOrderModel::getWinOrderById($winOrderId,$userId);
			$result = array();
			if (count($arr) > 0) {
				$obj = $arr[0];
				foreach ($obj as $key => $value) {
					if (!is_numeric($key)) {
						$result = array_merge($result,array($key=>$value));
					}
				}
				return $result;
			} else {
				header('location:/');
			}
		} else {
			header('location:/');
		}
	}

	public static function getWinOrderByIdMobile($userId) {
		if (isset($_GET['winOrderId'])) {
			$winOrderId = $_GET['winOrderId'];
			$arr = winOrderModel::getWinOrderById($winOrderId,$userId);
			$result = array();
			$winOrder = array();
			if (count($arr) > 0) {
				$obj = $arr[0];
				foreach ($obj as $key => $value) {
					if (!is_numeric($key)) {
						$winOrder = array_merge($winOrder,array($key=>$value));
					}
				}
				include_once __ROOT__.'/model/orderModel.php';
				$sum = orderModel::getOrderCountByUserAndYungou($userId,$winOrder['yungouId']);
				$winOrder = array_merge($winOrder,array('count'=>$sum));
				$result = array_merge($result, array('winOrder'=>$winOrder));
				if ($winOrder['logisticsStatus'] > 0) {
					include_once __ROOT__.'/model/addressModel.php';
					$arr = addressModel::getById($userId,$winOrder['addressId']);
					$address = array();
					if (count($arr) > 0) {
						$obj = $arr[0];
						foreach ($obj as $key => $value) {
							if (!is_numeric($key)) {
								$address = array_merge($address,array($key=>$value));
							}
						}
					}
					$result = array_merge($result,array('address'=>$address));
				}
				return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			} else {
				return json_encode(new jsonBean(404,$result,'查找不到此记录'),JSON_UNESCAPED_UNICODE);
			}
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function getWinOrderAddress($userId) {
		if (isset($_GET['winOrderId'])) {
			$winOrderId = $_GET['winOrderId'];
			$arr = winOrderModel::getWinOrderForAddress($winOrderId,$userId);
			$result = array();
			if (count($arr) > 0) {
				$obj = $arr[0];
				foreach ($obj as $key => $value) {
					if (!is_numeric($key)) {
						$result = array_merge($result,array($key=>$value));
					}
				}
				return $result;
			} else {
				header('location:/');
			}
		} else {
			header('location:/');
		}
	}
	//用户确认收货
	public static function confirmAddress($userId) {
		if (isset($_GET['addressId']) && isset($_GET['winOrderId'])) {
			$winOrderId = $_GET['winOrderId'];
			$addressId = $_GET['addressId'];
			if( winOrderModel::confirmAddress($userId,$winOrderId,$addressId)){
				return json_encode(new jsonBean(200,array(),'确认收货地址成功'),JSON_UNESCAPED_UNICODE);
			}else{
				return json_encode(new jsonBean(407,array(),'确认收货地址失败'),JSON_UNESCAPED_UNICODE);
			}
			
		}else{
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function confirm($userId) {
		if (isset($_POST['winOrderId'])) {
			date_default_timezone_set("PRC");
			$now = date('Y-m-d H:i:s');
			$winOrderId = $_POST['winOrderId'];
			return json_encode(new jsonBean(200,winOrderModel::confirmGoods($winOrderId,$userId,$now),'修改成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	/*后台接口*/

	public static function modify() {
		if (isset($_POST)) {
			$dataToUpdate = $_POST;
			date_default_timezone_set('PRC');
			$now = date('Y-m-d H:i:s');
			$dataToUpdate['deliveryTime'] = $now;
			$dataToUpdate['logisticsStatus'] = 2;
			return json_encode(new jsonBean(200,winOrderModel::updateData($dataToUpdate['id'],$dataToUpdate,winOrderModel::getTable()),'修改成功'),JSON_UNESCAPED_UNICODE);			
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function winOrderList() {
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$logisticsStatus = isset($_GET['logisticsStatus']) ? $_GET['logisticsStatus'] : -1;
		return json_encode(new jsonBean(200,winOrderModel::winOrderList($page,$logisticsStatus),'获取成功'),JSON_UNESCAPED_UNICODE);
	}
	public static function winOrderCount() {
		$logisticsStatus = isset($_GET['logisticsStatus']) ? $_GET['logisticsStatus'] : -1;
		return json_encode(new jsonBean(200,winOrderModel::winOrderCount($logisticsStatus),'获取成功'),JSON_UNESCAPED_UNICODE);
	}
}
?>