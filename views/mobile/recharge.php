<?php 
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "../../config/config.php";
require_once __ROOT__."/wx/lib/WxPay.Api.php";
require_once __ROOT__."/wx/example/WxPay.JsApiPay.php";
require_once __ROOT__.'/controller/userController.php';
$check = json_decode(userController::isLogin(),true);
$fee = $_GET['fee'];
if ($check['code'] == 200 && count($check['data']) == 1) {
	$users = $check['data'];
	$user = $users[0];
	$userId = $user['id'];
} else {
	header('location:/mobile.html#/tab/index');
}
$tools = new JsApiPay();
$openId = $tools->GetOpenid();

$tradeId = strval($userId);
while(strlen($tradeId) < 6) {
	$tradeId = '0'.$tradeId;
}
$tradeId .= date("YmdHis");
$t = explode('.', microtime(true));
$tradeId .= $t[1];
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>充值</title>
    <script type="text/javascript" src="/public/js/jquery/jquery.min.js"></script>
    <script type="text/javascript">
	var fee = '<?php echo $fee;?>',
	tradeId = '<?php echo $tradeId;?>';
	//调用微信JS api 支付
	function jsApiCall()
	{
		$.ajax({
			url: "/wxPay/createBoundByRecharge",
			data: {
				openId: '<?php echo $openId;?>',
				fee: fee,
				tradeId: tradeId
			},
			type: 'POST',
			success: function (data) {
				WeixinJSBridge.invoke(
					'getBrandWCPayRequest',
					data,
					function(res){
						if(res.err_msg == "get_brand_wcpay_request:cancel" ) {
							window.location.href = '/mobile.html#/tab/profile/rechargeResult-'+tradeId+'-'+fee+'-1';
						} else {
							window.location.href = '/mobile.html#/tab/profile/rechargeResult-'+tradeId+'-'+fee+'-0';
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