<?php
include_once __ROOT__.'/model/balanceModel.php';
include_once __ROOT__.'/bean/jsonBean.php';

Class balanceController {
	public static function getByTradeId($userId) {
		if (isset($_GET['tradeId'])) {
			$tradeId = $_GET['tradeId'];
			$arr = balanceModel::getByTradeId($tradeId,$userId);
			if (count($arr) == 1) {
				return json_encode(new jsonBean(200,$arr[0],'获取成功'),JSON_UNESCAPED_UNICODE);
			} else {
				return json_encode(new jsonBean(404,array(),'数据为空'),JSON_UNESCAPED_UNICODE);
			}
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}

	}

	public static function getBalanceByUser($userId) {
		if (isset($_GET['limit']) && isset($_GET['page'])) {
			$limit = $_GET['limit'];
			$page= $_GET['page'];
			$arr = balanceModel::getBalanceByPage($userId,$page,$limit);
			return json_encode(new jsonBean(200,$arr,'获取成功'),JSON_UNESCAPED_UNICODE);

		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	/*后台接口*/
	public static function balanceList() {
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$type = isset($_GET['type']) ? $_GET['type'] : -1;
		$userId = isset($_GET['userId']) ? $_GET['userId'] : -1;
		
		$result = balanceModel::getBalanceList($page,$type,$userId);
		return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE);
	}
	public static function balanceCount() {
		$type = isset($_GET['type']) ? $_GET['type'] : -1;
		$userId = isset($_GET['userId']) ? $_GET['userId'] : -1;
		$result = balanceModel::getBalanceCount($type,$userId);
		return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE);
	}
}
?>