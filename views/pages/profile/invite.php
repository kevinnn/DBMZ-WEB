<div ng-cloak>
    <div class="b_record_buy" ng-show="currentView==='invite'">
        <!--S 去邀请好友 -->
        <div class="b_record_list b_record_cloud" style="display: block;">
            
            <!--         <div class="a_invite_link">
                <em>您的邀请链接</em>
                <span id="invite_link">http://www.ygqq.com/api/uc/register.html?code=YG13826459756</span>
            </div> -->
            <div class="a_invite_link">
                <em>您的邀请码&nbsp;&nbsp;&nbsp;&nbsp;</em>
                <span id="invite_mobile"><?php echo $user['code'];?></span>
                &nbsp;&nbsp;&nbsp;您的好友注册时记得告诉他您的邀请码哦~
            </div>
            <!--         <div class="a_invite_link_share">
                <div class="a_invite_share_list a_invite_share_qq" id="a_invite_share_qq">
                    <div class="bdsharebuttonbox bdshare-button-style0-16" data-bd-bind="1460942748040">
                        <a href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title=云购全球 快乐购&amp;summary=这等好事怎能独享，一块钱就能买iPhone6s，10元就能开走轿车，一起玩起来！&amp;url=http%3A%2F%2Fwww.ygqq.com%2Fapi%2Fuc%2Fregister.html%3Fcode%3DYG13826459756&amp;pics=http://www.ygqq.com/static/img/icon/share-3.jpg" class="bds_qzone" id="qq_share_link" target="_blank" title="分享到QQ空间"></a>
                        <span></span>
                    </div>
                    <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"16"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
                </div>
                <div class="a_invite_share_list a_invite_share_click a_invite_share_wx">
                    <span></span>
                    <div class="a_invite_share_click_down" style="display: none;">
                        <img id="wxImg" src="http://wx.ygqq.com/main_controller/wx_qrCode.do?content=http://wx.ygqq.com/share-10545687.html">
                    </div>
                </div>
                <div class="a_invite_share_list a_invite_share_click a_invite_share_dx">
                    <span></span>
                    <div class="a_invite_share_click_down" style="display: none;">
                        <form>
                            <p class="a_share_dx_p" id="invite_notice">我是9756，邀您一起去云购，记得注册时填写我的邀请码：YG13826459756,点击http://www.ygqq.com/api/uc/register.do
                            </p>
                            <input id="toMobile" onkeydown="$('#sendSMS').html('发送');" maxlength="11" type="text" placeholder="对方手机号">
                            <label>
                                <input id="validCode" style="ime-mode:disabled " class="a_invite_share_click_down_input" type="text" placeholder="验证码">
                                <em><img id="validCodeImg" src="/member/invite/validCode.do" onclick="reloadValidCode();" width="84px" height="28px"></em>
                            </label>
                            <a id="sendSMS" href="javascript:void(0);" onclick="sendSMS();">发送</a>
                        </form>
                    </div>
                </div>
            </div> -->
            <div class="a_invite_font">
                <h2>活动规则</h2>
                <p>一、邀请人奖励</p>
                <p class="a_invite_font1">1、每成功推荐一名好友注册云购全球您将获得100积分。 在【我的云购全球】的【我的积分】里可看到您的每次积分奖励记录。</p>
                <p class="a_invite_font1">2、您邀请的好友充值，您可得好友充值金额的5%作为佣金。 在【我的云购全球】的【我的佣金】里可看到您的每次佣金来源记录。</p>
                <p style="margin-top:15px;">二、被邀请人奖励</p>
                <p class="a_invite_font1">1.  被推荐的好友手机认证成功可获得<span id="phone_verify_score">100</span>积分</p>
                <p class="a_invite_font1" style="padding-bottom:20px;">2.  被推荐的好友邮箱认证成功可获得<span id="email_verify_score">100</span>积分</p>
            </div>
        </div>
    </div>
</div>