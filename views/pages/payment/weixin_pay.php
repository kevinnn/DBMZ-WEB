<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../../components/head.php';?>

        <meta charset="UTF-8">
        <title>微信支付</title>
    </head>
    <body>
        <?php include '../../components/payment_header.php';?>

        <div class="m-weixin">
            
            <div class="m-header">
                <div class="g-wrap f-clear">
                    <div class="m-header-logo">
                        <h1><a class="m-header-logo-link" href="/">一元夺宝</a></h1>
                        <div class="m-header-slogan">
                            <a id="whatIsThis" href="javascript:void(0)" title="什么是一元夺宝？" style="display:none"><img src="http://mimg.127.net/p/one/web/lib/img/promotion/logo_banner_beta.gif"></a>
                        </div>
                    </div>
                    <div class="m-header-steps">
                    </div>
                </div>
            </div>
            <div class="g-wrap">
                <div class="m-weixin-header">
                    <p><strong>请您及时付款，以便订单尽快处理！订单号：41214746</strong></p>
                  
                    <p>请您在提交订单后1小时内支付，否则订单会自动取消。</p>
                </div>
                <div class="m-weixin-main">
                    <h1 class="m-weixin-title">微信支付</h1>
                    <p class="m-weixin-money">扫一扫付款<br><strong>￥1</strong>
                    </p>
                    <p>
                        <img id="code" width="260" height="260" class="m-weixin-code" src="qr.do?size=260&amp;qrtoken=mUZ0iuI" alt="二维码">
                        <img class="m-weixin-demo" src="http://mimg.127.net/p/yy/lib/img/common/weixin_0.png" alt="扫一扫">
                        <img src="http://mimg.127.net/p/yy/lib/img/common/weixin_1.png" alt="请使用微信扫描二维码以完成支付">
                    </p>
                </div>
            </div>
        </div>
        <?php include '../../components/footer.php';?>

    </body>
</html>