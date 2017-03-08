<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <?php include '../components/head.php';?>
        <title>ResultsTODO</title>
        <meta name="description" content="TODO">
        <meta name="keywords" content="TODO">
    </head>
    <body ng-app="YYYG" ng-cloak>
        <?php include '../components/header.php';?>
        <div class="results-page" ng-controller="resultsController" >
            <div class="m-results" >
                <div class="g-wrap f-clear">
                    <div class="g-main m-results-revealList">
                        <div class="m-results-mod-hd">
                            <h3>最近三天揭晓的所有商品</h3>
                        </div>
                        <div class="m-results-mod-bd" >
                            <ul class="w-revealList f-clear" scroll="getFastStart()" id="page">
                                <div ng-repeat="page in pages">
                                <li class="w-revealList-item" ng-repeat="item in remainItems[page] track by $index">
                                    <div class="w-goods w-goods-reveal">
                                        <div class="w-goods-info">
                                            <div class="w-goods-pic">
                                                <a href="/detail/{{item.productId}}-{{item.term}}.html" target="_blank">
                                                    <img width = "200" height="200" ng-src="{{item.thumbnailUrl}}" onerror="this.src='../../public/img/app/loading.png'">
                                                </a>
                                            </div>
                                            <p class="w-goods-title f-txtabb">
                                                <a href="/detail/{{item.productId}}-{{item.term}}.html" target="_blank">
                                                    {{item.title}}
                                                </a>
                                            </p>
                                            <p class="w-goods-price">
                                                总需人次{{item.price}}
                                            </p>
                                            <p class="w-goods-periods">期号：{{item.term}}</p>
                                        </div>
                                        <div class="w-countdown">
                                            <p class="w-countdown-title">
                                                <i class="ico ico-countdown ico-countdown-gray"></i>揭晓倒计时
                                            </p>
                                            <p class="w-countdown-nums" pro="countdown">
                                                <b>{{Math.floor(intervalService[page].time[$index][0] / 10 % 10) < 0 ? 0 : Math.floor(intervalService[page].time[$index][0] / 10 % 10)}}</b>
                                                <b>{{intervalService[page].time[$index][0] % 10 < 0 ? 0 : intervalService[page].time[$index][0] % 10}}</b>:
                                                <b>{{Math.floor(intervalService[page].time[$index][1] / 10 % 10) < 0 ? 0 : Math.floor(intervalService[page].time[$index][1] / 10 % 10)}}</b>
                                                <b>{{intervalService[page].time[$index][1] % 10 < 0 ? 0 : intervalService[page].time[$index][1] % 10}}</b>:
                                                <b>{{Math.floor(intervalService[page].time[$index][2] / 10 % 10) < 0 ? 0 : Math.floor(intervalService[page].time[$index][2] / 10 % 10)}}</b>
                                                <b>{{intervalService[page].time[$index][2] % 10 < 0 ? 0 : intervalService[page].time[$index][2] % 10}}</b>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                </div>
                                <div ng-repeat="finishedItem in finishedItems track by $index">
                                <li class="w-revealList-item" ng-repeat="item in finishedItem">
                                    <div class="w-goods w-goods-reveal">
                                        <div class="w-goods-info">
                                            <div class="w-goods-pic">
                                                <a href="/detail/{{item.productId}}-{{item.term}}.html" target="_blank">
                                                    <img width="200" height="200" ng-src="{{item.thumbnailUrl}}" onerror="this.src='../../public/img/app/loading.png'">
                                                </a>
                                            </div>
                                            <p class="w-goods-title f-txtabb">
                                                <a href="/detail/{{item.productId}}-{{item.term}}.html" target="_blank">
                                                    {{item.title}}
                                                </a>
                                            </p>
                                            <p class="w-goods-price">总需：{{item.price}}人次</p>
                                            <p class="w-goods-period">期号：{{item.term}}</p>
                                        </div>
                                        <div class="w-record">
                                            <div class="w-record-avatar">
                                                <a href="/user/recordBuy" target="_blank">
                                                    <img ng-src="{{item.winUser.avatorUrl}}" width="40" height="40" onerror="this.src='http://mimg.127.net/p/yy/lib/img/avatar/90.jpeg'">
                                                </a>
                                            </div>
                                            <div class="w-record-detail">
                                                <p class="user f-breakword">恭喜
                                                    <a href="/user/{{item.winUser.userId}}.html" title="{{item.winUser.userName}}(ID:{{item.winUser.userId}})">{{item.winUser.userName}}</a>
                                                    <span class="txt-green">({{item.winUser.loginArea}})</span>
                                                获得该商品</p>
                                                <p>幸运号码：<b class="txt-red">{{item.winUser.result}}</b></p>
                                                <p>本期参与：<b class="txt-red">{{item.winUser.count}}</b>人次</p>
                                                <p>揭晓时间：<span>{{item.endTime}}</span></p>
                                                <p><a class="w-button w-button-simple" href="/detail/{{item.productId}}-304093443.html" target="_blank">查看详情</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <!-- 最快揭晓 -->
                    <div class="g-side">
                        <div class="m-results-leastRemain">
                            <div class="m-results-leastRemain-title">
                                <h4>最快揭晓</h4>
                            </div>
                            <div class="m-results-leastRemain-title-ft">
                            </div>
                            <div class="m-results-leastRemain-main">
                                <ul class="w-remainList">
                                    <li class="w-remainList-item" ng-repeat="item in revealListRevealItems">
                                        <div class="w-goods w-goods-ing">
                                            <div class="w-goods-pic">
                                                <a href="/detail/{{item.productId}}-{{item.term}}.html" title="{{item.title}}" target="_blank">
                                                    <img ng-src="{{item.thumbnailUrl}}" alt="{{item.title}}" width="200" height="200" onerror="this.src='../../public/img/app/loading.png'">
                                                </a>
                                            </div>
                                            <p class="w-goods-title f-txtabb">
                                                <a href="/detail/{{item.productId}}-{{item.term}}.html" title="{{item.title}}" target="_blank">
                                                    {{item.title}}
                                                </a>
                                            </p>
                                            <p class="w-goods-price">
                                                总需：{{item.price}} 人次
                                            </p>
                                            <div class="w-progressBar" title="{{$eval('Math.floor(item.saleCount / item.price * 100)') + '%'}}">
                                                <p class="w-progressBar-wrap">
                                                    <span class="w-progressBar-bar" style="width:{{Math.floor(item.saleCount / item.price * 100) + '%'}}"></span>
                                                </p>
                                                <ul class="w-progressBar-txt f-clear">
                                                    <li class="w-progressBar-txt-l"><p><b>{{item.saleCount}}</b></p><p>已参与人次</p></li>
                                                    <li class="w-progressBar-txt-r"><p><b>{{item.price - item.saleCount}}</b></p><p>剩余人次</p></li>
                                                </ul>
                                            </div>
                                            <p class="w-goods-progressHint">
                                                <b class="txt-blue">{{item.saleCount}}</b>人次已参与，赶快去参加吧！剩余<b class="txt-red">{{item.price - item.saleCount}}</b>人次
                                            </p>
                                            <div class="w-goods-opr">
                                                <a class="w-button w-button-main w-button-l w-goods-buyRemain" href="/detail/{{item.productId}}-{{item.term}}.html" style="width:70px;" target="_blank">我来包尾</a>
                                            </div>
                                        </div>
                                    </li >
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../components/footer.php';?>
        <script type="text/javascript" src="../../public/js/app.js"></script>
        <script type="text/javascript" src="../../public/js/pages/results.js"></script>
    </body>
</html>