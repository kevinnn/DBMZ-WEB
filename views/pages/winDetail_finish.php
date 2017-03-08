<?php
include '../../config/config.php';
include __ROOT__.'/controller/userController.php';
include __ROOT__.'/controller/winOrderController.php';
include __ROOT__.'/controller/addressController.php';
$User = json_decode(userController::isLogin(),true);
if ($User['code'] == 200) {
$user = $User['data'][0];
winOrderController::confirmAddress($user['id']);
$winOrder = winOrderController::getWinOrderById($user['id']);
$address = winOrderController::getWinOrderAddress($user['id']);
} else {
header('Location: /');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../components/head.php';?>
        <meta charset="UTF-8">
        <title>WinDetail_finishTODO</title>
        <meta name="description" content="TODO">
        <meta name="keywords" content="TODO">
    </head>
    <body ng-app="YYYG">
        <?php include '../components/header.php';?>
        <!-- step 2 -->
        <div class="winDetail g-wrap" ng-controller="winDetailFinishController">
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
                        <li class="step step-3 act">
                            <p class="name">3. 商品派发</p>
                            <i class="ico"></i>
                        </li>
                        <li class="step step-4 <?php if ($winOrder['logisticsStatus'] > 2):echo 'act'; endif?>">
                            <p class="name">4. 确认收货</p>
                            <i class="ico"></i>
                        </li>
                        <li class="step step-5 <?php if ($winOrder['logisticsStatus'] > 3):echo 'act'; endif?>">
                            <p class="name">5. 晒单分享</p>
                            <i class="ico"></i>
                        </li>
                    </ol>
                </div>
                
                <div class="winDetail-info">
                    <?php if ($winOrder['logisticsStatus'] == 1):?>
                    <div class="title">
                        <div class="status" pro="status">当前状态：等待商品派发</div>
                        <div class="tips">
                            我们已将商品发往您填写/确认的配送地址信息，请您耐心等候！
                        </div>
                    </div>
                    <?php elseif ($winOrder['logisticsStatus'] == 2):?>
                    <div class="title">
                        <div class="status" pro="status">当前状态：商品已派发<button class="w-button w-button-main" type="button" ng-click="openReceiveGoodConfirmModal()"><span>确认收货</span></button></div>
                        <div class="tips">
                            我们已将商品发往您填写/确认的配送地址信息，请您耐心等候！
                        </div>
                    </div>
                    <?php elseif ($winOrder['logisticsStatus'] == 3):?>
                    <div class="title">
                        <div class="status" pro="status">当前状态：已签收</div>
                        <div class="tips">
                            商品已被签收！再次恭喜获得本商品，祝您夺宝愉快~！
                        </div>
                    </div>
                    <?php endif?>
                    <div class="content">
                        <div class="addressInfo">
                            <div class="name"><strong>收货信息</strong></div>
                            <div class="cont">
                                <p>收 货 人：<?php echo $address['receiver'];?></p>
                                <p>联系电话：
                                    <?php echo $address['phoneNumber'];?>
                                </p>
                                <p>收货地址：<?php echo $address['province'];?>，<?php echo $address['city'];?>，<?php echo $address['area'];?>，<?php echo $address['street'];?><?php echo " | " . $address['postCode'];?></p>
                            </div>
                        </div>
                    </div>
                    <?php if($winOrder['logisticsStatus'] > 1):?>
                    <div class="content">
                        <div class="addressInfo">
                            <div class="name"><strong>物流信息</strong></div>
                            <div class="cont">
                                <p>物流公司：<?php echo $winOrder['logisticsCompany'];?></p>
                                <p>运单号码：
                                    <?php echo $winOrder['expressTradeId'];?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif?>
                </div>
                
                <table class="w-table winDetail-goodsInfo">
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
                                        <a href="/detail/460-304181146.html" target="_blank">
                                            <img src="<?php echo $winOrder['thumbnailUrl'];?>" style="width: 76px;" alt="<?php echo $winOrder['title'];?>">
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="col2">
                                <div class="w-goods">
                                    <p class="w-goods-title f-txtabb"><a href="/detail/<?php echo $winOrder['productId'];?>-<?php echo $winOrder['term'];?>.html" target="_blank"><?php echo $winOrder['title'];?></a></p>
                                    <div class="code">幸运号码：<strong class="txt-impt"><?php echo $winOrder['result'];?></strong></div>
                                    <div class="calcTime">揭晓时间：<?php echo $winOrder['endTime'];?>.000</div>
                                </div>
                            </td>
                            <td class="col3">
                                <?php echo $winOrder['price'];?>夺宝币
                            </td>
                            <td class="col4">1</td>
                            <?php if ($winOrder['logisticsStatus'] == 1):?>
                            <td class="col5">
                                待发货
                            </td>
                            <?php elseif ($winOrder['logisticsStatus'] == 2):?>
                            <td class="col5">
                                已发货
                            </td>
                            <?php elseif ($winOrder['logisticsStatus'] == 3):?>
                            <td class="col5">
                                已签收
                                <a href="/profile?recordWin" target="_blank">晒单</a>
                            </td>
                            <?php elseif ($winOrder['logisticsStatus'] == 4):?>
                            <td class="col5">
                                <a href="/showDetail?yungouId=<?php echo $winOrder['yungouId']?>" target="_blank">晒单详情</a>
                            </td>
                            <?php endif?>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php include './modal/receive-good-confirm-modal.php';?>
        </div>
        <?php include '../components/footer.php';?>
        <script type="text/javascript" src="../../public/js/app.js"></script>
        <script type="text/javascript" src="../../public/js/pages/winDetail_finish.js"></script>
        <div class="w-mask" id="pro-view-80" style="display: none;"></div>
    </body>
</html>