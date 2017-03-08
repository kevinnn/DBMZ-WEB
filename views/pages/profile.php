<?php
include '../../config/config.php';
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
        <?php include '../components/head.php';?>
        <meta charset="UTF-8">
        <title>ResultsTODO</title>
        <meta name="description" content="TODO">
        <meta name="keywords" content="TODO">
    </head>
    <body ng-app="YYYG">
        <?php include '../components/header.php';?>
        <div class="profile" ng-controller="profileController" name="top" ng-cloak>
            <!-- 头部 bar -->
            <div class="c_homepage_header" style="background: url(../../public/img/app/profile-bar.jpg) no-repeat;">
                <div class="c_homepage_headercon" >
                    <div class="c_headercon_left">
                        <div class="c_investor_img">
                            <a href="javascript:void(0);">
                                <img id="alterFace" ng-src="{{user.avatorUrl}}">
                                <span class="c_text_top" style="display: none">编辑头像</span>
                                <div class="c_top_bg" style="display: none"></div>
                            </a>
                        </div>
                        <div class="c_investor_details">
                            <p><a href="javascript:void(0);">{{user.userName}}</a><span id="prompt">拥抱积极的心态，改变现有的生活。</span></p>
                            <ul class="c_investor_ul">
                                <li>ID：<span id="ID" ng-model="user.id" ng-init="setUserId(<?php echo $user['id'] ;?>)"><?php echo $user['id'] ;?></span><i>|</i></li>
                                <li>手机：<span id="mobile">{{user.phoneNumber}}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 正文内容 -->
            <div id="content_box" class="">
                <div id="member_left" class="member_left">
                    <!-- 左部 side Nav -->
                    <div class="c_left_side">
                        <ul class="c_left_nav">
                            <li><a class="c_out_a" ng-class="{c_click_newa: currentView==='account'}" ng-click="changeView('account')" href=""><i></i>我的账户</a></li>
                            <li><a class="c_out_a" ng-class="{c_click_newa: currentView==='recordBuy'}" ng-click="changeView('recordBuy')" href=""><i class="c_out_i_two"></i>夺宝记录</a></li>
                            <li><a class="c_out_a" ng-class="{c_click_newa: currentView==='balance'}" ng-click="changeView('balance')" href=""><i class="c_out_i_four"></i>充值记录</a></li>
                            <li><a class="c_out_a" ng-class="{c_click_newa: currentView==='recordWin'}" ng-click="changeView('recordWin')" href=""><i class="c_out_i_fourteen"></i>中奖记录</a></li>
                            <li><a class="c_out_a" ng-class="{c_click_newa: currentView==='expose'}" ng-click="changeView('expose')" href=""><i class="c_out_i_six"></i>我的晒单</a></li>
                            <li><a class="c_out_a" ng-class="{c_click_newa: currentView==='credit'}" ng-click="changeView('credit')" href=""><i class="c_out_i_seven"></i>我的积分</a></li>
                            <li><a class="c_out_a" ng-class="{c_click_newa: currentView==='invite'}" ng-click="changeView('invite')" href=""><i class="c_out_i_nine"></i>邀请好友</a></li>
                            <li><a class="c_out_a" ng-class="{c_click_newa: currentView==='setting'}" ng-click="changeView('setting')" href=""><i class="c_out_i_eleven"></i>个人资料</a></li>
                            <li><a class="c_out_a" ng-class="{c_click_newa: currentView==='address'}" ng-click="changeView('address')" href=""><i class="c_out_i_ten"></i>收货地址</a></li>
                        </ul>
                    </div>
                </div>
                <!-- 右边正文 -->
                <div id="member_right" class="member_right c_member_right" style="border:none;">
                    <!-- 我的用户页面显示 -->
                    <!-- 头部信息 -->
                    <?php include './profile/account.php';?>
                    <!-- 夺宝记录 -->
                    <?php include './profile/recordBuy.php';?>
                    <!-- 充值记录 -->
                    <?php include './profile/balance.php';?>
                    <!-- 积分记录 -->
                    <?php include './profile/score.php';?>
                    <!-- 个人资料页面显示 -->
                    <?php include './profile/setting.php';?>
                    <!-- 收货地址 页面显示 -->
                    <?php include './profile/address.php';?>
                    <?php include './modal/address-modal.php';?>
                    <!-- 中奖记录 -->
                    <?php include './profile/recordWin.php';?>
                    <!-- 个人晒单 -->
                    <?php include './profile/share.php';?>
                    <?php include './profile/shareEdit.php';?>
                    <?php include './modal/show-image-delete-modal.php';?>
                    <?php include './modal/show-confirm-modal.php';?>
                    <!-- 邀请好友 -->
                    <?php include './profile/invite.php';?>
                    <?php include './modal/number-modal.php';?>
                </div>
            </div>
        </div>
        <?php include '../components/footer.php';?>
        <script type="text/javascript" src="../../public/js/app.js"></script>
        <script type="text/javascript" src="../../public/js/pages/profile.js"></script>
        <div class="w-mask" style="display: none;"></div>
    </body>
</html>