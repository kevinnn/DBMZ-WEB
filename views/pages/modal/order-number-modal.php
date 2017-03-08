<div class="w-msgbox w-msgbox-moduleCode" id="order-number-modal" style="top: 30%;display: none; animation: ngdialog-flyin .5s;" ng-cloak ng-hide="isLoading">
    <a pro="close" href="javascript:void(0);" class="w-msgbox-close">×</a>
    <div class="w-msgbox-hd" pro="header">我的夺宝号码</div>
    <div class="w-msgbox-bd" pro="entry">
        <div>
            <div class="w-duobaoCodeList" pro="codeWarper">
                <div class="w-duobaoCodeList-hd" pro="codeTitle">您本期总共参与了{{orders[lookNumberIndex].count}}人次</div>
                <div pro="list" class="w-duobaoCodeList-list">
                    <dl class="iItem f-clear">
                        <dt class="iItemTime">{{orders[lookNumberIndex].buyTime}}</dt>
                        <dt><span class="iCodeItem" ng-repeat="i in [] | range: (orders[lookNumberIndex].numberEnd - orders[lookNumberIndex].numberStart + 1)">{{$index + (orders[lookNumberIndex].numberStart-0)}}</span> </dt>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>