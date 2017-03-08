<?php
include __ROOT__.'/model/cashierModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
Class cashierController {
	public static function add ($userId) {
		if (isset($_POST['orders'])) {
			date_default_timezone_set('PRC');
			$overTime = date('Y-m-d H:i:s',strtotime("+30 minutes"));
			$cashierid = date('YmdHis');
			$t = explode('.', microtime(true));
			$cashierid .= $t[1];
			$code = (string)$userId;
			while (strlen($code) < 6) {
				$code = '0'.$code; 
			}
			$cashierid = $code.$cashierid;
			$orders = $_POST['orders'];
			$result = cashierModel::addCashier($orders,$userId,$overTime,$cashierid);
			return json_encode(new jsonBean($result ? 200 : 405,$cashierid,$result ? '插入成功' : '插入失败'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function cashierPayment ($userId) {
		if ($_GET['cashierid']) {
			$cashiers = cashierModel::getCashierByCashierid($_GET['cashierid'],$userId);
			if (count($cashiers) > 0) {
				date_default_timezone_set('PRC');
				$cashier = $cashiers[0];
				$result = array(
						'overTime'=> strtotime($cashier->overTime)-time(),
						'cashierid'=>$cashier->cashierid,
						'count'=>$cashier->count,
						'isPay'=>$cashier->isPay
					);
				return $result;
			} else {
				header('location:/');
			}
		} else {
			header('location:/');
		}
	}

	public static function getDetailByCashier ($userId) {
		if (isset($_GET['cashierid'])) {
			$cashierid = $_GET['cashierid'];
			$arr = cashierModel::getDetailByCashier($cashierid,$userId);
			if (count($arr) == 0) {
				header('location:/');
			}
			$result = array();
			foreach ($arr as $key => $value) {
				$obj = array();
				foreach ($value as $key1 => $value1) {
					if (!is_numeric($key1)) {
						$obj = array_merge($obj,array($key1=>$value1));
					}
				}
				array_push($result, $obj);
			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			header('location:/');
		}
	}

	public static function getCashier ($userId) {
		if (isset($_GET['cashierid'])) {
			date_default_timezone_set("PRC");
			$cashierid = $_GET['cashierid'];
			$arr = cashierModel::getCashierMobile($cashierid,$userId);
			$result = array();
			foreach ($arr as $key => $value) {
				$obj = array(
					'timeout'=>strtotime($value['overTime'])-time(),
					'title'=>$value['title'],
					'count'=>$value['count'],
					'isPay'=>$value['isPay']
					);
				array_push($result, $obj);
			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getDetailByCashierMobile ($userId) {
		if (isset($_GET['cashierid'])) {
			$cashierid = $_GET['cashierid'];
			$arr = cashierModel::getDetailByCashier($cashierid,$userId);
			$result = array();
			foreach ($arr as $key => $value) {
				$obj = array();
				foreach ($value as $key1 => $value1) {
					if (!is_numeric($key1)) {
						$obj = array_merge($obj,array($key1=>$value1));
					}
				}
				array_push($result, $obj);
			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
}
?>