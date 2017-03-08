<?php
include '../../../config/config.php';
include __ROOT__.'/controller/userController.php';
include __ROOT__.'/controller/cashierController.php';
$User = json_decode(userController::isLogin(),true);
if ($User['code'] == 200) {
$user = $User['data'][0];
if (!isset($_SERVER['HTTP_REFERER'])) {
header('Location: /');
} else if (strstr($_SERVER['HTTP_REFERER'], "payment?cashierid=" . $_GET['cashierid']) === false) {
header('Location: /');
} else {
$result = cashierController::cashierPayment($user['id']);
}
} else {
header('Location: /');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../../components/head.php';?>
        <meta charset="UTF-8">
        <title>payment_finshTODO</title>
        <meta name="description" content="TODO">
        <meta name="keywords" content="TODO">
    </head>
    <body ng-app="YYYG" ng-controller="paymentFinishController">
        <?php include '../../components/payment_header.php';?>
        <div class="m-order-pay-result">
            <div class="m-header g-wrap f-clear" style="height: 127px;">
                <div class="m-header-logo" style="margin-top: 34px;">
                    <h1>
                    <a class="m-header-logo-link" href="/">一元夺宝</a>
                    </h1>
                </div>
                <div class="m-cart-order-steps">
                    <div class="w-step-duobao w-step-duobao-3"> </div>
                </div>
            </div>
            <div class="m-order-pay-result-content">
                <?php if($result['isPay'] == 1): ?>
                <div class="message" ng-hide="isAllFailPay" ng-cloak>
                    <b class="ico ico-suc"></b>
                    <h1 class="title" style="white-space:nowrap;">恭喜您，参与成功！请等待系统为您揭晓！<a href="/profile" style="font-weight:normal;font-size:13px" id="seeDBRecord">查看夺宝记录</a></h1>
                    <ul class="tips">
                        <li>您现在可以 <a id="back" href="/">返回首页</a> 或 <a id="seeMore" class="w-button w-button-main" href="/list/listAll.html">查看更多商品</a></li>
                    </ul>
                </div>
                <?php elseif($result['isPay'] == 0): ?>
                <div class="message">
                    <b class="ico ico-wrong"></b>
                    <h1 class="title" style="white-space:nowrap;">支付失败！余额可能不足或已支付过订单<a href="/profile" style="font-weight:normal;font-size:13px" id="seeDBRecord">查看夺宝记录</a></h1>
                    <ul class="tips">
                        <li>您现在可以 <a id="back" href="/">返回首页</a> 或 <a id="seeMore" class="w-button w-button-main" href="/list.html">查看更多商品</a></li>
                    </ul>
                </div>
                <?php endif ?>
                <div class="special" pro="special"></div>
                
                <table class="list w-table" id="successList" ng-cloak ng-hide="isAllFailPay">
                    <thead>
                        <tr>
                            <th>夺宝时间</th>
                            <th>商品名称</th>
                            <th style="text-align:center;">商品期号</th>
                            <th style="text-align:center;">参与人次</th>
                            <th>当期号码</th>
                        </tr>
                    </thead>
                    <tbody ng-hide="isLoading || orders.length === 0" ng-repeat="item in orders" >
                        <tr ng-show="item.status === '1'">
                            <td>{{item.buyTime}}</td>
                            <td>
                                <p><a target="_blank" href="/detail/{{item.productId}}-{{item.term}}.html">{{item.title}}</a></p>
                            </td>
                            <td style="text-align:center;">{{item.term}}</td>
                            <td style="text-align:center;">{{item.count}}</td>
                            <td>
                                <ul>
                                    <li>{{item.numberStart}}</li>
                                    <li><a href="javascript:void(0)" class="more" ng-click="showOrderNumberModal($index)" ng-show="item.numberEnd - item.numberStart >= 1">查看更多</a></li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h3 class="fail-title" ng-hide="isAllSuccessPay" ng-cloak>以下商品参与夺宝失败 <span>（购买失败的夺宝币已返还到您的账户，下次购买可直接抵扣金额）</span></h3>
                <table class="list w-table fail-list" id="failList"  ng-hide="isAllSuccessPay" ng-cloak>
                    <thead>
                        <tr>
                            <th>商品名称</th>
                            <th style="text-align:center;">商品期号</th>
                            <th style="text-align:center;">参与人次</th>
                        </tr>
                    </thead>
                    <tbody ng-hide="isLoading || orders.length === 0" ng-repeat="item in orders" >
                        <tr ng-show="item.status === '0'">
                            <td>
                                <p><a target="_blank" href="/detail/{{item.productId}}-{{item.term}}.html">{{item.title}}</a></p>
                            </td>
                            <td style="text-align:center;">{{item.term}}</td>
                            <td style="text-align:center;">{{item.count}}</td>
                        </tr>
                    </tbody>
                </table>
                <?php include '../components/loading.php';?>
            </div>
            <?php include '../modal/order-number-modal.php';?>
            <!-- 推荐夺宝 -->
            <div tag="moduleRecommend" style="margin-top:30px;" ng-cloak>
                <div class="w-goodsRecommend" >
                    <div class="w-hd">
                        <h3 class="w-hd-title">推荐夺宝</h3><span>根据你的浏览记录推荐的商品</span>
                    </div>
                    <div class="w-recommend-bd">
                        <ul class="w-goodsList f-clear" pro="goodsList">
                            <li class="w-goodsList-item" ng-repeat="item in recommendProducts">
                                <div class="w-goods w-goods-brief">
                                    <div class="w-goods-pic">
                                        <a href="/detail/{{item.id}}.html" title="{{item.title}}" target="_blank"><img width="200" height="200" alt="{{item.title}}" ng-src="{{item.thumbnailUrl}}"></a>
                                    </div>
                                    <p class="w-goods-title f-txtabb"><a title="{{item.title}}" href="/detail/{{item.id}}.html" target="_blank">{{item.title}}</a></p>
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
    <script type="text/javascript" src="../../public/js/pages/payment_finish.js">
    </script>
    <div class="w-mask" id="pro-view-80" style="display: none;"></div>
</body>
</html>