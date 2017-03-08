<div class="m-win-user m-win" ng-cloak>
    <div class="m-user-comm-wraper" id="pro-view-11" ng-show="currentView==='recordOtherWin'">
        <div class="m-user-comm-title m-user-comm-titleHasBdr" ng-cloak>
            <h2 class="title">幸运记录
            <span class="desc txt-gray">共有 <strong pro="total">{{recordWinCount}}</strong> 条幸运记录~</span>
            </h2>
        </div>
        <?php include './components/loading.php';?>
        <div class="w-goods" ng-repeat="item in recordWin" ng-hide="recordWinCount === 0 || isLoading">
            <div class="w-goods-pic">
                <a title="{{item.title}}" target="_blank" href="/detail/{{item.productId}}-{{item.term}}.html">
                <img ng-src="{{item.thumbnailUrl}}" alt="{{item.title}}"></a>
            </div>
            <div class="w-goods-content">
                <p class="w-goods-title">
                    <a title="{{item.title}}" target="_blank" href="/detail/{{item.productId}}-{{item.term}}.html">{{item.title}}</a>
                </p>
                <p class="w-goods-price">期号：{{item.term}}</p>
                <p class="w-goods-price">总需：{{item.price}}人次</p>
                <p class="w-goods-info">幸运号码：<strong class="txt-impt">{{item.result}}</strong></p>
                <p class="w-goods-info">总共参与：<strong class="txt-dark">{{item.count}}</strong>人次</p>
                <p class="w-goods-info">揭晓时间：{{item.endTime}}.000</p>
            </div>
        </div>
        <div class="pager" ng-show="pageService.recordWin.pageCount > 0">
            <div class="w-pager">
                <a href="#top">
                    <button class="w-button w-button-aside" ng-class="{'w-button-disabled':pageService.recordWin.page==1}" type="button" ng-disabled="pageService.recordWin.page==1" ng-click="changePage(0)"><span>首页</span></button>
                </a>
                <a href="#top" ng-repeat="page in pageService.recordWin.pageArr track by $index">
                    <button class="w-button" ng-class="{'w-button-main':pageService.recordWin.page==page}" type="button" ng-click="changePage($index)">
                    <span>{{page}}</span>
                    </button>
                </a>
                <a href="#top">
                    <button class="w-button w-button-aside" type="button" ng-class="{'w-button-disabled':pageService.recordWin.page==pageService.recordWin.pageCount}" ng-disabled="pageService.recordWin.page==pageService.recordWin.pageCount" ng-click="changePage(pageService.recordWin.pageCount - 1)"><span>末页</span></button>
                </a>
                <span class="w-pager-ellipsis">共{{pageService.recordWin.pageCount}}页</span>
            </div>
        </div>
    </div>
</div>