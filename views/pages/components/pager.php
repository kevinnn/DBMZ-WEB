<div class="pager" id="history" ng-show="pageService.history.pageCount > 0">
    <div class="w-pager">
        
        <button class="w-button w-button-aside" ng-class="{'w-button-disabled':pageService.history.page==1}" type="button" ng-disabled="pageService.history.page==1" ng-click="history(1)"><span>首页</span></button>
        <button class="w-button" ng-class="{'w-button-main':pageService.history.page==page}" type="button" ng-repeat="page in pageService.history.pageArr track by $index"><span>{{page}}</span></button>
        
        <button class="w-button w-button-aside" type="button" ng-class="{'w-button-disabled':pageService.history.page==pageService.history.pageCount}" ng-disabled="pageService.history.page==pageService.history.pageCount" ng-click="history(pageService.history.pageCount)"><span>末页</span></button>
        
        <span class="w-pager-ellipsis">共{{pageService.history.pageCount}}页</span>
    </div>
</div>