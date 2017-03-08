<?php
include '../../config/config.php';
include __ROOT__.'/controller/userController.php';
include __ROOT__.'/controller/winOrderController.php';
$User = json_decode(userController::isLogin(),true);
if ($User['code'] == 200) {
    $user = $User['data'][0];
    $winOrder = winOrderController::getWinOrderById($user['id']);
    if ($winOrder['logisticsStatus'] != 0) {
        header('Location: /profile/winDetail_finish.html?winOrderId=' . $_GET['winOrderId']);
    }
} else {
    header('Location: /');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../components/head.php';?>
        <meta charset="UTF-8">
        <title>WinDetailTODO</title>
        <meta name="description" content="TODO">
        <meta name="keywords" content="TODO">
    </head>
    <body ng-app="YYYG">
        <?php include '../components/header.php';?>
        <!-- step 2 -->
        <div class="winDetail g-wrap" ng-controller="winDetailController">
            <div class="m-winDetail">
                <div class="winDetail-progress">
                    <ol class="inner">
                        <li class="step step-first step-1 act">
                            <p class="name">1. 获得商品</p>
                            <i class="ico"></i>
                        </li>
                        <li class="step step-2 act">
                            <p class="name">2. 确认收货地址</p>
                            <i class="ico"></i>
                        </li>
                        <li class="step step-3 ">
                            <p class="name">3. 商品派发</p>
                            <i class="ico"></i>
                        </li>
                        <li class="step step-4 ">
                            <p class="name">4. 确认收货</p>
                            <i class="ico"></i>
                        </li>
                        <li class="step step-5 ">
                            <p class="name">5. 晒单分享</p>
                            <i class="ico"></i>
                        </li>
                    </ol>
                </div>
                <!-- 我的地址 -->
                <div class="address-bar-title">
                <span class="title-txt">已保存的有效收货地址</span>
                <a class="title-btn" href="javascript:void(0)" pro="addAddress" ng-click="addAddressByModal()" style="display: block;" ng-show="addressService.address.length !== 0">新增收货地址</a></div>
                <!-- 我的地址 -->
                <div pro="addresslist" class="m-user-address-list">
                    <div class="w-address-bar" >
                        <ul class="bar-view">
                            <li class="bar-header f-clear">
                                <div class="bar-item receiver">收货人</div>
                                <div class="bar-item address">收货地址</div>
                                <div class="bar-item mobile">联系电话</div>
                                <div class="bar-item operation">操作</div>
                            </li>
                            <li class="address-bar empty" ng-hide="isLoading || addressService.address.length !== 0">
                                <p>你现在还没收货地址，赶紧添加吧~！</p>
                            </li>
                            <li>
                                <?php include './components/loading.php';?>
                            </li>
                            <li class="address-bar f-clear" ng-repeat="item in addressService.address" ng-class="{'bar-selected':selectAddressIndex === $index}" ng-click="selectThisAddress($index)" ng-mouseover="mouseoverAddressList($index)" ng-mouseout="mouseoutAddressList($index)" ng-cloak>
                                    <div class="bar-item receiver" style="margin-left:16px;">{{item.receiver}}</div>
                                    <div class="bar-item address">{{item.province}}&nbsp;{{item.city}}&nbsp;{{item.area}}&nbsp;{{item.street}}</div>
                                    <div class="bar-item mobile">{{item.phoneNumber}}</div>
                                    <div class="bar-item operation">
                                        <a pro="setdefault" href="javascript:void(0)" ng-hide="item.status === '1' || mouseoverAddressIndex != $index" class="address-status set-default" ng-click="setAddressDefault($index)">设为默认地址</a>
                                        <span class="address-status default-status" ng-show="item.status === '1'">默认地址</span>&nbsp;
                                        <a href="javascript:void(0)" ng-click="addressUpdate($index);$event.stopPropagation();">修改</a>&nbsp;|&nbsp;
                                        <a href="javascript:void(0)" ng-click="addressService.delete($index);$event.stopPropagation();">删除</a>
                                    </div>
                                    <i class="selected-ico" ng-show="selectAddressIndex === $index"></i>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="winDetail-info">
                    <div class="title">
                        <div class="status">当前状态：确认收货地址</div>
                    </div>
                    <div class="content">
                        <div ng-hide="addressService.address.length !== 0 || isLoading">
                            <?php include './components/address.php';?>
                        </div>
                        <div pro="submit" class="submit" ng-hide="addressService.address.length === 0">
                            <button class="w-button w-button-xl w-button-main" style="margin: 40px 480px;" type="button" ng-click="openAddressConformForm('adddressConform-form')">
                            <span>确认收货地址</span>
                            </button>
                        </div>
                    </div>
                </div>
                <table class="w-table winDetail-goodsInfo" ng-cloak>
                    <thead>
                        <tr>
                            <th class="col1">商品</th>
                            <th class="col2"></th>
                            <th class="col3">价格(夺宝币)</th>
                            <th class="col4">数量</th>
                            <th class="col5">商品状态</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col1">
                                <!-- 商品通用模板 -->
                                <div class="w-goods">
                                    <div class="w-goods-pic">
                                        <a href="/detail/<?php echo $winOrder['productId'] ;?>-<?php echo $winOrder['term'] ;?>.html" target="_blank">
                                            <img src="<?php echo $winOrder['thumbnailUrl'] ;?>" alt="<?php echo $winOrder['title'] ;?>" style="width: 76px;">
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="col2">
                                <div class="w-goods">
                                    <p class="w-goods-title f-txtabb"><a href="/detail/<?php echo $winOrder['productId'] ;?>-<?php echo $winOrder['term'] ;?>.html" target="_blank"><?php echo $winOrder['title'] ;?></a></p>
                                    <div class="code">幸运号码：<strong class="txt-impt"><?php echo $winOrder['result'] ;?></strong></div>
                                    <div class="calcTime">揭晓时间：<?php echo $winOrder['endTime'] ;?>.000</div>
                                </div>
                            </td>
                            <td class="col3">
                                <?php echo $winOrder['price'] ;?>夺宝币
                            </td>
                            <td class="col4">1</td>
                            <td class="col5">
                                待确认收货地址
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php include './modal/address-modal.php';?>
        </div>
        <?php include '../components/footer.php';?>
        <script type="text/javascript" src="../../public/js/app.js"></script>
        <script type="text/javascript" src="../../public/js/pages/winDetail.js"></script>
        <div class="w-mask" id="pro-view-80" style="display: none;"></div>

    </body>
</html>