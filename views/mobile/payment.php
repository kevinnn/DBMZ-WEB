<?php 
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "../../config/config.php";
require_once __ROOT__."/wx/lib/WxPay.Api.php";
require_once __ROOT__."/wx/example/WxPay.JsApiPay.php";
require_once __ROOT__.'/controller/userController.php';
$check = json_decode(userController::isLogin(),true);
$fee = $_GET['fee'];
$cashierid = $_GET['cashierid'];
if ($check['code'] == 200 && count($check['data']) == 1) {
	$users = $check['data'];
	$user = $users[0];
	$userId = $user['id'];
	require_once __ROOT__.'/controller/cashierController.php';
	$cashierDate = cashierController::cashierPayment($userId);
	if ($cashierDate['isPay'] == 1) {
		header('location:/mobile.html#/tab/index');
	}
} else {
	header('location:/mobile.html#/tab/index');
}
// require_once 'log.php';
//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();

// $input = new WxPayUnifiedOrder();
// $input->SetBody("test");
// $input->SetAttach("test");
// $input->SetOut_trade_no('10002020160506172913');
// $input->SetTotal_fee("1");
// $input->SetTime_start(date("YmdHis"));
// $input->SetTime_expire(date("YmdHis", time() + 600));
// $input->SetGoods_tag("test");
// $input->SetNotify_url("http://test.dangke.co/wx/example/notify.php");
// $input->SetTrade_type("JSAPI");
// $input->SetOpenId($openId);
// $order = WxPayApi::unifiedOrder($input);
// $jsApiParameters = $tools->GetJsApiParameters($order);

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>支付</title>
    <script type="text/javascript" src="/public/js/jquery/jquery.min.js"></script>
    <script type="text/javascript">
	var fee = '<?php echo $fee;?>',
	cashierid = '<?php echo $cashierid;?>';
	//调用微信JS api 支付
	function jsApiCall()
	{
		$.ajax({
			url: "/wxPay/createBound",
			data: {
				openId: '<?php echo $openId;?>',
				fee: fee,
				cashierid: cashierid
			},
			type: 'POST',
			success: function (data) {
				WeixinJSBridge.invoke(
					'getBrandWCPayRequest',
					data,
					function(res){
						if(res.err_msg == "get_brand_wcpay_request:cancel" ) {
							window.location.href = '/mobile.html#/tab/cart/payresult-'+cashierid+'-'+fee+'-1';
						} else {
							window.location.href = '/mobile.html#/tab/cart/payresult-'+cashierid+'-'+fee+'-0';
						}
					}
				);
			}
		})			
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	window.onload = function () {
		callpay();
	}
	</script>
</head>
<body>
</body>
</html>