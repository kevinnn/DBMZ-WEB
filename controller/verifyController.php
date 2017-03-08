<?php
include_once __ROOT__.'/bean/jsonBean.php';
include_once __ROOT__.'/model/globalModel.php';
Class verifyController {

	public static function sendVerificationCode() {

		if (isset($_GET['phoneNumber'])) {
			$phoneNumber = $_GET['phoneNumber'];
			$arr = globalModel::getGlobal($phoneNumber);
			date_default_timezone_set("PRC");
			if (count($arr) == 1) {
				$phoneNumberObj = $arr[0];
				if (time() - strtotime($phoneNumberObj->createdTime) < 60) {
					return json_encode(new jsonBean(404,array(),'验证码已经发送过了'),JSON_UNESCAPED_UNICODE);
				} else {
					globalModel::clearGlobal($phoneNumber);
				}
			}
			$statusStr = array(
				"0" => "短信发送成功",
				"-1" => "参数不全",
				"-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
				"30" => "密码错误",
				"40" => "账号不存在",
				"41" => "余额不足",
				"42" => "帐户已过期",
				"43" => "IP地址限制",
				"50" => "内容含有敏感词"
			);

			$smsapi = "http://api.smsbao.com/";
			$username = "sidongh";
			$password = md5("sisi6612683");

			$arr = array();
			while(count($arr) < 6) {
				$arr[] = rand(0,9);
				$arr = array_unique($arr);
			}

			$code = implode("", $arr);

			$content = "【夺宝盟主】您的验证码是".$code."，若非本人操作请忽略此消息";
			$sendUrl = $smsapi."sms?u=".$username."&p=".$password."&m=".$phoneNumber."&c=".urlencode($content);
			$result = file_get_contents($sendUrl);
			// $result = 0;
			if ($result == 0) {
				globalModel::setGlobal($phoneNumber,$code);
				return json_encode(new jsonBean(200,array(),$statusStr[$result]),JSON_UNESCAPED_UNICODE);
			} else {
				return json_encode(new jsonBean(402,array(),$statusStr[$result]),JSON_UNESCAPED_UNICODE);
			}
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
}
?>