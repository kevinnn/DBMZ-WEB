<?php
include '../../../config/config.php';
include __ROOT__.'/controller/cashierController.php';
include __ROOT__.'/controller/userController.php';
$User = json_decode(userController::isLogin(),true);
if ($User['code'] == 200) {
	$user = $User['data'][0];
	$result = cashierController::cashierPayment($user['id']);
	if ($result['isPay'] == 1) {
		header('Location: /');
	}
} else {
	header('Location: /');
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include '../../components/head.php';?>
		<title>paymentTODO</title>
		<meta name="description" content="TODO">
		<meta name="keywords" content="TODO">
	</head>
	<body ng-app="YYYG">
		<?php include '../../components/payment_header.php';?>
		<div class="m-header g-wrap f-clear" style="height: 127px;">
			<div class="m-header-logo" style="margin-top: 34px;">
				<h1>
				<a class="m-header-logo-link" href="/">一元夺宝</a>
				</h1>
			</div>
			<div class="m-cart-order-steps">
				<div class="w-step-duobao w-step-duobao-2"> </div>
			</div>
		</div>
		<div class="payment" ng-controller="paymentController">
			<div class="g-wrap">
				<div class="m-cashier-info">
					<div class="header">
						<h2 class="title"><b class="ico ico-suc-m"></b> 订单提交成功，只差最后一步支付就可以啦！</h2>
						<p class="desc">请您在提交订单后<span id="idCountDown" time="<?php echo $result['overTime'] ?>">{{hour}}小时{{minute}}分{{second}}秒</span>完成支付，否则订单会自动取消！</p>
					</div>
					<div class="detail">
						订单号：<?php echo $result['cashierid'] ?>
						<br>
					</div>
				</div>
				<div class="m-cashier-amount">
					<div class="header">
						<div class="amount">应付金额：<span class="txtMoney">￥<?php echo number_format($result['count'], 2) ?></span>
						<span id="amount" amount="<?php echo $result['count']?>"></span>
					</div>
					<div class="useCoin" ng-init="checked=false">
						<label class="w-checkbox w-checkbox-disabled"><input type="checkbox" ng-model="checked" ng-click="updateStillPay(checked)"> <span>使用账户余额支付（账户余额：<?php echo number_format($user['balance'], 2) ?>夺宝币）</span></label>
						<span id="balance" balance="<?php echo $user['balance'] ?>"></span>
					</div>
				</div>
				<div class="content">
					<div class="stillPay">
						<div class="payMoney" ng-cloak ng-show="stillPay > 0">
							<span>还需支付：</span>
							<em class="txtMoney" id="idStill">{{stillPay | currency: "￥"}}</em>
						</div>
						<div class="payment">
							<div tag="payments" ng-show="stillPay > 0">
								<span class="title">支付方式：</span>
								<div class="w-pay-selector" >
									<div class="w-pay-type w-pay-selected">
										<img src="http://mimg.127.net/p/yy/lib/img/bank/SMWX.png" alt="微信扫码">
									</div>
									<div class="w-pay-type">
										<img src="/public/img/app/zhifubao.png" alt="支付宝支付">
									</div>
									<div class="w-pay-type">
										<img src="../../../public/img/app/unionpay.png" alt="银联支付">
									</div>
								</div>
							</div>
							<div class="f-clear"></div>
							<div class="opt" ng-show="canPay">
								<button class="w-button w-button-main w-button-xl" type="button" id="submit-payment-button" ng-click="submitOrder()"><span>立即支付</span></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include '../../components/footer.php';?>
		<script type="text/javascript" src="../../public/js/app.js"></script>
		<script type="text/javascript" src="../../public/js/pages/payment.js"></script>
	</body>
</html>