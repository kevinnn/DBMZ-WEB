<div class="m-user-comm-wraper" ng-show="currentView === 'recordOtherBuy'" ng-cloak>
    <div class="m-user-duobao">
        <div module="user/duobao/DuobaoOther" class="m-user-comm-cont m-user-duobaoOther" id="pro-view-12" module-id="module-9" module-launched="true">
            <div class="m-user-comm-title">
                <div class="m-user-comm-navLandscape">
                    <span class="title">夺宝记录</span>
                </div>
            </div>
            <div pro="container">
                <div class="listCont" style="display: block;">
                    <div id="pro-view-19">
                        <table class="w-table m-user-comm-table" pro="listHead">
                            <thead>
                                <tr>
                                    <th class="col-info">商品信息</th><th class="col-period">期号</th>
                                    <th class="col-joinNum">参与人次</th>
                                    <th class="col-status">夺宝状态</th>
                                    <th class="col-opt">操作</th>
                                </tr>
                            </thead>
                        </table>
                        <div pro="duobaoList" class="duobaoList">
                            <?php include './components/loading.php';?>
                            <table class="m-user-comm-table" ng-hide="isLoading || record === []" ng-repeat = "item in record">
                                <tbody>
                                    <tr ng-class="{'getWin': item.isWin === '1'}">
                                        <td class="col-info">
                                            <div class="w-goods w-goods-l w-goods-hasLeftPic">
                                                <div class="w-goods-pic">
                                                    <a target="_blank" href="/detail/{{item.productId}}-{{item.term}}.html">
                                                        <img ng-src="{{item.thumbnailUrl}}" alt="{{item.title}}" width="74" height="74">
                                                    </a>
                                                    <b class="ico ico-winner" ng-show="item.isWin === '1'"></b>
                                                </div>
                                                <p class="w-goods-title f-txtabb">
                                                    <a title="{{item.title}}" target="_blank" href="/detail/{{item.productId}}-{{item.term}}.html">{{item.title}}</a>
                                                </p>
                                                <p class="w-goods-price">总需：{{item.price}}人次</p>
                                                <div class="w-progressBar" ng-hide="item.status === '2'">
                                                    <p class="w-progressBar-wrap">
                                                        <span class="w-progressBar-bar" style="width:{{Math.floor((item.saleCount) / (item.price) * 100)}}%"></span>
                                                    </p>
                                                    <ul class="w-progressBar-txt f-clear">
                                                        <li class="w-progressBar-txt-l">已完成{{Math.floor((item.saleCount) / (item.price) * 100)}}%，剩余<span class="txt-residue">{{item.price - item.saleCount}}</span></li>
                                                    </ul>
                                                </div>
                                                <div class="winner" ng-show="item.status === '2'">
                                                    <div class="name">获得者：<a href="/user/{{item.winUser.userId}}.html" title="{{item.winUser.userName}}">{{item.winUser.userName}}</a>（本期参与<strong class="txt-dark">{{item.winUser.count}}</strong>人次）</div>
                                                    <div class="code">幸运代码：<strong class="txt-impt">{{item.winUser.result}}</strong></div>
                                                    <div class="time">揭晓时间：{{item.endTime + " 000"}}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="col-period">{{item.term}}</td>
                                        <td class="col-joinNum">{{item.count}}人次</td>
                                        <td class="col-status">
                                            <span ng-show="item.status === '0'">正在进行</span>
                                            <span ng-show="item.status === '1'">即将揭晓</span>
                                            <span ng-show="item.status === '2'">已揭晓</span>
                                        </td>
                                        <td class="col-opt" ng-show="item.status === '0'">
                                            <a class="w-button w-button-main" href="/detail/{{item.term}}.html" target="_blank" style="margin-left: 16px;"><span>跟 买</span></a>
                                            <p><a href="#number-modal" ng-click="viewNumber($index)" style="padding-left: 34px;">查看</a></p>
                                        </td>
                                        <td class="col-opt" ng-hide="item.status === '0'">

                                            <a class="w-button w-button-main" href="/detail/{{item.productId}}.html" target="_blank" ng-show="item.isOn === '1'"><span>参与最新</span></a>
                                            <a class="w-button w-button-disabled w-button-main" href="" ng-show="item.isOn=== '0'"><span>暂无最新</span></a>
                                            <p><a href="#number-modal" ng-click="viewNumber($index)" style="padding-left: 34px;">查看</a></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="pager" ng-show="pageService.record.pageCount > 0">
                            <div class="w-pager">
                                <a href="#top">
                                    <button class="w-button w-button-aside" ng-class="{'w-button-disabled':pageService.record.page==1}" type="button" ng-disabled="pageService.record.page==1" ng-click="changePage(0)"><span>首页</span></button>
                                </a>
                                <a href="#top" ng-repeat="page in pageService.record.pageArr track by $index">
                                        <button class="w-button" ng-class="{'w-button-main':pageService.record.page==page}" type="button" ng-click="changePage($index)">
                                        <span>{{page}}</span>
                                    </button>
                                </a>
                                <a href="#top">
                                    <button class="w-button w-button-aside" type="button" ng-class="{'w-button-disabled':pageService.record.page==pageService.record.pageCount}" ng-disabled="pageService.record.page==pageService.record.pageCount" ng-click="changePage(pageService.record.pageCount - 1)"><span>末页</span></button>
                                </a>
                                <span class="w-pager-ellipsis">共{{pageService.record.pageCount}}页</span>
                            </div>
                        </div>
                        <div pro="limitTips" class="limitTips" style="display: block;">一年内共有
                            <span class="txt-impt">{{recordCount}}</span>条夺宝记录，仅可查看其最近50条记录~
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>