<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "../../config/config.php";
require_once __ROOT__."/wx/lib/WxPay.Api.php";
require_once __ROOT__.'/wx/lib/WxPay.Notify.php';
require_once __ROOT__.'/controller/wxPaymentController.php';
require_once __ROOT__.'/model/globalModel.php';

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);

		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			//TODO业务逻辑

			$arr = globalModel::getGlobal($result['out_trade_no']);
			if (count($arr) == 0) {
				globalModel::setGlobal($result['out_trade_no'],$result['cash_fee']);
				$back = wxPaymentController::paySuccess($result['out_trade_no'],$result['cash_fee']/100);
				$result = globalModel::clearGlobal($result['out_trade_no']);
			}
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{

		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
}

$notify = new PayNotifyCallBack();
$notify->Handle(false);
