<?php
include '../../config/config.php';
include __ROOT__.'/controller/userController.php';
$otherUser = userController::getUser();
$User = json_decode(userController::isLogin(),true);

if ($User['code'] == 200) {
    $user = $User['data'][0];
    if ($otherUser->id == $user['id']) {
        header('Location: /profile');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../components/head.php';?>
        <meta charset="UTF-8">
        <title>UserTODO</title>
        <meta name="description" content="TODO">
        <meta name="keywords" content="TODO">
    </head>
    <body ng-app="YYYG">
        <?php include '../components/header.php';?>
        <div class="g-wrap">
            <div class="profile">
                <div class="m-user-frame-wraper profile-user" ng-controller="userProfileController">
                    <!-- 左侧导航栏 -->
                    <div class="m-user-frame-colNav">
                        <h3>
                        <a href="/user/index.do?cid=79215399">Ta的夺宝</a>
                        
                        </h3>
                        <hr>
                        <ul class="userFrameNav">
                            <li>
                                <a href="" pro="userDuobao" ng-class="{'a-active': currentView === 'recordOtherBuy'}" ng-click="changeView('recordOtherBuy')">夺宝记录 <strong pro="userDuobao_num" data-pos="userNav" class="txt-impt"></strong></a>
                            </li>
                            <li>
                                <a href="" pro="userWin" ng-class="{'a-active': currentView === 'recordOtherWin'}" ng-click="changeView('recordOtherWin')">幸运记录 <strong pro="userWin_num" data-pos="userNav" class="txt-impt"></strong></a>
                            </li>
                            <li>
                                <a href="" pro="userShare" ng-class="{'a-active': currentView === 'expose'}" ng-click="changeView('expose')">Ta的晒单 <strong pro="userShare_num" data-pos="userNav" class="txt-impt"></strong></a>
                            </li>
                        </ul>
                    </div>
                    <div class="m-user-frame-colMain">
                        <div class="m-user-frame-content" pro="userFrameWraper">
                            <div>
                                <div class="m-user-duobao">
                                    <!-- 头像 -->
                                    <div class="m-user-comm-infoBox f-clear">
                                        <img class="m-user-comm-infoBox-face" onerror="this.src='http://mimg.127.net/p/yy/lib/img/avatar/160.jpeg'" src="<?php echo $otherUser->avatorUrl;?>" width="160" height="160">
                                        <div class="m-user-comm-infoBox-cont">
                                            <ul>
                                                <li class="item nickname">
                                                    <span class="txt"><?php echo $otherUser->userName;?></span>
                                                </li>
                                                <li class="item"><span class="txt">ID：<strong><?php echo $otherUser->id;?></strong></span></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- 夺宝记录 list-->
                                    <?php include './profile/recordOtherBuy.php';?>
                                    <!-- 中奖记录 -->
                                    <?php include './profile/recordOtherWin.php';?>
                                    <!-- 晒单 -->
                                    <?php include './profile/share.php';?>
                                    <?php include './modal/other-number-modal.php';?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-user-frame-clear"></div>
    </div>
    <?php include '../components/footer.php';?>
    <script type="text/javascript" src="../../public/js/app.js"></script>
    <script type="text/javascript" src="../../public/js/pages/user.js"></script>
    <div class="w-mask" style="display: none;"></div>

</body>
</html>