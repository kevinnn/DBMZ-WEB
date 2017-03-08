<div class="m-user-shareAdd-panel" id="wrapper" ng-show="currentView==='shareEdit'">
    <!-- 夺宝晒单前置页 -->
    <!--     <div class="m-user-shareAdd-toknow" style="display: none;">
        <div class="pic">
            <img src="http://mimg.127.net/p/one/web/release/user/share/imgs/shareToKnow.jpg" alt="">
        </div>
        <div class="cont">
            <p class="dearUser">亲爱的用户：</p>
            <p>恭喜您在一元夺宝中获得幸运号码！</p>
            <p>现在，我们邀请您进行晒单！您可以将获得的商品拍照，并附上文字放到网上。</p>
            <p>您的晒单，可以和网友们分享您的喜悦，帮助大家更充分的了解一元夺宝平台的乐趣，而且，晒单成功，您会获得直减红包奖励，最高价值10夺宝币！</p>
            <p class="attention">晒单的时候，我们提醒您注意以下几点：</p>
            <p class="item"><span class="num">1</span> 详细的内容，是晒单的精华，因此，建议您的晒单不少于30字，并且配上3张以上不同的照片（跪求高清无码）；</p>
            <p class="item"><span class="num">2</span> 真实，是晒单的灵魂，因此，您可以将快递单号也晒上来哦~</p>
            <p class="item"><span class="num">3</span> 一般大家都喜欢沾仙气，因此，您能够出镜和商品合个影儿什么的，给大家一起膜拜就再好不过了！</p>
            <p class="item"><span class="num">4</span> 晒单的质量越高越好玩，红包的价值就越高哦！</p>
            <p class="item"><span class="num">5</span> 如果同一类商品多次获得，请勿简单的复制粘贴之前的晒单内容。</p>
            <div class="toknow">
                <button class="w-button w-button-main" pro="iknow"><span>我知道了，开始晒单</span></button>
            </div>
        </div>
    </div> -->
    <div class="m-user-shareAdd-form" style="display: block;" ng-hide="isPreview">
        <div class="title" >
            编辑晒单内容<span>赢取红包，最高价值10夺宝币<i></i></span>
        </div>
        <div class="cont">
            <div class="share-title">
                <lable>晒单主题 : </lable>
                <div class="share-input">
                    <div pro="titleInput">
                        <div class="w-input">
                            <input class="w-input-input" pro="input" type="text" ng-model="show.title" placeholder="不多于8个字" maxlength="8">
                        </div>
                    </div>
                </div>
                <div class="titleTips"></div>
            </div>
            <div class="share-cont">
                <lable>幸运感言 : </lable>
                <div class="share-input">
                    <div pro="contentInput" style="line-height: 0;">
                        <div class="w-input w-input-textarea" id="pro-view-11">
                            <textarea class="w-input-input" pro="input" ng-model="show.content" placeholder="为了更好地和网友分享您的喜悦，文字内容不少于30字不多于255字。审核通过会获得直减红包奖励，最高价值10夺宝币！" maxlength="255"></textarea>
                        </div>
                    </div>
                    <div class="contTips"></div>
                </div>
            </div>
            <div class="share-images">
                <label>上传图片 : </label>
                <div class="toUpload">
                    <div id="showImgeUrlPicker" class="webuploader-container" style="top: 14px;">选择文件</div>
                </div>
                <span class="uploadTips">上传的照片，必须是真实的商品照片哦，单张照片大小请勿超过5M (暂不支持火狐浏览器)</span>
                <div class="imgList" id="imgList" style="display: block; zoom: 1;">
                    
                    
                    <div class="item" ng-hide="show.imgThumbs.length === 0" ng-repeat="item in show.imgThumbs track by $index" ng-mouseover="mouseoverShowImage($index)" ng-mouseout="mouseoutShowImage($index)">
                        <table>
                            <tbody><tr>
                                <td valign="middle" align="center">
                                    <img ng-src="{{item}}">
                                </td>
                            </tr>
                        </tbody></table>
                        <a href="javascript:void(0);" class="close" title="删除图片" ng-click="deleteShowImage($index)"><b class="ico m-user-shareAdd-icoClose"></b></a>
                        <span class="mask"></span>
                    </div>
                    
                </div>
                <div class="share-buttons">
                    <div class="toPublish">
                        <button class="w-button w-button-main" ng-click="showShowConfirmModal()"><span>发布晒单</span></button>
                    </div>
                    <div class="toPreview">
                        <a href="javascript:void(0);" ng-click="togglePreview()">预览</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="m-user-shareAdd-preview" ng-show="isPreview">
        <div class="title">
            晒单内容预览
        </div>
        <div class="cont">
            <div class="preview-title"></div>
            <div class="preview-title">{{show.title}}</div>
            <div class="preview-cont">
                <i class="ico ico-quote ico-quote-former"></i>
                <i class="ico ico-quote ico-quote-after"></i>
                <div class="preview-text">{{show.content}}</div>
            </div>
            <div class="preview-images">
                <div class="item" ng-hide="show.imgThumbs.length === 0" ng-repeat="url in show.imgThumbs track by $index">
                    <table>
                        <tbody>
                            <tr>
                                <td valign="middle" align="center">
                                    <img ng-src="{{url}}">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="preview-buttons">
            <div class="toPublish">
                <button class="w-button w-button-main" ng-click="showShowConfirmModal()"><span>发布晒单</span></button>
            </div>
            <div class="toPreview">
                <a href="" ng-click="togglePreview()">返回修改</a>
            </div>
        </div>
    </div>
</div>