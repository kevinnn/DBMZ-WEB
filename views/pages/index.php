<?php
include dirname(dirname(dirname(__FILE__))).'/config/config.php';
include __ROOT__.'/controller/productController.php';
include __ROOT__.'/controller/yungouController.php';
include __ROOT__.'/controller/orderController.php';
include __ROOT__.'/controller/bannerController.php';

$hotProductNumber = 8;
$categoryProductNumber = 4;
$newProductNumber = 20;
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <?php include '../components/head.php';?>

    <link rel="stylesheet" type="text/css" href="../../public/flexslider/flexslider.css">

    <title>TODO</title>
    <meta name="description" content="TODO，就是指只需1元就有机会获得一件商品，好玩有趣，不容错过。">
    <meta name="keywords" content="1元,一元,1元夺宝,1元购,1元购物,1元云购,一元夺宝,一元购,一元购物,一元云购,夺宝奇兵">
</head>

<body ng-app="YYYG" ng-cloak>

	<?php include '../components/header.php';?>
    <div ng-controller="IndexController">
    <!-- 分类 & 轮播框 -->
    <section class="g-banner clearfix" style="width: 100%;">
        <!-- 首页轮播框 -->
        <div class="yBanner">
            <div id="banner" class="flexslider">
                <ul class="slides">
                    <?php
                        $bannerArr = json_decode(bannerController::all(),true);
                        $bannerArr = $bannerArr['data'];
                        foreach ($bannerArr as $key => $value) {
                    ?>
                    <li>
                        <a href="<?php echo $value['url'];?>"><div class="img" style="background:url(<?php echo $value['imgUrl'];?>) no-repeat center 0px;"></div></a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div><!-- end 首页轮播框 -->
    </section>
    <div class="g-wrap nav-category-section m-index-mod clearfix">
        <div class="w-hd clearfix">
            <h3 class="w-hd-title">正在揭晓</h3>
            <a class="w-hd-more" href="/results.html">更多商品，点击查看&gt;&gt;</a>
        </div>
        <ul class="w-slide-wrap-list">
            <li pro="item" class="w-slide-wrap-list-item" ng-repeat="item in remainItems track by $index">
                <div class="w-goods-newReveal">
                    <p class="w-goods-title f-txtabb"><a href="/detail/{{item.productId}}-{{item.term}}.html" title="{{item.title}}" target="_blank">{{item.title}}</a></p>
                    <div class="w-goods-pic">
                        <a href="/detail/{{item.productId}}-{{item.term}}.html" target="_blank">
                            <img width="120" height="120" ng-src="{{item.thumbnailUrl}}">
                        </a>
                    </div>
                    <p class="w-goods-price">总需：{{item.price}}人次</p>
                    <p class="w-goods-period">期号：{{item.term}}</p>
                    <!-- 正在倒计时时显示 -->
                    <div class="w-goods-counting">
                        <div class="w-goods-countdown">揭晓倒计时:
                            <div class="w-countdown">
                                <span class="w-countdown-nums">
                                    <b>{{Math.floor(intervalService.index.time[$index][0] / 10 % 10) < 0 ? 0 : Math.floor(intervalService.index.time[$index][0] / 10 % 10)}}</b>
                                    <b>{{intervalService.index.time[$index][0] % 10 < 0 ? 0 : intervalService.index.time[$index][0] % 10}}</b>:
                                    <b>{{Math.floor(intervalService.index.time[$index][1] / 10 % 10) < 0 ? 0 : Math.floor(intervalService.index.time[$index][1] / 10 % 10)}}</b>
                                    <b>{{intervalService.index.time[$index][1] % 10 < 0 ? 0 : intervalService.index.time[$index][1] % 10}}</b>:
                                    <b>{{Math.floor(intervalService.index.time[$index][2] / 10 % 10) < 0 ? 0 : Math.floor(intervalService.index.time[$index][2] / 10 % 10)}}</b>
                                    <b>{{intervalService.index.time[$index][2] % 10 < 0 ? 0 : intervalService.index.time[$index][2] % 10}}</b>
                                </span>
                                <p class="w-countdown-ing txt-red" style="display:none;">请稍候，正在计算中…</p>
                            </div>
                        </div>
                    </div>
                    <!-- 加载错误提示 -->
                    <div class="w-goods-error" style="display:none">
                        <p class="txt-gray w-goods-err">非常抱歉~
                            <br>服务器开小差了，请稍后再试...</p>
                    </div>
                </div>
            </li>
            <li pro="item" class="w-slide-wrap-list-item" ng-repeat="item in finishedItems track by $index">
                <div class="w-goods-newReveal">
                    <p class="w-goods-title f-txtabb"><a href="/detail/{{item.productId}}-{{item.term}}.html" title="{{item.title}}" target="_blank">{{item.title}}</a></p>
                    <div class="w-goods-pic">
                        <a href="/detail/{{item.productId}}-{{item.term}}.html" target="_blank">
                            <img width="120" height="120" ng-src="{{item.thumbnailUrl}}">
                        </a>
                    </div>
                    <p class="w-goods-price">总需：{{item.price}}人次</p>
                    <p class="w-goods-period">期号：{{item.term}}</p>
                    <!-- 加载错误提示 -->
                    <div class="w-goods-error" style="display:none">
                        <p class="txt-gray w-goods-err">非常抱歉~
                            <br>服务器开小差了，请稍后再试...</p>
                    </div>
                    <!-- 倒计时完成后显示 -->
                    <div class="w-goods-record">
                        <p class="w-goods-owner f-txtabb">获得者：
                            <a href="/user/{{item.user.userId}}.html" title="{{item.user.userName}}(ID:{{item.user.userId}})">
                                <b>{{item.user.userName}}</b>
                            </a>
                        </p>
                        <p>本期参与：{{item.user.count}}人次</p>
                        <p>幸运号码：{{item.user.result}}</p>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <!-- 最热商品 & 最新云购 -->
    <div class="g-wrap g-body-bd f-clear clearfix">
        <!-- 最热商品 -->
        <div class="g-main">
            <div class="m-index-mod m-index-goods-hotest">
                <!-- head -->
                <div class="w-hd">
                    <h3 class="w-hd-title">最热商品</h3>
                    <a class="w-hd-more" href="/list/listAll.html">更多商品，点击查看&gt;&gt;</a>
                 </div>
                <!-- body -->
                <div class="m-index-mod-bd">
                    <ul class="w-goodsList f-clear">
                        <!-- 单个商品 -->
                        <?php
                            $productArr = json_decode(productController::productIsHot($hotProductNumber),true);
                            $productArr = $productArr['data'];
                            $productIdArr = array();
                            foreach ($productArr as $key => $value) {
                                array_push($productIdArr, $value['id']);
                            }
                            $yungouArr = json_decode(yungouController::yungouProductIdArr($productIdArr,0),true);
                            $yungouArr = $yungouArr['data'];
                            $arr = array();
                            $resultArr = array();
                            for ($i=0; $i < count($productArr); $i++) {
                                for ($j=0; $j < count($yungouArr); $j++) {
                                    if ($yungouArr[$j]['productId'] == $productArr[$i]['id']) {
                                        $arr['product'] = $productArr[$i];
                                        $arr['yungou'] = $yungouArr[$j];
                                        array_push($resultArr, $arr);
                                        $arr = array();
                                    }
                                }
                            }
                            foreach ($resultArr as $key => $value) {
                        ?>
                        <li class="w-goodsList-item row-first">
                            <div class="w-goods w-goods-ing">
                                <!-- 图片 -->
                                <div class="w-goods-pic">
                                    <a href="/detail/<?php echo $value['product']['id'].'-'.$value['yungou']['term'].'.html';?>" title="<?php echo $value['product']['title'];?>" target="_blank">
                                        <img width="200" height="200" src="<?php echo $value['product']['thumbnailUrl'];?>" onerror="this.src='http://mimg.127.net/p/one/web/lib/img/products/l.png'" id="<?php echo 'hot'.$value['product']['id'];?>">
                                    </a>
                                </div>
                                <p class="w-goods-title f-txtabb">
                                    <a title="<?php echo $value['product']['title'];?>" href="/detail/<?php echo $value['product']['id'].'-'.$value['yungou']['term'].'.html';?>" target="_blank"><?php echo $value['product']['title'];?></a>
                                </p>
                                <p class="w-goods-price">总需：<?php echo $value['product']['price'];?> 人次</p>
                                <div class="w-progressBar" title="<?php echo $value['yungou']['saleCount']/$value['product']['price'];?>">
                                    <p class="w-progressBar-wrap">
                                        <span class="w-progressBar-bar" style="width:<?php echo ($value['yungou']['saleCount']/$value['product']['price']*100).'%';?>;"></span>
                                    </p>
                                    <ul class="w-progressBar-txt f-clear">
                                        <li class="w-progressBar-txt-l"><p><b><?php echo $value['yungou']['saleCount'];?></b></p><p>已参与人次</p></li>
                                        <li class="w-progressBar-txt-r"><p><b><?php echo $value['product']['price']-$value['yungou']['saleCount'];?></b></p><p>剩余人次</p></li>
                                    </ul>
                                </div>
                                <p class="w-goods-progressHint">
                                    <b class="txt-blue"><?php echo $value['yungou']['saleCount'];?></b>人次已参与，赶快去参加吧！剩余<b class="txt-red"><?php echo $value['product']['price']-$value['yungou']['saleCount'];?></b>人次
                                </p>
                                <div class="w-goods-opr">                                  
                                    <a class="w-button w-button-main w-button-l w-goods-quickBuy" href="/cartIndex?yungouId=<?php echo $value['yungou']['id'];?>&amount=<?php echo $value['product']['singlePrice'];?>" style="width:70px;" title="立即夺宝">立即夺宝</a>
                                    <a class="w-button w-button-addToCart" href="javascript:void(0);" ng-click="addToCart(<?php echo $value['yungou']['id'];?>,<?php echo $value['product']['singlePrice'];?>,'<?php echo $value['product']['thumbnailUrl'];?>','<?php echo $value['product']['title'];?>',<?php echo $value['product']['price']?>);toCartCartoon('<?php echo 'hot'.$value['product']['id'];?>')" title="添加到购物车"></a>
                                </div>
                            </div>
                        </li>
                        <?php } ?>       
                    </ul>
                </div>
            </div>
        </div>

        <!-- 正在云购 -->       
        <div class="g-side" ng-cloak>
            <div class="m-index-mod m-index-recordRank m-index-recordRank-nb">
                <!-- head -->
                <div class="m-index-mod-hd">
                    <h3>正在云购</h3>
                </div>
                <!-- body -->
                <div class="m-index-mod-bd">
                    <ul class="w-intervalScroll" data-minline="8" id="pro-view-10" style="margin-top: 0px;">
                        <li pro="item" class="w-intervalScroll-item odd" ng-repeat="item in ordering track by $index">
                            <div class="w-record-avatar">
                                <img width="40" height="40" ng-src="{{item.avatorUrl}}">
                            </div>
                            <div class="w-record-detail">
                                <p class="w-record-intro">
                                    <a class="w-record-user f-txtabb" href="/user/{{item.userId}}.html" target="_blank" title="{{item.userName}}(ID:{{item.userId}})">{{item.userName}}</a>
                                    <span class="w-record-date">于{{item.createdTime}}</span>
                                </p>
                                <p class="w-record-title f-txtabb"><strong>参与{{item.count}}人次</strong> <a title="{{item.title}}" href="/detail/{{item.productId}}-{{item.term}}.html" target="_blank">{{item.title}}</a></p>
                                <p class="w-record-price">总需：{{item.price}} 人次</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- foot -->
                <div class="m-index-mod-ft">看看谁的狗屎运最好！</div>
                </div>
        </div>
    </div>

    <!-- 分类项目 -->
    <div class="g-wrap g-body-ft f-clear clearfix">
        <?php
            $categoryArr = json_decode(categoryController::categoryIsOnIndex(),true);
            $categoryArr = $categoryArr['data'];
            foreach ($categoryArr as $key => $value) {
        ?>
        <div class="m-index-mod m-index-goods-catlog clearfix">
            <div class="w-hd clearfix">
                <h3 class="w-hd-title"><?php echo $value['name'];?></h3>
                <a class="w-hd-more" href="/list/<?php echo $value['id'];?>.html">更多商品，点击查看&gt;&gt;</a>
            </div>
            <div class="m-index-mod-bd f-clear">
                <div class="w-slide m-index-promotGoods">
                    <div class="w-slide-wrap">
                        <ul class="w-slide-wrap-list" pro="list">
                            <li pro="item" class="w-slide-wrap-list-item">
                                <a href="/detail/1555.html" target="_blank">
                                    <img width="239" height="400" src="<?php echo $value['coverUrl'];?>">
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="w-slide-controller">
                        <div class="w-slide-controller-btn" pro="controllerBtn">
                            <a class="prev" pro="prev" href="javascript:void(0)"><i class="ico ico-arrow-large ico-arrow-large-l"></i></a>
                            <a class="next" pro="next" href="javascript:void(0)"><i class="ico ico-arrow-large ico-arrow-large-r"></i></a>
                        </div>
                    </div>
                </div>
                <ul class="w-goodsList">
                    <?php
                        $productArr = json_decode(productController::productByCategoryId($value['id']),true);
                        $productArr = $productArr['data'];
                        $productIdArr = array();
                        foreach ($productArr as $key1 => $value1) {
                            array_push($productIdArr, $value1['id']);
                        }
                        $yungouArr = json_decode(yungouController::yungouCreatedTime($productIdArr,$categoryProductNumber),true);
                        $yungouArr = $yungouArr['data'];
                        $arr = array();
                        $resultArr = array();
                        for ($i=0; $i < count($productArr); $i++) {
                            for ($j=0; $j < count($yungouArr); $j++) {
                                if ($yungouArr[$j]['productId'] == $productArr[$i]['id']) {
                                    $arr['product'] = $productArr[$i];
                                    $arr['yungou'] = $yungouArr[$j];
                                    array_push($resultArr, $arr);
                                    $arr = array();
                                }
                            }
                        }
                        foreach ($resultArr as $key1 => $value1) {
                    ?>
                    <li class="w-goodsList-item">
                        <div class="w-goods w-goods-ing">
                            <div class="w-goods-pic">
                                <a href="/detail/<?php echo $value1['product']['id'].'-'.$value1['yungou']['term'].'.html';?>" title="<?php echo $value1['product']['title'];?>" target="_blank">
                                    <img width="200" height="200" alt="<?php echo $value1['product']['title'];?>" src="<?php echo $value1['product']['thumbnailUrl'];?>" onerror="this.src='http://mimg.127.net/p/one/web/lib/img/products/l.png'" id="<?php echo $value['name'].$value1['product']['id'];?>">
                                </a>
                            </div>
                            <p class="w-goods-title f-txtabb"><a title="<?php echo $value1['product']['title'];?>" href="/detail/116-303225976.html" target="_blank"><?php echo $value1['product']['title'];?></a></p>
                            <p class="w-goods-price">总需：<?php echo $value1['product']['price'];?> 人次</p>
                            <div class="w-progressBar" title="<?php echo ($value1['yungou']['saleCount']/$value1['product']['price']*100).'%';?>">
                                <p class="w-progressBar-wrap">
                                    <span class="w-progressBar-bar" style="width:<?php echo ($value1['yungou']['saleCount']/$value1['product']['price']*100).'%';?>;"></span>
                                </p>
                                <ul class="w-progressBar-txt f-clear">
                                    <li class="w-progressBar-txt-l">
                                        <p><b><?php echo $value1['yungou']['saleCount'];?></b></p>
                                        <p>已参与人次</p>
                                    </li>
                                    <li class="w-progressBar-txt-r">
                                        <p><b><?php echo $value1['product']['price']-$value1['yungou']['saleCount'];?></b></p>
                                        <p>剩余人次</p>
                                    </li>
                                </ul>
                            </div>
                            <p class="w-goods-progressHint">
                                <b class="txt-blue"><?php echo $value1['yungou']['saleCount'];?></b>人次已参与，赶快去参加吧！剩余<b class="txt-red"><?php echo $value1['product']['price']-$value1['yungou']['saleCount'];?></b>人次
                            </p>
                            <div class="w-goods-opr">
                                <a class="w-button w-button-main w-button-l w-goods-quickBuy" href="/cartIndex?yungouId=<?php echo $value1['yungou']['id'];?>&amount=<?php echo $value1['product']['singlePrice'];?>" style="width:70px;" title="立即夺宝">立即夺宝</a>
                                <a class="w-button w-button-addToCart" href="javascript:void(0);" ng-click="addToCart(<?php echo $value1['yungou']['id'];?>,<?php echo $value1['product']['singlePrice'];?>,'<?php echo $value1['product']['thumbnailUrl'];?>','<?php echo $value1['product']['title'];?>',<?php echo $value1['product']['price']?>);toCartCartoon('<?php echo $value['name'].$value1['product']['id'];?>')" title="添加到购物车"></a>
                            </div>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <?php } ?>
        <a name="newArrivals"></a>
        <div class="m-index-mod m-index-newArrivals clearfix">
            <div class="w-hd">
                <h3 class="w-hd-title">最新上架</h3>
            </div>
            <div class="m-index-mod-bd">
                <ul class="w-goodsList f-clear">
                    <?php
                        $productArr = json_decode(productController::productIsNew($newProductNumber),true);
                        $productArr = $productArr['data'];
                        foreach ($productArr as $key => $value) {
                    ?>
                    <li class="w-goodsList-item">
                        <div class="w-goods w-goods-brief">
                            <div class="w-goods-pic">
                                <a href="/detail/<?php echo $value['id'];?>.html" title="<?php echo $value['title'];?>" target="_blank">
                                    <img width="200" height="200" alt="<?php echo $value['title'];?>" src="<?php echo $value['thumbnailUrl'];?>" onerror="this.src='http://mimg.127.net/p/one/web/lib/img/products/l.png'">
                                </a>
                            </div>
                            <p class="w-goods-title f-txtabb"><a title="<?php echo $value['title'];?>" href="/detail/<?php echo $value['id'];?>.html" target="_blank"><?php echo $value['title'];?></a></p>
                            <p class="w-goods-price">总需：<?php echo $value['price'];?> 人次</p>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    </div>
    <?php include '../components/footer.php';?>

    <script type="text/javascript" src="../../public/js/app.js"></script>

    <script type="text/javascript" src="../../public/flexslider/jquery.flexslider-min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#banner').flexslider({
                animation : "slide",
                direction : "horizontal",
                easing : "swing",
                slideshowSpeed: 2000,
                pauseOnHover:true
            });
            
        })
        app.controller('IndexController', function ($rootScope, $scope, $http, $interval,intervalService) {
            $scope.Math = Math;
            $scope.intervalService = intervalService;
            $scope.revealListRemainItems = new Array();
            $scope.remainItems = new Array();
            $scope.finishedItems = new Array();
            var second = (new Date()).valueOf()/1000;
            $http.get("/yungou/fastStart?page=1&limit=3&now="+second)
            .success(function (data) {
                if (data.code == 200) {
                    data = data.data;
                    for(var index in data) {
                        if (data[index].status == 2) {
                            (function (index) {
                                $http.get("/order/winOrder?orderId="+data[index].orderId)
                                .success(function (data2) {
                                    if (data2.code == 200) {
                                        data2 = data2.data;
                                        if (data2.length>0) {
                                            data[index].user = data2[0];
                                        }
                                        $scope.finishedItems.push(data[index]);
                                    }

                                })
                                .error(function (data2) {
                                    console.log(data2);
                                });
                            })(index);
                        } else {
                            $scope.remainItems.push(data[index]);
                            $scope.revealListRemainItems.push(data[index].timeout*100);
                        }
                    }
                    $scope.intervalService.init('index',$scope.revealListRemainItems);
                    $scope.intervalService.countdown('index',function(e){
                        $scope.remainItems[e].timeout = 0;
                        $http.get("/order/winOrder?yungouId="+$scope.remainItems[e].yungouId)
                        .success(function (data) {
                            if (data.code == 200) {
                                data = data.data;
                                $scope.remainItems[e].user = data[0];
                            }
                            $scope.finishedItems.unshift($scope.remainItems[e]);
                            $scope.remainItems.splice(e,1);
                        })
                        .error(function (data) {
                            console.log(data);
                        });
                    });
                }
                
            })
            .error(function (data) {
                console.log(data);
            });

            $scope.ordering = new Array();

            $scope.addToCart = function (yungouId,singlePrice,thumbnailUrl,title,price) {
                $rootScope.addToCart({
                    amount: singlePrice,
                    yungou: yungouId,
                    product: {
                        thumbnailUrl: thumbnailUrl,
                        title: title,
                        price : price
                    },
                });
            }
            var count = 0;
            function getOrdering (limit) {
                $http.get('/order/ordering?limit='+limit)
                .success(function (data) {
                    if (data.code == 200) {
                        var objArr = data.data;
                        if (objArr.length == 0) return;
                        var createdTime;
                        for (var i = objArr.length - 1; i >= 0; i--) {
                            createdTime = objArr[i]['createdTime'].split(" ")[0].split("-");
                            createdTime = createdTime[1]+"月"+createdTime[2]+"日";
                            objArr[i]['createdTime'] = createdTime;
                            if (!objArr[i]['avatorUrl'] || typeof objArr[i]['avatorUrl'] == "undefined" || objArr[i]['avatorUrl'] == "") {
                                objArr[i]['avatorUrl'] = "http://mimg.127.net/p/yy/lib/img/avatar/40.jpeg";
                            }
                        }
                        count++;
                        if (limit == 1) {
                            var check = false;
                            for (var i=0;i<$scope.ordering.length;i++) {
                                if (objArr[0]['id'] == $scope.ordering[i]['id'])
                                    check = true;
                            }

                            if (!check) {
                                $scope.ordering.push(objArr[0]);
                            }
                        } else {
                            $scope.ordering = objArr;
                            $scope.ordering.reverse();
                        }
                        if ($scope.ordering.length > 8) {
                            $("#pro-view-10").animate({
                                marginTop : -96+"px"
                            },500,function () {
                                $("#pro-view-10").find("li:first-child").remove();
                                $scope.ordering.splice(0,1);
                                $("#pro-view-10").css("margin-top",0);

                            });
                        }
                    }
                })
                .error(function (data) {
                    console.log(data);
                });
            }
            getOrdering(8);
            $interval(function () {
                getOrdering(1);
            },5000);
        });
    </script>

</body>

</html>