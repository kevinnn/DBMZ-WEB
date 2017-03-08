<div ng-cloak>
    <div class="m-user-share-panel" ng-show="currentView==='expose'">
        <div class="m-user-share-totalRecords" style="display: block;">
            <span style="font-weight:bold;font-size:18px;margin-right:20px;">晒单
            </span>共有
            <span class="m-user-share-totalCnt">{{showRecordCount}}</span>条晒单~
        </div>
        <div class="m-user-share-list" style="display: block;">
            <div class="m-user-share-blank" ng-hide="isLoading || showRecord.length !== 0">还没有晒单记录~</div>
            <div id="ShareList">
                <?php include './components/loading.php';?>
                <div class="m-shareList" ng-hide="isLoading">
                    <div class="group" id="groupOne" >
                        <div class="item" ng-repeat="item in showRecord" ng-if="$index % 3 === 0">
                            <div class="pic">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td valign="middle" align="center">
                                                <a href="/showDetail?yungouId={{item.yungouId}}" target="_blank" title="{{item.title}}">
                                                    <img ng-src="{{item.imgUrls[0]}}" alt="{{item.title}}" width="270">
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="name">
                                <a href="/detail/{{item.productId}}.html">{{item.productTitle}}</a>
                            </div>
                            <div class="code">幸运号码：<strong class="txt-impt">{{item.result}}</strong>
                            </div>
                            <div class="post">
                                <div class="title">
                                    <a href="/showDetail?yungouId={{item.yungouId}}" target="_blank" target="_blank">
                                        <strong>{{item.title}}</strong>
                                    </a>
                                </div>
                                <div class="time">{{item.createdTime}}</div>
                                <div class="abbr">
                                    <p>{{item.content}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="group" id="groupTwo" >
                        <div class="item" ng-repeat="item in showRecord" ng-if="$index % 3 === 1">
                            <div class="pic">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td valign="middle" align="center">
                                                <a href="/showDetail?yungouId={{item.yungouId}}" target="_blank" title="{{item.title}}">
                                                    <img ng-src="{{item.imgUrls[0]}}" alt="{{item.title}}" width="270">
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="name">
                                <a href="/detail/{{item.productId}}.html">{{item.productTitle}}</a>
                            </div>
                            <div class="code">幸运号码：<strong class="txt-impt">{{item.result}}</strong>
                            </div>
                            <div class="post">
                                <div class="title">
                                    <a href="/showDetail?yungouId={{item.yungouId}}" target="_blank" target="_blank">
                                        <strong>{{item.title}}</strong>
                                    </a>
                                </div>
                                <div class="time">{{item.createdTime}}</div>
                                <div class="abbr">
                                    <p>{{item.content}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="group" id="groupThree" >
                        <div class="item" ng-repeat="item in showRecord" ng-if="$index % 3 === 2">
                            <div class="pic">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td valign="middle" align="center">
                                                <a href="/showDetail?yungouId={{item.yungouId}}" target="_blank" title="{{item.title}}">
                                                    <img ng-src="{{item.imgUrls[0]}}" alt="{{item.title}}" width="270">
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="name">
                                <a href="/detail/{{item.productId}}.html">{{item.productTitle}}</a>
                            </div>
                            <div class="code">幸运号码：<strong class="txt-impt">{{item.result}}</strong>
                            </div>
                            <div class="post">
                                <div class="title">
                                    <a href="/showDetail?yungouId={{item.yungouId}}" target="_blank" target="_blank">
                                        <strong>{{item.title}}</strong>
                                    </a>
                                </div>
                                <div class="time">{{item.createdTime}}</div>
                                <div class="abbr">
                                    <p>{{item.content}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pager" ng-show="pageService.show.pageCount > 0">
                <div class="w-pager">
                    <a href="#top">
                        <button class="w-button w-button-aside" ng-class="{'w-button-disabled':pageService.show.page==1}" type="button" ng-disabled="pageService.show.page==1" ng-click="changePage(1)" href="#top"><span>首页</span></button>
                    </a>
                    <a href="#top">
                        <button class="w-button" ng-class="{'w-button-main':pageService.show.page==page}" type="button" ng-repeat="page in pageService.show.pageArr track by $index" ng-click="changePage($index)" href="#top"><span>{{page}}</span></button>
                    </a>
                    <a href="#top">
                        <button class="w-button w-button-aside" type="button" ng-class="{'w-button-disabled':pageService.show.page==pageService.show.pageCount}" ng-disabled="pageService.show.page==pageService.show.pageCount" ng-click="changePage(pageService.show.pageCount)" href="#top"><span>末页</span></button>
                    </a>
                    <span class="w-pager-ellipsis">共{{pageService.show.pageCount}}页</span>
                </div>
            </div>
        </div>
    </div>
    <div id="BottomBlank" style="display:none;text-align:center;">已经到达列表底部～</div>