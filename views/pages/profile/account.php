<div ng-cloak>
    <div class="c_main_box" ng-show="currentView==='account'">
        <ul class="c_main_income">
            <li id="c_money_type">
                <div class="c_money_type">可用余额<!--<i></i>--></div>
                <span id="accountUsableMoney"><?php echo $user['balance'] ;?></span>
            </li>
            <div class="c_right_line"></div>
            
            <li class="c_income">
                <div class="c_money_type">积分</div>
                <span style="color:#f57403;" id="scoreBalance"><?php echo $user['credits'] ;?></span>
            </li>
            <div class="c_right_line"></div>
            <li>
                <a href="/recharge" target="_blank" class="c_main_recharge">充值</a>
            </li>
            
            <div class="c_clear"></div>
        </ul>
    </div>
    <div class="m-user-frame-content-m" ng-show="currentView==='account'">
        <div class="m-user-index-duobaoRecord">
            <div class="m-user-comm-title m-user-comm-titleHasBdr">
                <a class="ext" href="#" ng-click="changeView('recordBuy')">查看更多记录</a>
                <h3 class="title">我最近的夺宝</h3>
            </div>
            <!-- 夺宝记录 和 我的用户 页面显示 -->
            <!-- 商品信息 -->
            <div class="m-user-duobao">
                <div id="pro-view-26">
                    <table class="w-table m-user-comm-table" pro="listHead">
                        <thead>
                            <tr>
                                <th class="col-info">商品信息</th>
                                <th class="col-period">期号</th>
                                <th class="col-joinNum">参与人次</th>
                                <th class="col-opt">操作</th>
                            </tr>
                        </thead>
                    </table>
                    <div pro="duobaoList" class="duobaoList duobaoList-simple">
                        <table class="m-user-comm-table" ng-repeat="item in accountRecord" on-finish-render="ngRepeatFinished"  ng-hide="isLoading">
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
                                            
                                            <div class="w-progressBar" ng-hide="item.status ==='2'">
                                                <p class="w-progressBar-wrap">
                                                    <span class="w-progressBar-bar" style="width:{{Math.floor(item.saleCount / item.price * 100)}}%"></span>
                                                </p>
                                                <ul class="w-progressBar-txt f-clear">
                                                    <li class="w-progressBar-txt-l">已完成{{Math.floor(item.saleCount / item.price * 100)}}%，剩余<span class="txt-residue">{{item.price - item.saleCount}}</span></li>
                                                </ul>
                                            </div>
                                            <div class="winner" ng-show="item.status === '2'">
                                                <div class="name">获得者：<a href="/user/xx.html" title="{{item.winUser.userName}}">{{item.winUser.userName}}</a>（本期参与<strong class="txt-dark">{{item.winUser.count}}</strong>人次）</div>
                                                <div class="code">幸运代码：<strong class="txt-impt">{{item.winUser.result}}</strong></div>
                                                <div class="time">揭晓时间：{{item.endTime + " 000"}}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-period">{{item.term}}</td>
                                    <td class="col-joinNum">{{item.count}}人次</td>
                                    <td class="col-opt" ng-show="item.status === '0'">
                                        <a class="w-button w-button-main" href="/detail/{{item.productId}}-{{item.term}}.html" target="_blank"><span>追加人次</span></a>
                                        <p><a href="#number-modal" ng-click="viewNumber($index)" style="padding-left: 30px;">查看</a></p>
                                    </td>
                                    <td class="col-opt" ng-hide="item.status === '0'">
                                        <a class="w-button w-button-main" href="/detail/{{item.productId}}.html" target="_blank" ng-show="item.isOn === '1'"><span>参与最新</span></a>
                                        <a class="w-button w-button-disabled w-button-main" href="" ng-show="item.isOn=== '0'"><span>暂无最新</span></a>
                                        <p><a href="#number-modal" ng-click="viewNumber($index)" style="padding-left: 30px;">查看</a></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php include './components/loading.php';?>
                    </div>
                    <div pro="pager" class="pager"></div>
                    <div pro="limitTips" class="limitTips" style="display:none"></div>
                </div>
            </div>
            <div class="lineWhite"></div>
        </div>
    </div>
</div>