<?php
include __ROOT__.'/model/creditModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
include_once __ROOT__.'/model/balanceModel.php';
Class creditController {
	public static function signIn($userId) {
		date_default_timezone_set("PRC");
		$now = date("Y-m-d");
		$count = creditModel::isSignIn($userId,$now);
		if ($count > 0) {
			return json_encode(new jsonBean(405,array(),'已经签到过了'),JSON_UNESCAPED_UNICODE);
		} else {
			$result = true;
			$result &= creditModel::insertData(array('userId'=>$userId,'time'=>$now,'type'=>1,'amount'=>10),creditModel::getTable());
			$result &= creditModel::addCredits($userId,10);
			return json_encode(new jsonBean($result?200:406,$result,$result?'签到成功':'签到失败'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function isSignIn($userId) {
		date_default_timezone_set("PRC");
		$now = date("Y-m-d");
		$count = creditModel::isSignIn($userId,$now);
		return json_encode(new jsonBean($count>0?200:405,array(),$count>0?'已经签到':'还未签到'),JSON_UNESCAPED_UNICODE);
	}

	public static function getRecord($userId) {
		if (isset($_GET['page']) && isset($_GET['limit'])) {
			$page = $_GET['page'];
			$limit = $_GET['limit'];
			return json_encode(new jsonBean(200,creditModel::getRecordByUser($userId,$page,$limit),'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function exchange($userId) {
		if (isset($_POST) && isset($_POST['amount'])) {
			$amount = ((int)(($_POST['amount'])/100))*100;
			if ($amount > 0)	{
				$arr = userModel::getUserById($userId);
				$credit = $arr[0]->credits;
				if( $credit - $amount >= 0){
					$result = true;
					creditModel::startTransaction();
					$result &= balanceModel::addBalance($userId,$amount/100,0);
					$amount = 0 - $amount;	
					$result &= creditModel::addCreditRecord($userId,$amount,4);
					$arr = userModel::getUserById($userId);				
					$credit = $arr[0]->credits;
					$balance = $arr[0]->balance;
					if($result){
						creditModel::commitTransaction();
						return json_encode(new jsonBean(200,array("credits" => $credit , "balance" => $balance),'积分兑换成功'),JSON_UNESCAPED_UNICODE);
					}else{
						creditModel::rollBackTransaction();
						return json_encode(new jsonBean(400,array("credits" => $credit, "balance" => $balance),'积分兑换失败'),JSON_UNESCAPED_UNICODE);
					}
				}else{
					return json_encode(new jsonBean(401,array(),'积分剩余不足'),JSON_UNESCAPED_UNICODE);
				}
			}else{
				return json_encode(new jsonBean(402,array(),'兑换的积分数太少'),JSON_UNESCAPED_UNICODE);
			}
				
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
}
?>