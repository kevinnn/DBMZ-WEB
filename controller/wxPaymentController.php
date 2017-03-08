<?php
include __ROOT__.'/model/cashierModel.php';
include __ROOT__.'/model/paymentModel.php';
include __ROOT__.'/model/userModel.php';
include_once __ROOT__.'/model/creditModel.php';
include_once __ROOT__.'/model/balanceModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
require_once __ROOT__.'/wx/template_msg/templateModel.php';
require_once __ROOT__.'/wx/wx.Config.php';

Class wxPaymentController {
	//支付成功，处理相应逻辑
	public static function paySuccess($cashierid,$fee) {
		//判断用户余额是否能够完成支付

		$result = cashierModel::getCashierByCashierid($cashierid,null);
		$cashier = $result[0];
		$count = $cashier->count-$fee;
		$userId = $cashier->userId;
		$user = userModel::getUserById($userId);
		$user = $user[0];
		$balance = $user->balance;
		//修改payStatus=1
		if ($cashier->payStatus > 0) {
			return ;
		}
		paymentModel::updateCashierPayStatus($userId,$cashierid,1);

		//重复支付判断，未支付：isPay=1，修改用户余额，然后插入余额变动记录。否则直接将支付金钱转为余额，插入余额变动记录，结束
		if ($cashier->isPay) {//已支付
			//修改用户余额，并插入余额变动数据
			paymentModel::updateUserBalance($userId,$balance+$fee);
			paymentModel::insertBalanceRecording(4,$userId,$cashierid,$fee);
			paymentModel::execute();
			//修改payStatus=2
			paymentModel::updateCashierPayStatus($userId,$cashierid,2);
			return json_encode(new jsonBean(405,array(),'订单已经支付过了'),JSON_UNESCAPED_UNICODE);
		} else {//未支付
			include __ROOT__.'/model/orderModel.php';
			if ($count != 0) {
				if ($balance < $count) {
					//余额不足
					paymentModel::updateUserBalance($userId,$balance+$fee);
					paymentModel::insertBalanceRecording(7,$userId,$cashierid,$fee);
					paymentModel::execute();
					//修改payStatus=2
					paymentModel::updateCashierPayStatus($userId,$cashierid,2);
					return json_encode(new jsonBean(405,array(),'支付失败,余额不足'),JSON_UNESCAPED_UNICODE);
				}
				//减去余额，插入余额变动表
				$balance -= $count;

				paymentModel::insertBalanceRecording(3,$userId,$cashierid,$count);
			}

			//首次消费的话给邀请自己的非代理用户余额增加一元
			if(cashierModel::getPaidCashierCount($userId) == 0){
				$sql = "SELECT id,isDelegate from `User` where `code` =(SELECT invitedBy from `User` where id = {$userId})";
				$results = mysql::queryTables($sql);
				if (count($results) > 0) {

					$olduserid = $results[0]['id'];
					$isDelegate = $results[0]['isDelegate'];
					if ($isDelegate == 0){
						balanceModel::addBalance($olduserid,1,6,$cashierid);
					}
				}
			}

			//修改order的isPay
			paymentModel::updateCashierIsPay($userId,$cashierid,$count,2);

			$orders = orderModel::getOrderByUserIdAndCashierid($userId,$cashierid);


			include __ROOT__.'/model/yungouModel.php';
			include __ROOT__.'/model/productModel.php';
			//支付失败总额
			$failCount = 0;
			//修改status=1的orderID数组
			foreach ($orders as $key => $order) {
				$productArr = productModel::getProductById($order->productId);
				$product = $productArr[0];
				paymentModel::startTransaction();
				$yungouArr = paymentModel::getYungouById($order->yungouId);

				$yungou = $yungouArr[0];
				if ($product->price - $yungou->saleCount >= $order->count) {//订单完成
					//修改订单状态
					paymentModel::updateOrderStatus($order->id);

					//修改yungou的saleCount
					paymentModel::updateYungouSaleCount($order->yungouId,$order->count);
					//分配夺宝号
					paymentModel::distributionNumbers($order->id,$yungou->saleCount,$order->count);
					paymentModel::endTransaction();
					
					//微信推送购买成功模板消息
					if($user->type == 2){
						$productName = $product->title;								
						$participateCount = $order->count;					
						$term = $yungou->term;		
						$openid = $user->openId;
						$msg = "$productName\n商品期号：$term\n参与人次：{$participateCount}人次";
						$templateMsg = new templateMsg(WxConfig::appID, WxConfig::appsecret);
						$templateMsg->sendPurchaseSuccessMsg($openid,"http://".SERVER_DOMAIN."/wx/login?returnAddress=/tab/profile?location=recordBuy",$msg);
					}
					//判断云购是否人满
					if ($yungou->saleCount + $order->count == $product->price) {
						yungouModel::enough($yungou->id,$order->productId,$yungou->term);
					}
				} else {//订单插入失败
					$failCount += $order->count;
					paymentModel::endTransaction();
				}
			}

			if ($failCount > 0) {
				//将不能完成的订单的金额转为余额，并插入余额变动
				$balance += $failCount;
				paymentModel::updateUserBalance($userId,$balance);
				paymentModel::insertBalanceRecording(2,$userId,$cashierid,$failCount);
				paymentModel::execute();
				//修改payStatus=2
				paymentModel::updateCashierPayStatus($userId,$cashierid,2);
				//将成功支付的部分订单的金额数添加到积分
				$successCount = $count - $failCount;
				creditModel::addCreditRecord($userId,$successCount);
				// 将支付金额数添加到邀请自己的代理的cash
				$sql = "SELECT id,isDelegate from `User` where `code` =(SELECT invitedBy from `User` where id = {$userId})";			
				$results = mysql::queryTables($sql);
				if (count($results) > 0) {
					$olduserid = $results[0]['id'];
					$isDelegate = $results[0]['isDelegate'];					
					if ($isDelegate == 1){
						delegateModel::addCashRecords($olduserid,$successCount * delegateModel::rate /100);					
					}
				}
				return json_encode(new jsonBean(200,array(),'部分订单支付成功'),JSON_UNESCAPED_UNICODE);
			}
			paymentModel::updateUserBalance($userId,$balance);
			paymentModel::execute();
			//修改payStatus=2
			paymentModel::updateCashierPayStatus($userId,$cashierid,2);
			// 将支付金额数添加到积分
			creditModel::addCreditRecord($userId,$count);
			// 将支付金额数添加到邀请自己的代理的cash
			$sql = "SELECT id,isDelegate from `User` where `code` =(SELECT invitedBy from `User` where id = {$userId})";			
			$results = mysql::queryTables($sql);
			if (count($results) > 0) {
				$olduserid = $results[0]['id'];
				$isDelegate = $results[0]['isDelegate'];					
				if ($isDelegate == 1){
					delegateModel::addCashRecords($olduserid,$count * delegateModel::rate /100);					
				}
			}
			return json_encode(new jsonBean(200,array(),'全部订单支付成功'),JSON_UNESCAPED_UNICODE);
			
		}
	}
	public static function rechargeSuccess ($tradeId,$fee) {
		$userId = intval(substr($tradeId,0,6));
		$user = userModel::getUserById($userId);
		$user = $user[0];
		$balance = $user->balance;
		
		$openid = $user->openId;
		$templateMsg = new templateMsg(WxConfig::appID, WxConfig::appsecret);			
		$templateMsg->sendDepositSuccess($openid,"http://".SERVER_DOMAIN."/wx/login?returnAddress=/tab/profile?location=balance","恭喜您！充值成功！","{$fee}元","微信");
		
		$arr = paymentModel::getBalanceRecording($userId,$tradeId);
		if (count($arr) == 0) {
			paymentModel::insertBalanceRecording(1,$userId,$tradeId,$fee);
			paymentModel::updateUserBalance($userId,$balance+$fee);
			paymentModel::execute();
		}
	}

	public static function createBound () {
		ini_set('date.timezone','Asia/Shanghai');
		error_reporting(E_ERROR);
		require_once __ROOT__."/wx/lib/WxPay.Api.php";
		require_once __ROOT__."/wx/example/WxPay.JsApiPay.php";
		$fee = $_POST['fee'];
		$openId = $_POST['openId'];
		$cashierid = $_POST['cashierid'];
		//①、获取用户openid
		$tools = new JsApiPay();
		$input = new WxPayUnifiedOrder();
		$input->SetBody("夺宝萌主夺宝");
		$input->SetAttach("支付");
		$input->SetOut_trade_no($cashierid);
		$input->SetTotal_fee($fee);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600*3));
		$input->SetGoods_tag("支付");
		$input->SetNotify_url("http://test.dangke.co/views/mobile/notify.php");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = WxPayApi::unifiedOrder($input);
		$jsApiParameters = $tools->GetJsApiParameters($order);
		return $jsApiParameters;
	}

	public static function createBoundByRecharge () {
		ini_set('date.timezone','Asia/Shanghai');
		error_reporting(E_ERROR);
		require_once __ROOT__."/wx/lib/WxPay.Api.php";
		require_once __ROOT__."/wx/example/WxPay.JsApiPay.php";
		$fee = $_POST['fee'];
		$openId = $_POST['openId'];
		$tradeId = $_POST['tradeId'];
		
		$tools = new JsApiPay();
		$input = new WxPayUnifiedOrder();
		$input->SetBody("夺宝萌主充值");
		$input->SetAttach("充值");
		$input->SetOut_trade_no($tradeId);
		$input->SetTotal_fee($fee);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600*3));
		$input->SetGoods_tag("充值");
		$input->SetNotify_url("http://test.dangke.co/views/mobile/notifyRecharge.php");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = WxPayApi::unifiedOrder($input);
		$jsApiParameters = $tools->GetJsApiParameters($order);
		return $jsApiParameters;
	}
}
?>