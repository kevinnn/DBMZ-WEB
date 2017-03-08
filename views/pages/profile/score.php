<div ng-cloak>
    <div class="profile-score" ng-show="currentView==='credit'">
        <div class="m-user-duobao">
            <div>
                <table class="w-table m-user-comm-table">
                    <thead>
                        <tr>
                            <th class="col-period">获得时间</th>
                            <th class="col-joinNum">获得积分</th>
                            <th class="col-status">渠道来源</th>
                        </tr>
                    </thead>
                </table>
                <?php include './components/loading.php';?>
                <table class="m-user-comm-table" ng-repeat="item in creditContainer" >
                    <tbody>
                        <tr>
                            <td class="col-period" >{{item.time}}</td>
                            <td class="col-joinNum">{{item.amount}}</td>
                            <td class="col-status" ng-show="item.type == 1">签到赠送</td>
                            <td class="col-status" ng-show="item.type == 2">购买返还</td>
                            <td class="col-status" ng-show="item.type == 3">邀请码</td>
                        </tr>
                    </tbody>
                </table>
                <div class="duobaoList duobaoList-simple">
                </div>
                <div pro="pager" class="pager"></div>
                <div pro="limitTips" class="limitTips" style="display:none"></div>
                <div class="lineWhite"></div>
            </div>
        </div>
    </div>
</div>