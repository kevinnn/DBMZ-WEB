<?php
include_once dirname(dirname(dirname(__FILE__))).'/config/config.php';
include_once __ROOT__.'/controller/categoryController.php';
?>
<header ng-controller="HeaderController" id="top" name="top">
    <!-- 头部 start -->
    <div class="header header_fixed" style="margin-bottom: 0px;">
        <!-- 工具条 start -->
        <div class="header1">
            <div class="header1in">
                <ul class="headerul1">
                    <li><a style="padding-left:40px;font-size: 14px;"><i class="header-tel"></i>400-187-6777</a></li>
                    <li class="phoneli header-WeChatli">
                        <a href="javascript:;" style=" padding-bottom: 0px;">关注我们<i class="i-header-WeChat"></i></a>
                        <img src="http://www.ygqq.com/static/img/front/cloud_global/clienty.png">
                    </li>
                    <li class="phoneli header-phoneli">
                        <a href="http://www.ygqq.com/static/zt/app3/load.htm" style="border-right:0px;" target="_black">手机APP下载<i class="i-header-phone"></i></a>
                        <img src="http://www.ygqq.com/static/img/front/cloud_global/fullPage/android_app.png">
                    </li>
                </ul>
                <ul class="headerul2">
                    <li class="li_popup_doregister" ng-show="!user">
                        <a href="javascript:void(0);" style="padding-left:30px;color:#dd2726;" ng-click="register()"><img style="position:absolute;top:-1px;left:7px;" src="/public/img/app/top_add.png">免费注册</a>
                    </li>
                    <li class="li_popup_login" ng-show="!user">
                        <a href="javascript:void(0);" ng-click="login()">登录</a>
                    </li>
                    <li ng-show="user" ng-cloak>
                        <a href="/profile" ng-bind="user.userName"></a>
                    </li>
                    <li ng-show="user" ng-cloak>
                        <a href="javascript:void(0);" ng-click="logout()">[退出]</a>
                    </li>
                    <li class="MyzhLi">
                        <a href="/profile">我的零钱夺宝<i class="top"></i></a>
                        <dl class="Myzh">
                            <dd><a href="/profile?recordBuy" >夺宝记录</a></dd>
                            <dd><a href="/profile?recordWin">幸运记录</a></dd>
                            <dd><a href="/profile?setting">个人中心</a></dd>
                        </dl>

                    </li>
                    <li><a href="/recharge" ng-click="checkLoginAndModal($event)">充值</a></li>
                    <li><a style="border-right:none;" href="/helpcenter?knowCloud">帮助</a></li>
                </ul>
            </div>
        </div>
        <!-- 工具条 end -->
        <div class="header2">
            <!-- logo start -->
            <a href="/" class="header_logo"><img src="http://mimg.127.net/logo/yy_logo.gif"></a>
            <a href=""><img src="http://mimg.127.net/p/yy/hd/150602_appprompt/logo_banner_v3.png"></a>
            <!-- logo end -->
            <!-- 搜索框 start -->
            <div class="search_header2">
                <s></s>
                <input type="text" placeholder="搜索您需要的商品" value="" id="q">
                <a href="" class="btnHSearch">搜索</a>
            </div>
            <!-- 搜索框 end -->
        </div>
    </div>
    <!-- 头部 end -->
    <!-- 导航条 start -->
    <div class="yNavIndexOut yNavIndexOut_fixed yNavIndexOut_fixed_index" id="yNav">
        <div class="yNavIndex">
            <div class="pullDown">
                <h4 class="pullDownTitle">
                <a href="/list/listAll.html">所有商品分类</a>
                </h4>
                <!-- 下拉详细列表 start -->
                <ul class="pullDownList">
                    <li>
                        <i><img src="/public/img/app/index_icon_nav_all.png"></i>
                        <a href='/list/listAll.html'>全部商品</a>
                        <span></span>
                    </li>
                    <?php
                    $data = json_decode(categoryController::allCategory(),true);
                    $categories = $data['data'];
                    foreach ($categories as $key => $category) {
                    ?>
                    <li>
                        <i><img src="/public/img/app/index_icon_nav_<?php echo $category['id'];?>.png"></i>
                        <a href="/list/<?php echo $category['id'];?>.html"><?php echo $category['name'];?></a>
                        <span></span>
                    </li>
                    <?php } ?>
                </ul>
                <!-- 下拉详细列表 end -->
            </div>
            <ul class="yMenuIndex">
                <li>
                    <a href="/index.html" ng-class="{yMenua: pageSite === 'index.html'}">首页</a>
                    <em>/</em>
                </li>
                <!--                 <li>
                    <a href="/ten_zone" ng-class="{yMenua: pageSite === 'ten_zone'}">十元专区</a>
                    <em>/</em>
                </li> -->
                <li>
                    <a href="/results.html" ng-class="{yMenua: pageSite === 'results.html'}">最新揭晓</a>
                    <em>/</em>
                </li>
                <li>
                    <a href="/show" ng-class="{yMenua: pageSite === 'show'}">晒单分享</a>
                    <em>/</em>
                </li>
                <li style="position:relative">
                    <div style="position: absolute;top: -10px;background-color: #ffefca;color:#f60;left:30%;opacity:0;" id="spanSignIn">
                        <span>+50</span><i></i>
                    </div>
                    <a href="javascript:void(0);" ng-disabled="isSignIn" ng-click="checkLoginAndModal($event);signIn()" ng-cloak>{{isSignIn?"已签到":"签到"}}</a>
                </li>
            </ul>
            <!-- 购物车 -->
            <div class="w-miniCart" ng-cloak>
                <!-- 购物车按钮 -->
                <a class="w-miniCart-btn" href="javascript:void(0);" ng-click="checkLoginAndModal($event)">
                    <i class="ico ico-miniCart"></i>清 单<b class="w-miniCart-count">
                    <i class="ico ico-arrow-white-solid ico-arrow-white-solid-l" id="cart-btn"></i>{{shoppingCarts.length}}</b>
                    </a><!-- end 购物车按钮 -->
                    <!-- 购物车列表 -->
                    <div class="w-layer w-miniCart-layer">
                        <div pro="title">
                            <p class="w-miniCart-layer-title">
                                <strong>最近加入的商品</strong>
                            </p>
                        </div>
                        <ul pro="list" class="w-miniCart-list" ng-cloak>
                            <!-- 单个购物车商品 -->
                            <li class="w-miniCart-item" ng-repeat="item in shoppingCarts track by $index">
                                <div class="w-miniCart-item-pic">
                                    <img width="74px" height="74px" ng-src="{{item.product.thumbnailUrl}}" alt="{{item.product.title}}">
                                </div>
                                <div class="w-miniCart-item-text">
                                    <p>
                                        <strong>{{item.product.title}}</strong>
                                    </p>
                                    <p>
                                        <em>{{item.amount}}夺宝币 × 1期</em>
                                        <a class="w-miniCart-item-del" href="javascript:void(0);" ng-click="deleteFromCart(item)">删除</a>
                                    </p>
                                </div>
                                </li><!-- end 单个购物车商品 -->
                            </ul>
                            <div pro="footer" class="w-miniCart-layer-footer">
                                <p>
                                    <strong>共有<b pro="count">{{shoppingCarts.length}}</b>件商品，金额总计：<em><span>{{shoppingCarts | calCount}}</span>夺宝币</em></strong>
                                </p>
                                <p>
                                    <a href="/cartIndex" pro="go" class="w-button w-button-main" ng-click="checkLoginAndModal($event)">查看清单并结算</a>
                                </p>
                            </div>
                            </div><!-- end 购物车列表 -->
                            </div><!-- end 购物车 -->
                        </div>
                    </div>
                    <!-- 导航条 end -->
                </header>