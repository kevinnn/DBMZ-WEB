<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../components/head.php';?>
        <meta charset="UTF-8">
        <title>HelpCenterTODO</title>
    </head>
    <body ng-app="YYYG">
        <?php include '../components/header.php';?>
        <div class="helpcenter" ng-controller="helpcenterController">
            <div class="c_cloud_guide c_guide_this">
                <div class="c_guide_content c_guide_this">
                    <div class="c_guide_left" id="leftMenu">
                        <div class="c_newbie_guide">
                            <h4>新手指南</h4>
                            <ul>
                                <li>
                                    <a href="" ng-click="changeView('knowCloud')">购物指南</a>
                                </li>
                                <li>
                                    <a href="" ng-click="changeView('oftenQuestions')">常见问题</a>
                                </li>
                                <li>
                                    <a href="" ng-click="changeView('cmsArticle')">服务协议</a>
                                </li>
                                <li>
                                    <a href="" ng-click="changeView('feedback')">意见反馈</a>
                                </li>
                            </ul>
                        </div>
                        <div class="c_newbie_guide">
                            <h4>服务支持</h4>
                            <ul>
                                <li>
                                    <a href="" ng-click="changeView('ensureSystem')">保障体系</a>
                                </li>
                                <li>
                                    <a href="" ng-click="changeView('riskHint')">风险提示</a>
                                </li>
                                <li>
                                    <a href="" ng-click="changeView('securePay')">安全支付</a>
                                </li>
                                <li>
                                    <a href="" ng-click="changeView('privateAnnouncement')">隐私声明</a>
                                </li>
                            </ul>
                        </div>
                        <div class="c_newbie_guide">
                            <h4>商品配送</h4>
                            <ul>
                                <li><a href="" ng-click="changeView('deliverFee')">配送费用</a></li>
                                <li><a href="" ng-click="changeView('signFor')">验货签收</a></li>
                                <li><a href="" ng-click="changeView('vip')">会员福利</a></li>
                                <li><a href="" ng-click="changeView('sendNotReceive')">已发货未收到商品</a></li>
                            </ul>
                        </div>
                        <div class="c_newbie_guide">
                            <h4>关于我们</h4>
                            <ul>
                                <li><a href="" hidefocus="">了解我们</a></li>
                                <li><a href="" hidefocus="">联系我们</a></li>
                                <li><a href="" hidefocus="">诚聘英才</a></li>
                                <li><a href="" hidefocus="">公司证件</a></li>
                            </ul>
                        </div>
                        <div class="c_newbie_guide">
                            <h4>服务中心</h4>
                            <ul>
                                <li><a href="" hidefocus="">友情链接</a></li>
                            </ul>
                        </div>
                    </div>
                    <?php include './helpcenter/knowCloud.php';?>
                    <?php include './helpcenter/cmsArticle.php';?>
                    <?php include './helpcenter/oftenQuestions.php';?>
                    <?php include './helpcenter/feedback.php';?>

                    <?php include './helpcenter/ensureSystem.php';?>
                    <?php include './helpcenter/riskHint.php';?>
                    <?php include './helpcenter/securePay.php';?>
                    <?php include './helpcenter/privateAnnouncement.php';?>

                    <?php include './helpcenter/deliverFee.php';?>
                    <?php include './helpcenter/signFor.php';?>
                    <?php include './helpcenter/vip.php';?>
                    <?php include './helpcenter/sendNotReceive.php';?>

                    
                </div>
            </div>
        </div>
        <?php include '../components/footer.php';?>
        <script type="text/javascript" src="../../public/js/app.js"></script>
        <script type="text/javascript" src="../../public/js/pages/helpcenter.js"></script>
    </body>
</html>