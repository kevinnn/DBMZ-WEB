<div ng-cloak>
    <div class="m-win m-win-myself" ng-show="currentView==='recordWin'">
        <div class="m-user-comm-wraper" >
            <div pro="cont" class="m-user-comm-cont">
                <div class="m-win-hd">
                    <div class="col info">商品信息</div>
                    <div class="col status">商品状态</div>
                    <div class="col opt">操作</div>
                </div>
                <?php include './components/loading.php';?>
                <div class="m-win-bd" pro="list" ng-repeat="item in recordWin">
                    <div class="w-goods" >
                        <div class="col info">
                            <div class="w-goods-pic">
                                <a title="{{item.title}}" target="_blank" href="/detail/{{item.productId}}.html">
                                    <img style="width: 120px; height: 120px;" ng-src="{{item.thumbnailUrl}}" alt="{{item.title}}">
                                </a>
                            </div>
                            <div class="w-goods-content">
                                <p class="w-goods-title">
                                    <a title="{{item.title}}" target="_blank" href="/detail/{{item.productId}}.html">{{item.title}}</a>
                                </p>
                                <p class="w-goods-price">期号：{{item.term}}</p>
                                <p class="w-goods-price">总需：{{item.price}}人次</p>
                                <p class="w-goods-info">幸运号码：<strong class="txt-impt">{{item.result}}</strong>，总共参与了<strong class="txt-dark">{{item.count}}</strong>人次</p>
                                <p class="buyTime">夺宝时间：{{item.winUser.buyTime}}</p>
                                <p class="calcTime">揭晓时间：{{item.endTime}}</p>
                            </div>
                        </div>
                        <div class="col status">
                            <span class="txt-suc" ng-show="item.logisticsStatus == 0">未填写收货地址</span>
                            <span class="txt-blue" ng-show="item.logisticsStatus == 1">未发货</span>
                            <span class="txt-green" ng-show="item.logisticsStatus == 2">已发货</span>
                            <span class="txt-gray" ng-show="item.logisticsStatus == 3">已签收</span>
                            <span class="txt-gray" ng-show="item.logisticsStatus == 4">已晒单</span>
                        </div>
                        <div class="col opt">
                            <p><a href="/profile/winDetail.html?winOrderId={{item.winOrderId}}" target="_blank" ng-show="item.logisticsStatus == 0">填写收货地址</a></p>
                            <p><a href="/profile/winDetail_finish.html?winOrderId={{item.winOrderId}}" target="_blank" ng-show="item.logisticsStatus == 1">查看详情</a></p>
                            <p><a href="/profile/winDetail_finish.html?winOrderId={{item.winOrderId}}" target="_blank" ng-show="item.logisticsStatus == 2">去签收</a></p>
                            <p ng-show="item.logisticsStatus == 3">未晒单</p>
                            <p><a href="" class="w-button w-button-main w-button-s" ng-show="item.logisticsStatus == 3" ng-click="changeView('shareEdit', $index)"><span>晒单</span></a></p>
                            <p><a href="/profile/winDetail_finish.html?winOrderId={{item.winOrderId}}" target="_blank" ng-show="item.logisticsStatus == 4">详情</a></p>
                        </div>
                    </div>
                </div>
                <div class="m-user-comm-empty" ng-hide="isLoading || recordWin.length !== 0">
                    <div class="i-desc">您还没有中奖记录哦！</div>
                </div>
            </div>
            <div class="pager" ng-show="pageService.recordWin.pageCount > 0">
                <div class="w-pager">
                    <a href="#top">
                        <button class="w-button w-button-aside" ng-class="{'w-button-disabled':pageService.recordWin.page==1}" type="button" ng-disabled="pageService.recordWin.page==1" ng-click="changePage(1)"><span>首页</span></button>
                    </a>
                    <a href="#top">
                        <button class="w-button" ng-class="{'w-button-main':pageService.recordWin.page==page}" type="button" ng-repeat="page in pageService.recordWin.pageArr track by $index" ng-click="changePage($index)"><span>{{page}}</span></button>
                    </a>
                    <a href="#top">
                        <button class="w-button w-button-aside" type="button" ng-class="{'w-button-disabled':pageService.recordWin.page==pageService.recordWin.pageCount}" ng-disabled="pageService.recordWin.page==pageService.recordWin.pageCount" ng-click="changePage(pageService.recordWin.pageCount)"><span>末页</span></button>
                    </a>
                    <span class="w-pager-ellipsis">共{{pageService.recordWin.pageCount}}页</span>
                </div>
            </div>
        </div>
    </div>
</div>