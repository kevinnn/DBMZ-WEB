<div ng-cloak ng-hide="isLoading">
    <div class="m-user-profile" ng-show="currentView==='setting'">
        <div class="m-user-comm-wraper">
            <table style="width:100%">
                <tbody>
                    <tr>
                        <th>头像</th>
                        <td>
                            <a pro="UC_avatarEdit" class="w-user-avatarEdit w-user-avatarEdit-160" href="javascript:void(0);">
                                <img pro="avatar" width="160" height="160" ng-src="{{user.avatorUrl}}">
                                <span class="w-user-avatarEdit-tips">点击更换头像</span>
                            </a>
                        </td>
                    </tr>
                    <tr pro="nicknameWp">
                        <th>昵称</th>
                        <td>
                            <div pro="nameShow">
                                <span class="contTxt" pro="nickname">{{user.userName}}</span>
                                <a pro="UC_nicknameEdit" href="javascript:void(0)" class="linkEdit"><b class="ico ico-edit"></b>修改</a>
                            </div>
                            <div class="nameEdit" pro="nameEdit" style="display:none">
                                <div id="pro-view-53">
                                    <div class="w-input" id="pro-view-54">
                                        <input class="w-input-input" value="{{user.userName}}" pro="input" type="text" maxlength="80" placeholder="请输入昵称" id="userName" ng-focus="reset()">
                                    </div>

                                    <button class="w-button w-button-main" type="button" id="pro-view-55" style="margin-left: 8px; margin-right: 8px;" ng-click="save()">
                                        <span>保存</span>
                                    </button>
                                    <button class="w-button w-button-aside" type="button" ng-click="reset(true)" name="cancel">
                                        <span>取消</span>
                                    </button>
                                    <p style="color: red;" ng-show="error.userName.empty">昵称不能为空</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <td>
                            <?php echo $user['id'] ;?>
                        </td>
                    </tr>
                    <tr>
                        <th>登录帐号</th>
                        <td>
                            <span pro="username">{{user.phoneNumber}}</span>
                        </td>
                    </tr>
                    <tr pro="mobileWp" class="iMobileWp">
                        <th>手机号</th>
                        <td>
                            <div pro="mobileShow">
                                <span class="contTxt" pro="mobile" ng-show="user.phoneNumber">{{user.phoneNumber}}</span>
                                <a pro="UC_mobileEdit" href="javascript:void(0)" class="linkEdit"><b class="ico ico-edit"></b>填写手机号，不错过揭晓通知哦</a>
                            </div>
                            <div pro="mobileEdit" style="display: none;">
                                <div pro="mobilecheck" class="w-mobilecheck">
                                    <div class="w-mobilecheck-item w-mobilecheck-mobile-item">
                                        <div class="w-input">
                                            <input class="w-input-input" pro="input" type="text" maxlength="11" placeholder="请输入手机号" style="width: 222px;" ng-model="phoneNumber" required ng-focus="reset()" id="phoneNumber">
                                        </div>
                                        <p style="color: red;" ng-show="error.phoneNumber.empty">请输入手机号码</p>
                                        <p style="color: red;" ng-show="error.phoneNumber.invalid">手机格式不正确</p>
                                        <p style="color: red;" ng-show="error.phoneNumber.unique">手机已经验证过了</p>
                                    </div>
                                    <div pro="sncheck" class="w-mobilecheck-item">
                                        <div class="w-input">
                                            <input class="w-input-input" pro="input" type="text" maxlength="6" placeholder="请输入验证码" style="width: 122px;" ng-model="code" required ng-focus="reset()" id="code">
                                        </div>
                                        <input class="w-button w-button-simple" type="button" style="margin-left: 10px; width: 88px; height: 35px; color: rgb(51, 51, 51); font-size: 14px; font-weight: normal; vertical-align: top;" ng-click="sendCode()" value="{{codeBtnText}}" ng-disabled="codeBtnDisabled">
                                        <p style="color: red;" ng-show="error.code.empty">请输入验证码</p>
                                        <p style="color: red;" ng-show="error.code.incorrect">验证码错误</p>
                                        <p style="color: red;" ng-show="error.code.invalid">验证码格式不正确</p>

                                    </div>
                                    <div pro="smscheck" style="margin-bottom:12px;">
                                        
                                    </div>
                                    <div class="w-mobilecheck-operation">
                                        <button class="w-button w-button-main" type="button" style="margin-right: 9px; width: 65px; height: 32px; font-weight: normal; font-size: 14px;" ng-click="savePhoneNumber();">
                                        <span>保存</span>
                                        </button>
                                        <button class="w-button w-button-aside" type="button" name="cancel" ng-click="reset(true)" style="width: 65px; height: 32px; font-weight: normal; font-size: 14px;">
                                        <span>取消</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- 个人资料页面显示 -->
<!-- 上传头像 -->
<div class="w-msgbox m-user-editMsgbox" tabindex="0" id="avatorUrlUpdate" style="left: 334px; top: 111.111px; position: absolute; visibility: hidden;">
    <a pro="close" href="javascript:void(0);" class="w-msgbox-close">×</a>
    <div class="w-msgbox-hd" pro="header">编辑头像</div>
    <div class="w-msgbox-bd" pro="entry">
        <div id="imgUrlList" class="uploader-list"></div>
        <div id="imgUrlPicker">选择图片</div>
    </div>
</div>