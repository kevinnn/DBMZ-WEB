<?php 
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "../../config/config.php";
require_once __ROOT__."/wx/lib/WxPay.Api.php";
require_once __ROOT__."/wx/example/WxPay.JsApiPay.php";
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
    <title>微信支付样例-支付</title>
    <script type="text/javascript" src="/public/js/jquery/jquery.min.js"></script>
    <script type="text/javascript">
	var fee = 1,
	cashierid = '11112222233333'
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
						WeixinJSBridge.log(res.err_msg);
						if (res.err_msg == "get_brand_wcpay_request:cancel") {
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
	</script>
</head>
<body>
    <br/>
    <font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px">1分</span>钱</b></font><br/><br/>
	<div align="center">
		<button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >立即支付</button>
	</div>
</body>
</html>