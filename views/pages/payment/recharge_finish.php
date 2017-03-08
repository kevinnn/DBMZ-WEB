<?php
include '../../../config/config.php';
include __ROOT__.'/controller/userController.php';
$User = json_decode(userController::isLogin(),true);
if ($User['code'] == 200) {
$user = $User['data'][0];
} else {
header('Location: /');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../../components/head.php';?>
        <meta charset="UTF-8">
        <title>recharge_finshTODO</title>
        <meta name="description" content="TODO">
        <meta name="keywords" content="TODO">
    </head>
    <body ng-app="YYYG" ng-controller="rechargeFinishController">
        <?php include '../../components/payment_header.php';?>
        <div class="m-order-pay-result">
            <div class="m-header g-wrap f-clear" style="height: 127px;">
                <div class="m-header-logo" style="margin-top: 34px;">
                    <h1>
                    <a class="m-header-logo-link" href="/">一元夺宝</a>
                    </h1>
                </div>
            </div>
            <div class="m-order-pay-result-content">
                <div class="message" ng-show="code==200">
                    <b class="ico ico-suc"></b>
                    <h1 class="title" style="white-space:nowrap;">恭喜您，获得10个夺宝币！</h1>
                    <ul class="tips">
                        <li>您现在可以 <a id="seeMore" class="w-button w-button-main" href="/">返回首页</a></li>
                    </ul>
                </div>
                <div class="special" pro="special"></div>
            </div>
            <!-- 推荐夺宝 -->
            <div tag="moduleRecommend" style="margin-top:30px;" >
                <div class="w-goodsRecommend" >
                    <div class="w-hd">
                        <h3 class="w-hd-title">推荐夺宝</h3><span>根据你的浏览记录推荐的商品</span></div>
                        <div class="w-recommend-bd">
                            <ul class="w-goodsList f-clear" pro="goodsList">
                                <li class="w-goodsList-item" ng-repeat="item in recommendProducts">
                                    <div class="w-goods w-goods-brief">
                                        <div class="w-goods-pic">
                                            <a href="/detail/975.html" title="{{item.title}}" target="_blank"><img width="200" height="200" alt="{{item.title}}" src="{{item.thumbnailUrl}}"></a>
                                        </div>
                                        <p class="w-goods-title f-txtabb"><a title="{{item.title}}" href="/detail/975.html" target="_blank">{{item.title}}</a></p>
                                        <p class="w-goods-price">总需：{{item.price}}人次</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../../components/footer.php';?>
        <script type="text/javascript" src="../../public/js/app.js"></script>
        <script type="text/javascript" src="../../public/js/pages/recharge_finish.js">
        </script>
    </body>
</html>