<div ng-cloak>
    <div class="profile-balance" ng-show="currentView==='balance'">
        <div class="m-user-duobao">
            <div>
                <table class="w-table m-user-comm-table" pro="listHead">
                    <thead>
                        <tr>
                            <th class="col-period">时间</th>
                            <th class="col-joinNum">变动方式</th>
                            <th class="col-status">余额变动</th>
                        </tr>
                    </thead>
                </table>
                <table class="m-user-comm-table">
                    <tbody>
                        <tr>
                            <td class="col-period" >2016-04-17 10:29:48</td>
                            <td class="col-joinNum">微信支付</td>
                            <td class="col-status">10元</td>
                        </tr>
                    </tbody>
                </table>
                <table class="m-user-comm-table">
                    <tbody>
                        <tr>
                            <td class="col-period" >2016-04-17 10:29:48</td>
                            <td class="col-joinNum">微信支付</td>
                            <td class="col-status">120元</td>
                        </tr>
                    </tbody>
                </table>
                <table class="m-user-comm-table">
                    <tbody>
                        <tr>
                            <td class="col-period" >2016-04-17 10:29:48</td>
                            <td class="col-joinNum">微信支付</td>
                            <td class="col-status">100元</td>
                        </tr>
                    </tbody>
                </table>
                <div pro="duobaoList" class="duobaoList duobaoList-simple">
                </div>
                <div pro="pager" class="pager"></div>
                <div pro="limitTips" class="limitTips" style="display:none"></div>
                <div class="lineWhite"></div>
            </div>
        </div>
    </div>
</div>