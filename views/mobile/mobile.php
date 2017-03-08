<?php
require_once "../../config/config.php";
require_once __ROOT__."/controller/userController.php";
if(isset($_GET) && isset($_GET['wx'])){

    session_start();
    if(isset($_SESSION['userId'])){
        // //用户已登录
        // echo "<script>alert('已登录')</script>";
        // $tmp = json_encode($_SESSION);
        // echo "<script>alert($tmp)</script>";
        $returnAddress = $_GET['wx'];
        header("location: http://".SERVER_DOMAIN."/mobile.html#{$returnAddress}");
        exit();
    }else{
        $returnAddress = $_GET['wx'];
        //用户未登录，发起微信登陆页面
        // echo "<script>alert('未登录')</script>";
        header("location: http://".SERVER_DOMAIN."/wx/login?returnAddress={$returnAddress}");
        exit();
    }     
}
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <meta name="format-detection" content="telephone=no">

    <title>夺宝萌主</title>
    <meta name="description" content="TODO">
    <meta name="keywords" content="TODO">

    <link rel="stylesheet" type="text/css" href="/public/js/ionic/release/css/ionic.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="/public/css/animate.min.css"> -->
    <link rel="stylesheet" type="text/css" href="/public/css/mobile.css">

    <script type="text/javascript" src="/public/js/ionic/release/js/ionic.bundle.min.js"></script>
    <script type="text/javascript" src="/public/js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="/public/js/mobile/min/mobile-min.js"></script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>

<body ng-app="YYYG">

    <ion-side-menus>

        <!-- 抽屉菜单 -->
        <ion-side-menu side="left" ng-controller="SideMenuCtrl" ng-cloak>
            <div class="list">
                <div class="item item-divider">商品分类</div>
                <ion-scroll zooming="false" direction="y" height="100%" ng-show="categories.length>0">
                    <div class="list">
                        <ion-radio class="item" ng-model="current_category" ng-value=0 ng-click="selectCategory(0,'全部商品',true)"><span class="ion-ios-cart-outline"></span> 全部商品</ion-radio>
                        <ion-radio class="item" ng-model="current_category" ng-value={{category.id}} ng-click="selectCategory(category.id,category.name,true)" ng-repeat="category in categories"><span class="ion-ios-cart-outline"></span> {{category.name}}</ion-radio>
                    </div>
                </ion-scroll>
            </div>
        </ion-side-menu>

        <!-- 抽屉内容 -->
        <ion-side-menu-content drag-content=false>
            <ion-nav-view></ion-nav-view>
        </ion-side-menu-content>

    </ion-side-menus>

</body>

</html>