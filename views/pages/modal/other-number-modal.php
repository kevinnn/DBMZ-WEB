<div class="w-msgbox w-msgbox-moduleCode" name="number-modal" style="top: 30%;display: none; animation: ngdialog-flyin .5s;" ng-cloak ng-hide="isLoading">
    <a pro="close" href="javascript:void(0);" class="w-msgbox-close">×</a>
    <div class="w-msgbox-hd" pro="header">Ta的夺宝号码</div>
    <div class="w-msgbox-bd" pro="entry">
        <div>
            <div class="w-duobaoCodeList" pro="codeWarper">
                <div class="w-duobaoCodeList-hd" pro="codeTitle">Ta本期总共参与了{{record[lookNumberIndex].count}}人次</div>
                <div pro="list" class="w-duobaoCodeList-list">
                    <dl class="iItem f-clear" ng-repeat="item in record[lookNumberIndex].numbers">
                        <dt class="iItemTime">{{item.buyTime}}</dt>
                        <dt><span class="iCodeItem" ng-class="{'txt-impt': $index+item.numberStart == record[lookNumberIndex].winUser.result}" ng-repeat="i in [] | range: (item.numberEnd - item.numberStart + 1)">{{$index + item.numberStart}}</span></dt>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>