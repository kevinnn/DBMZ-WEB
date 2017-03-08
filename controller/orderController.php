<?php
include __ROOT__.'/model/orderModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
Class orderController {
	public static function getByYungou($userId) {
		if (isset($_GET['id'])) {
			$yungouId = $_GET['id'];
			return json_encode(new jsonBean(200,orderModel::getOrderByUserAndYungou($userId,$yungouId),'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getNumbers() {
		if (isset($_GET['id']) && isset($_GET['userId'])) {
			$yungouId = $_GET['id'];
			$userId = $_GET['userId'];
			$arr = orderModel::getNumbersByUserAndYungou($userId,$yungouId);
			$numbers = array();
			$count = 0;
			foreach ($arr as $key => $item) {
				$count += $item['count'];
				array_push($numbers, array('numberStart'=>intval($item['numberStart']),'numberEnd'=>intval($item['numberEnd']),'buyTime'=>$item['buyTime']));
			}
			return json_encode(new jsonBean(200,array('count'=>$count,'numbers'=>$numbers),'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getByUser($userId) {
		$arr = orderModel::getOrderByUser($userId);
		$result = array();
		foreach ($arr as $key => $value) {
			$obj = array(
				'title'=>$value['title'],
				'price'=>intval($value['price']),
				'saleCount'=>$value['saleCount'],
				'thumbnailUrl'=>$value['thumbnailUrl'],
				'startTime'=>$value['startTime'],
				'status'=>$value['status'],
				'productId'=>$value['productId'],
				'term'=>$value['term'],
				'count'=>$value['count'],
				);
			array_push($result, array('amount'=>intval($value['amount']),'product'=>$productObj,'yungou'=>intval($value['yungouId'])));
		}
		return json_encode(new jsonBean(200,array(),'获取成功'),JSON_UNESCAPED_UNICODE);
	}

	public static function winOrder () {
		if (isset($_GET['orderId']) || isset($_GET['yungouId'])) {
			$orderId = isset($_GET['orderId']) ? $_GET['orderId'] : null;
			$yungouId = isset($_GET['yungouId']) ? $_GET['yungouId'] : null;
			$arr = orderModel::getWinOrderById($orderId,$yungouId);
			$resultArr = array();
			foreach ($arr as $key => $value) {
				$sum = orderModel::getOrderCountByUserAndYungou($value['userId'],$value['yungouId']);
				$obj = array(
					'userName'=> $value['userName'],
					'loginArea'=> $value['loginArea'],
					'count'=> $sum,
					'userId'=> $value['userId'],
					'result'=>$value['result']
				);
				array_push($resultArr, $obj);
			}
			return json_encode(new jsonBean(200,$resultArr,'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function ordering () {
		if (isset($_GET['limit'])) {
			$limit = $_GET['limit'];
			$arr = orderModel::getOrdering($limit);
			$resultArr = array();
			foreach ($arr as $key => $value) {
				$obj = array(
					'id'=> $value['id'],
					'productId'=>$value['productId'],
					'title'=>$value['title'],
					'term'=>$value['term'],
					'userName'=> $value['userName'],
					'userId'=> $value['userId'],
					'createdTime'=> $value['createdTime'],
					'count'=> $value['count'],
					'price'=> $value['price'],
					'avatorUrl'=> isset($value['avatorUrl']) ? $value['avatorUrl'] : null
				);
				array_push($resultArr,$obj);
			}
			return json_encode(new jsonBean(200,$resultArr,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}


	public static function orderByYungou () {
		if (isset($_GET['id']) && isset($_GET['page']) && isset($_GET['now'])) {
			$yungouId = $_GET['id'];
			$page = $_GET['page'];
			$now = $_GET['now'];
			date_default_timezone_set("PRC");
			$date = Date("Y-m-d H:i:s",$now);
			$date .= ".000";
			$arr = orderModel::getOrderByYungou($yungouId,$date,$page);
			$result = array();
			foreach ($arr as $key => $value) {
				array_push($result, array(
					'orderId'=>$value['orderId'],
					'buyTime'=>$value['buyTime'],
					'avatorUrl'=>$value['avatorUrl'],
					'loginArea'=>$value['loginArea'],
					'ip'=>$value['ip'],
					'userName'=>$value['userName'],
					'numberStart'=>intval($value['numberStart']),
					'numberEnd'=>intval($value['numberEnd']),
					'userId'=>$value['userId'],
					'count'=>$value['count']
					));
			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function orderByYungouMobile () {
		if (isset($_GET['yid']) && isset($_GET['limit'])) {
			$yungouId = $_GET['yid'];
			$limit = $_GET['limit'];
			$buyTime = isset($_GET['buyTime']) ? $_GET['buyTime'] : null;
			$arr = orderModel::getOrderByYungouMobile($yungouId,$limit,$buyTime);
			$result = array();
			foreach ($arr as $key => $value) {
				array_push($result, array(
					'orderId'=>$value['orderId'],
					'buyTime'=>$value['buyTime'],
					'avatorUrl'=>$value['avatorUrl'],
					'loginArea'=>$value['loginArea'],
					'ip'=>$value['ip'],
					'userName'=>$value['userName'],
					'numberStart'=>intval($value['numberStart']),
					'numberEnd'=>intval($value['numberEnd']),
					'userId'=>$value['userId'],
					'count'=>$value['count']
					));
			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getOrderCount() {
		if (isset($_GET['id'])) {
			$yungouId = $_GET['id'];
			return json_encode(new jsonBean(200,orderModel::getOrderCountByYungouId($yungouId),'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getRecord($userId) {
		if ( !isset($_GET['id']) && $userId != null ) {$_GET['id'] = $userId;}
		if (isset($_GET['id']) && isset($_GET['page']) && isset($_GET['limit'])) {
			$userId = $_GET['id'];
			$status = isset($_GET['status']) ? $_GET['status'] : -1;
			$page = $_GET['page'];
			$limit = $_GET['limit'];

			$result = array();
			$arr = orderModel::getRecordByUser($userId,$page,$limit,$status);
			foreach ($arr as $key => $value) {
				$numArr = orderModel::getNumbersByUserAndYungou($userId,$value['yungouId']);
				$numbers = array();
				$count = 0;
				foreach ($numArr as $key1 => $item) {

					$count += $item['count'];
					array_push($numbers, array('numberStart'=>intval($item['numberStart']),'numberEnd'=>intval($item['numberEnd']),'buyTime'=>$item['buyTime']));
				}
				$resultArr = array();
				if ($value['status'] == 2) {
					$winArr = orderModel::getWinOrderById($value['orderId'],$value['yungouId']);
					$buyTimeArr = orderModel::getBuyTimeByOrder($value['orderId']);
					if (count($winArr) > 0) {
						if ($winArr[0]['uid'] == $userId) {
							$value['isWin'] = '1';
						}
						$sum = orderModel::getOrderCountByUserAndYungou($winArr[0]['uid'],$value['yungouId']);
						$resultArr = array(
							'userId'=>$winArr[0]['uid'],
							'userName'=> $winArr[0]['userName'],
							'loginArea'=> $winArr[0]['loginArea'],
							'count'=> $sum,
							'userId'=> $winArr[0]['userId'],
							'result'=> $value['result'],
							'buyTime'=>$buyTimeArr[0]['buyTime']
						);
					}
				}
				array_push($result, array(
					'thumbnailUrl'=>$value['thumbnailUrl'],
					'title'=>$value['title'],
					'price'=>$value['price'],
					'productId'=>$value['productId'],
					'isWin'=>$value['isWin'],
					'term'=>$value['term'],
					'yungouId'=>$value['yungouId'],
					'status'=>$value['status'],
					'endTime'=>$value['endTime'],
					'startTime'=>$value['startTime'],
					'timeout'=>$value['endTime']-time(),
					'saleCount'=>$value['saleCount'],
					'isOn'=>$value['isOn'],
					'numbers'=>$numbers,
					'winUser'=>$resultArr,
					'count'=>$count
					));

			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getCountRecord($userId) {
		if ( !isset($_GET['id']) && $userId != null ) {$_GET['id'] = $userId;}
		if (isset($_GET['id'])) {
			$userId = $_GET['id'];

			$result = array();
			for ($i=-1; $i < 3; $i++) { 
				$count = orderModel::getCountRecordByUser($userId,$i);
				array_push($result,$count);
			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getWinRecord($userId) {
		if ( !isset($_GET['id']) && $userId != null ) {$_GET['id'] = $userId;}
		if (isset($_GET['id']) && isset($_GET['page']) && isset($_GET['limit'])) {
			$userId =$_GET['id'];
			$page = $_GET['page'];
			$limit = $_GET['limit'];
			$arr = orderModel::getWinRecordByUser($userId,$page,$limit);
			$result = array();
			foreach ($arr as $key => $value) {
				$obj = array();
				foreach ($value as $key1 => $value1) {
					if (!is_numeric($key1)) {
						$obj=array_merge($obj, array($key1=>$value1));
					}
				}
				$sum = orderModel::getOrderCountByUserAndYungou($userId,$obj['yungouId']);
				$obj=array_merge($obj, array('count'=>$sum));
				array_push($result, $obj);
			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getCountWinRecord($userId) {
		if ( !isset($_GET['id']) && $userId != null ) {$_GET['id'] = $userId;}
		if (isset($_GET['id'])) {
			$userId = $_GET['id'];
			return json_encode(new jsonBean(200,orderModel::getCountWinRecordByUser($userId),'获取成功'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function winOrderDetail() {
		if (isset($_GET['yungouId'])) {
			$yungouId = $_GET['yungouId'];
			$arr = orderModel::winOrderDetailByYungou($yungouId);
			$obj = array();
			foreach ($arr as $key => $value) {
				foreach ($value as $key1 => $value1) {
					if (!is_numeric($key1)) {
						$obj = array_merge($obj,array($key1=>$value1));
					}
				}
				$sum = orderModel::getOrderCountByUserAndYungou($obj['userId'],$yungouId);
				$obj = array_merge($obj,array('count'=>$sum));
			}
			return json_encode(new jsonBean(200,$obj,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	/*后台接口*/
	public static function orderList() {
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$productId = isset($_GET['productId']) ? $_GET['productId'] : 0;
		$isWin = isset($_GET['isWin']) ? $_GET['isWin'] : 0;
		$status = isset($_GET['status']) ? $_GET['status'] : 0;
		$logisticsStatus = isset($_GET['logisticsStatus']) ? $_GET['logisticsStatus'] : -1;
		$ispay = isset($_GET['isPay']) ? $_GET['isPay'] : -1;
		$userId = isset($_GET['userId']) ? $_GET['userId'] : -1;
		return json_encode(new jsonBean(200,orderModel::getOrderList($page,$productId,$isWin,$logisticsStatus,$status,$ispay,$userId),'获取成功'),JSON_UNESCAPED_UNICODE);
	}
	public static function orderCount() {
		$productId = isset($_GET['productId']) ? $_GET['productId'] : -1;
		$isWin = isset($_GET['isWin']) ? $_GET['isWin'] : 0;
		$logisticsStatus = isset($_GET['logisticsStatus']) ? $_GET['logisticsStatus'] : -1;
		$status = isset($_GET['status']) ? ($_GET['status']==1 ? 1 : -1) : -1;
		$ispay = isset($_GET['isPay']) ? $_GET['isPay'] : -1;
		$userId = isset($_GET['userId']) ? $_GET['userId'] : -1;
		return json_encode(new jsonBean(200,orderModel::getOrderCount($productId,$isWin,$logisticsStatus,$status,$ispay,$userId),'获取成功'),JSON_UNESCAPED_UNICODE);
	}
	public static function orderDetail() {
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$arr = orderModel::getOrderDetail($id);
			if (count($arr) > 0) {
				$obj = $arr[0];
				if ($obj['isWin'] == 1) {
					$winOrderArr = orderModel::getOrderDetailForWinOrder($id);
					if (count($winOrderArr) == 1) {
						$winOrderObj = $winOrderArr[0];
						$obj = array_merge($obj,array('winOrder'=>$winOrderObj));
						if ($winOrderObj->addressId != null) {
							$addressArr = orderModel::getOrderDetailForAddress($winOrderObj->addressId);
							if (count($addressArr) == 1) {
								$obj = array_merge($obj,array('address'=>$addressArr[0]));
							}
						}

					}
				}
			}
			return json_encode(new jsonBean(200,array($obj),'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(405,array(),'未传参数'));
		}
	}
}
?>