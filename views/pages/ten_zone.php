<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../components/head.php';?>
        <meta charset="UTF-8">
        <title>TenZoneTODO</title>
        <meta name="description" content="TODO">
        <meta name="keywords" content="TODO">
    </head>
    <body ng-app="YYYG">
        <?php include '../components/header.php';?>
        <div class="g-body">
            <div class="m-ten" ng-controller="tenZoneController">
                <div class="m-ten-slogan">
                    <div class="m-ten-slogan-wrap">
                        <div class="g-wrap">
                            <h2>任性，才有好运！—— 参与夺宝人次需是10的倍数！</h2>
                        </div>
                    </div>
                    <div class="m-ten-slogan-ft"></div>
                </div>
                <div class="g-wrap g-body-bd f-clear">
                    <div class="g-main">
                        <div class="m-ten-mod m-ten-allGoods">
                            <div class="w-hd">
                                <h3 class="w-hd-title">所有商品</h3>
                            </div>
                            <div class="m-ten-mod-bd" ng-cloak>
                                <ul class="w-goodsList f-clear">
                                    <li class="w-goodsList-item" ng-repeat="item in tenZone" ng-class="{row-first: $index % 4 === 0, row-last: $index % 4 === 3}">
                                        <i class="ico ico-label ico-label-ten"></i>
                                        <div class="w-goods w-goods-ing">
                                            <div class="w-goods-pic">
                                                <a href="/detail/{{item.product.id}}.html" title="{{item.product.title}}" target="_blank">
                                                    <img width="200" height="200" alt="{{item.product.title}}" src="{{item.product.thumbnailUrl}}" onerror="this.src='http://mimg.127.net/p/one/web/lib/img/products/l.png'" class="">
                                                </a>
                                            </div>
                                            <p class="w-goods-title f-txtabb"><a title="{{item.product.title}}" href="/detail/{{item.product.id}}.html" target="_blank">{{item.product.title}}</a></p>
                                            <p class="w-goods-price">总需：{{item.product.price}} 人次</p>
                                            <div class="w-progressBar" title="{{item.yungou.saleCount / item.product.price}}%">
                                                <p class="w-progressBar-wrap">
                                                    <span class="w-progressBar-bar" style="width:{{item.yungou.saleCount / item.product.price}}%;"></span>
                                                </p>
                                                <ul class="w-progressBar-txt f-clear">
                                                    <li class="w-progressBar-txt-l"><p><b>{{item.yungou.saleCount}}</b></p><p>已参与人次</p></li>
                                                    <li class="w-progressBar-txt-r"><p><b>{{item.product.price - item.yungou.saleCount}}</b></p><p>剩余人次</p></li>
                                                </ul>
                                            </div>
                                            <p class="w-goods-progressHint">
                                                <b class="txt-blue">{{item.yungou.saleCount}}</b>人次已参与，赶快去参加吧！剩余<b class="txt-red">{{item.product.price - item.yungou.saleCount}}}</b>人次
                                            </p>
                                            <div class="w-goods-opr">
                                                <a class="w-button w-button-main w-button-l w-goods-quickBuy" href="#" style="width:96px;" target="_blank">立即夺宝</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="w-goodsList-item w-goodsList-item-alt row-last">
                                        <img width="237" height="396" src="http://mimg.127.net/p/one/web/lib/img/common/alt_blank_ten_s.jpg">
                                    </li>
                                </ul>
                            </div>
                            <div class="w-pager">
                                <a href="#top">
                                    <button class="w-button w-button-aside" ng-class="{'w-button-disabled':pageService.tenZone.page==1}" type="button" ng-disabled="pageService.tenZone.page==1" ng-click="changePage(0)" href="#top"><span>首页</span></button>
                                </a>
                                <a href="#top">
                                    <button class="w-button" ng-class="{'w-button-main':pageService.tenZone.page==page}" type="button" ng-repeat="page in pageService.tenZone.pageArr track by $index" ng-click="changePage($index)" href="#top"><span>{{page}}</span></button>
                                </a>
                                <a href="#top">
                                    <button class="w-button w-button-aside" type="button" ng-class="{'w-button-disabled':pageService.tenZone.page==pageService.tenZone.pageCount}" ng-disabled="pageService.tenZone.page==pageService.tenZone.pageCount" ng-click="changePage(pageService.tenZone.pageCount)" href="#top"><span>末页</span></button>
                                </a>
                                <span class="w-pager-ellipsis">共{{pageService.tenZone.pageCount}}页</span>
                            </div>
                        </div>
                    </div>
                    <div class="g-side">
                        <div class="m-ten-mod m-ten-rule">
                            <div class="m-ten-mod-hd">
                                <h3>十元专区 规则说明</h3>
                            </div>
                            <div class="m-ten-mod-bd">
                                <ul class="m-ten-rule-list">
                                    <li><span class="txt-red index">1.</span> “十元专区”是指每次参与，人次必须是<b>十的倍数</b>；</li>
                                    <li><span class="txt-red index">2.</span> 十元专区分配的号码非连号，亦是随机分配；</li>
                                    <li><span class="txt-red index">3.</span> 幸运号码计算规则与一元夺宝商品相同。</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../components/footer.php';?>
        <script type="text/javascript" src="../../public/js/app.js"></script>
        <script type="text/javascript" src="../../public/js/pages/ten_zone.js"></script>
    </body>
</html>