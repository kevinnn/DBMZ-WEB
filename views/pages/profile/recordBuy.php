<div ng-cloak>
    <div class="m-user-frame-content-m" ng-show="currentView==='recordBuy'">
        <div class="m-user-index-duobaoRecord">
            <!-- 夺宝记录页面显示 -->
            <div class="m-user-comm-title">
                <div class="m-user-comm-navLandscape">
                    <a class="i-item i-item-active" ng-class="{'i-item-active': selectRecordStatus === 0}" href="javascript:void(0)" ng-click="changeRecordStatus(0)">参与成功 <span pro="tagNum_join"></span></a>
                    <span class="i-sptln">|</span>
                    <a class="i-item" ng-class="{'i-item-active': selectRecordStatus === 1}" href="javascript:void(0)" ng-click="changeRecordStatus(1)">正在进行 <span class="txt-impt" >{{recordCount[1]}}</span></a>
                    <span class="i-sptln">|</span>
                    <a class="i-item" ng-class="{'i-item-active': selectRecordStatus === 2}" href="javascript:void(0)" ng-click="changeRecordStatus(2)">即将揭晓 <span class="txt-impt" >{{recordCount[2]}}</span></a>
                    <span class="i-sptln">|</span>
                    <a class="i-item" ng-class="{'i-item-active': selectRecordStatus === 3}" href="javascript:void(0)" ng-click="changeRecordStatus(3)">已揭晓 <span class="txt-impt" >{{recordCount[3]}}</span></a>
                </div>
                <!--                 <div class="w-select m-user-comm-selectTitle" id="oneYear" style="display: none;">
                    <span pro="text">1年内</span><i class="w-select-arr">▼</i>
                </div>
                <div class="w-select m-user-comm-selectTitle" id="threeMonths">
                    <span pro="text">最近3个月</span><i class="w-select-arr">▼</i>
                </div>
                <div class="w-select m-user-comm-selectTitle" id="thirtyDays" style="display: none;">
                    <span pro="text">最近30天</span><i class="w-select-arr">▼</i>
                </div>
                <div class="w-select m-user-comm-selectTitle" id="SevenDays" style="display: none;">
                    <span pro="text">最近7天</span><i class="w-select-arr">▼</i>
                </div>
                <div class="w-select m-user-comm-selectTitle" id="today" style="display: none;">
                    <span pro="text">今天</span><i class="w-select-arr">▼</i>
                </div> -->
            </div>
            <!-- 夺宝记录 和 我的用户 页面显示 -->
            <!-- 商品信息 -->
            <div class="m-user-duobao">
                <div>
                    <table class="w-table m-user-comm-table" pro="listHead">
                        <thead>
                            <tr>
                                <th class="col-info">商品信息</th>
                                <th class="col-period">期号</th>
                                <th class="col-joinNum">参与人次</th>
                                <th class="col-status">夺宝状态</th>
                                <th class="col-opt">操作</th>
                            </tr>
                        </thead>
                    </table>
                    <div pro="duobaoList" class="duobaoList duobaoList-simple">
                        <table class="m-user-comm-table" ng-repeat="item in record | filter: filterKey as filtered" ng-hide='isLoading || record.length === 0'>
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
                                                    <span class="w-progressBar-bar" style="width:{{Math.floor(item.saleCount / item.price * 100) || 0}}%"></span>
                                                </p>
                                                <ul class="w-progressBar-txt f-clear">
                                                    <li class="w-progressBar-txt-l">已完成{{Math.floor(item.saleCount / item.price * 100) || 0}}%，剩余<span class="txt-residue">{{item.price - item.saleCount}}</span></li>
                                                </ul>
                                            </div>
                                            <div class="winner" ng-show="item.status === '2'">
                                                <div class="name">获得者：<a href="/user/xx.html" title="{{item.winUser.userName}}">{{item.winUser.userName}}</a>（本期参与<strong class="txt-dark">{{item.winUser.count}}</strong>人次）</div>
                                                <div class="code">幸运代码：<strong class="txt-impt">{{item.winUser.result}}</strong></div>
                                                <div class="time">揭晓时间：{{item.endTime + " 000"}}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-period" >{{item.term}}</td>
                                    <td class="col-joinNum">{{item.count}}人次</td>
                                    <td class="col-status">
                                        <span class="txt-suc" ng-show="item.status === '0'">正在进行</span>
                                        <span class="txt-blue" ng-show="item.status === '1'">即将揭晓</span>
                                        <span ng-show="item.status === '2'">已揭晓</span>
                                    </td>
                                    <td class="col-opt" ng-show="item.status === '0'">
                                        <a class="w-button w-button-main" href="/detail/{{item.term}}.html" target="_blank" style="margin:0 0;"><span>追加人次</span></a>
                                        <p><a href="#number-modal" ng-click="viewNumber($index)">查看夺宝号码</a></p>
                                    </td>
                                    <td class="col-opt" ng-hide="item.status === '0'">
                                        <a class="w-button w-button-main" href="/detail/{{item.productId}}.html" target="_blank" ng-show="item.isOn === '1'" style="margin:0 0;"><span>参与最新</span></a>
                                        <a class="w-button w-button-disabled w-button-main" href="" ng-show="item.isOn==='0'" style="margin:0 0;"><span>暂无最新</span></a>
                                        <p><a href="#number-modal" ng-click="viewNumber($index)">查看夺宝号码</a></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="m-user-comm-empty" ng-hide="isLoading || record.length !== 0">
                            <div class="i-desc">这里您还没有记录哦！</div>
                        </div>
                        <?php include './components/loading.php';?>
                    </div>
                    <div class="pager" ng-show="pageService.recordBuy.pageCount > 0">
                        <div class="w-pager">
                            <a href="#top">
                                <button class="w-button w-button-aside" ng-class="{'w-button-disabled':pageService.recordBuy.page==1}" type="button" ng-disabled="pageService.recordBuy.page==1" ng-click="changePage(1)" href="#top"><span>首页</span></button>
                            </a>
                            <a href="#top">
                                <button class="w-button" ng-class="{'w-button-main':pageService.recordBuy.page==page}" type="button" ng-repeat="page in pageService.recordBuy.pageArr track by $index" ng-click="changePage($index)" href="#top"><span>{{page}}</span></button>
                            </a>
                            <a href="#top">
                                <button class="w-button w-button-aside" type="button" ng-class="{'w-button-disabled':pageService.recordBuy.page==pageService.recordBuy.pageCount}" ng-disabled="pageService.recordBuy.page==pageService.recordBuy.pageCount" ng-click="changePage(pageService.recordBuy.pageCount)" href="#top"><span>末页</span></button>
                            </a>
                            <span class="w-pager-ellipsis">共{{pageService.recordBuy.pageCount}}页</span>
                        </div>
                    </div>
                    <div pro="limitTips" class="limitTips" style="display:none"></div>
                </div>
            </div>
            <div class="lineWhite"></div>
        </div>
    </div>
</div>
<!-- 夺宝记录  最近条 -->
<!-- <div class="w-menu" tabindex="0" id="pro-view-71" style="z-index: 1000; display: none;">
    <div class="w-recordBuy-menu-item w-menu-item" ng-click="clickRecordBuyMenuItem(1)">今天</div>
    <div class="w-recordBuy-menu-item w-menu-item" ng-click="clickRecordBuyMenuItem(7)">最近7天</div>
    <div class="w-recordBuy-menu-item w-menu-item" ng-click="clickRecordBuyMenuItem(30)">最近30天</div>
    <div class="w-recordBuy-menu-item w-menu-item w-menu-item-hover" ng-click="clickRecordBuyMenuItem(92)">最近3个月</div>
    <div class="w-recordBuy-menu-item w-menu-item" tabindex="0" ng-click="clickRecordBuyMenuItem(365)">1年内</div>
</div> -->