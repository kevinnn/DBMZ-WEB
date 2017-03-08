<?php
include '../../../config/config.php';
include __ROOT__.'/controller/userController.php';
$User = json_decode(userController::isLogin(),true);
if ($User['code'] == 200) {
$user = $User['data'][0];
} else {
header('Location: /');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../../components/head.php';?>
        <meta charset="UTF-8">
        <title>RechargeTODO</title>
        <meta name="description" content="TODO">
        <meta name="keywords" content="TODO">
    </head>
    <body ng-app="YYYG">
        <?php include '../../components/payment_header.php';?>
        <div class="recharge" ng-controller='rechargeController'>
            <div class="g-body">
                <div class="m-cashier" module="cashier/recharge/Recharge" id="pro-view-0" module-id="module-1" module-launched="true">
                    <div class="m-header">
                        <div class="g-wrap f-clear">
                            <div class="m-header-logo">
                                <h1><a class="m-header-logo-link" href="/">一元夺宝</a></h1>
                                <div class="m-header-slogan">
                                    <a id="whatIsThis" href="javascript:void(0)" title="什么是一元夺宝？" style="display:none"><img src="http://mimg.127.net/p/one/web/lib/img/promotion/logo_banner_beta.gif"></a>
                                </div>
                            </div>
                            <div class="m-header-steps">
                            </div>
                        </div>
                    </div>
                    <div class="g-wrap">
                        <div class="m-cashier-recharge">
                            <h1 class="title">充值夺宝币</h1>
                            <div class="content">
                                <table>
                                    <tbody><tr>
                                        <th>充值金额：
                                        </th>
                                        <td>
                                            <div class="w-pay w-money" id="pro-view-10">
                                                <div class="w-pay-selector" pro="selector">
                                                    <div class="w-pay-money" ng-class="{'w-pay-money-selected': isSelectOtherAmount === false && selectAmount === 10}" ng-click="changeSelectAmount(10, false)">10元</div>
                                                    <div class="w-pay-money" ng-class="{'w-pay-money-selected': isSelectOtherAmount === false && selectAmount === 20}" ng-click="changeSelectAmount(20, false)">20元</div>
                                                    <div class="w-pay-money" ng-class="{'w-pay-money-selected': isSelectOtherAmount === false && selectAmount === 100}" ng-click="changeSelectAmount(100, false)">100元</div>
                                                    <div class="w-pay-money" ng-class="{'w-pay-money-selected': isSelectOtherAmount === false && selectAmount === 200}" ng-click="changeSelectAmount(200, false)">200元</div>
                                                    <div class="w-pay-money" ng-class="{'w-pay-money-selected': isSelectOtherAmount}" ng-click="changeSelectAmount(1, true)"><span>其他金额</span>&nbsp;&nbsp;
                                                    <div class="w-input" id="pro-view-9">
                                                        <input class="w-input-input" pro="input" type="text" maxlength="6" style="width: 50px;" ng-model="inputAmount" ng-change="checkInputAmount()">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>支付方式：</th>
                                    <td>
                                        <div tag="payments" module="payments/Payments" cpid="20151217CP002" module-id="module-3" module-launched="true">
                                            <div class="w-pay-selector">
                                                <div class="w-pay-type" ng-class="{'w-pay-selected': selectPayType === 0}" ng-click="changeSelectPayType(0)">
                                                    <img src="http://mimg.127.net/p/yy/lib/img/bank/SMWX.png" alt="微信扫码">
                                                </div>
                                                <div class="w-pay-type" ng-class="{'w-pay-selected': selectPayType === 1}" ng-click="changeSelectPayType(1)">
                                                    <img src="../../../public/img/app/zhifubao.png" alt="支付宝支付">
                                                </div>
                                                <div class="w-pay-type" ng-class="{'w-pay-selected': selectPayType === 2}" ng-click="changeSelectPayType(2)">
                                                    <img src="../../../public/img/app/unionpay.png" alt="银联支付">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td>
                                        <button class="w-button w-button-main w-button-xl button-disabled" type="button" id="submit-recharge-button" disabled="true" ng-click="submitRecharge()"><span>立即充值</span></button>
                                        <label class="w-checkbox" ng-click="updateButton()"><input type="checkbox" ng-model="agreeContract"> <span>我已阅读并同意《服务协议》</span></label>
                                    </td>
                                </tr>
                            </tbody></table>
                            <div class="m-cashier-agreement">
                                <h4 style="margin-bottom:10px;color:#000;text-align:center;font-size:20px;font-weight:bold">一元夺宝平台服务协议</h4>
                                <div style="color:#000;text-indent:2em;font-size:14px;word-wrap:break-word;word-break:break-all">
                                    <p>欢迎访问一元夺宝平台（http://1.163.com），申请使用网易公司提供的一元夺宝平台服务（包括一元夺宝和全价购买服务），请您（下列简称为“用户”）仔细阅读以下全部内容<b style="text-decoration:underline">（特别是粗体下划线标注的内容）</b>。如用户不同意本服务条款任意内容，请勿注册或使用一元夺宝平台服务。如用户通过进入注册程序并勾选“我同意一元夺宝平台服务协议”，即表示用户与网易公司已达成协议，自愿接受本服务条款的所有内容。此后，用户不得以未阅读本服务条款内容作任何形式的抗辩。</p>
                                    <p style="margin-top:20px;"><b>一、用户使用一元夺宝平台服务的前提条件</b></p>
                                    <p>1、用户拥有网易公司认可的帐号，包括但不限于：</p>
                                    <p>（1）网易邮箱帐号，用户通过网易邮箱帐号使用一元夺宝平台服务的，本服务协议是《网易邮箱帐号服务条款》的补充条款，与《网易邮箱帐号服务条款》具有同等法律效力。</p>
                                    <p>（2）第三方帐号，用户可使用QQ帐号、微信帐号、微博帐号等其他网易公司认可的帐号在同意本服务条款后使用一元夺宝平台服务。</p>
                                    <p>2、用户在使用一元夺宝平台服务时须具备相应的权利能力和行为能力，能够独立承担法律责任，如果用户在18周岁以下，必须在父母或监护人的监护参与下才能使用本站。</p>
                                    <p style="margin-top:20px;"><b>二、用户管理</b></p>
                                    <p>1、用户ID</p>
                                    <p>用户首次登录一元夺宝平台时，一元夺宝平台会为每位用户生成一个帐户ID，作为其使用一元夺宝平台服务的唯一身份标识，用户需要对其帐户项下发生的所有行为负责。</p>
                                    <p>2、用户资料完善</p>
                                    <p>用户应当在使用一元夺宝平台服务时完善个人资料，用户资料包括但不限于个人手机号码、收货地址、帐号昵称、头像、密码、注册或更新网易邮箱帐号时输入的所有信息。</p>
                                    <p>用户在完善个人资料时承诺遵守法律法规、社会主义制度、国家利益、公民合法权益、公共秩序、社会道德风尚和信息真实性等七条底线，不得在资料中出现违法和不良信息，且用户保证其在完善个人资料和使用帐号时，不得有以下情形：</p>
                                    <p>（1）违反宪法或法律法规规定的；</p>
                                    <p>（2）危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；</p>
                                    <p>（3）损害国家荣誉和利益的，损害公共利益的；</p>
                                    <p>（4）煽动民族仇恨、民族歧视，破坏民族团结的；</p>
                                    <p>（5）破坏国家宗教政策，宣扬邪教和封建迷信的；</p>
                                    <p>（6）散布谣言，扰乱社会秩序，破坏社会稳定的</p>
                                    <p>（7）散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；</p>
                                    <p>（8）侮辱或者诽谤他人，侵害他人合法权益的；</p>
                                    <p>（9）含有法律、行政法规禁止的其他内容的。</p>
                                    <p>若用户提供给网易公司的资料不准确，不真实，含有违法或不良信息的，网易公司有权不予完善，并保留终止用户使用一元夺宝平台服务的权利。若用户以虚假信息骗取帐号ID或帐号头像、个人简介等注册资料存在违法和不良信息的，网易公司有权采取通知限期改正、暂停使用、注销登记等措施。对于冒用关联机构或社会名人注册帐号名称的，网易公司有权注销该帐号，并向政府主管部门进行报告。</p>
                                    <p>根据相关法律、法规规定以及考虑到一元夺宝平台服务的重要性，用户同意：</p>
                                    <p>（1）在完善资料时提交个人有效身份信息进行实名认证；</p>
                                    <p>（2）提供及时、详尽及准确的用户资料；</p>
                                    <p>（3）不断更新用户资料，符合及时、详尽准确的要求，对完善个人资料时填写的身份证件信息不能更新。</p>
                                    <p>（4）<b style="text-decoration:underline">用户有证明该帐号为本人所有的义务，需能提供网易邮箱注册资料或第三方平台注册资料以证明该帐号为本人所有，否则网易公司有权暂缓向用户交付其所获得的商品。</b></p>
                                    <p>3、夺宝币及宝石</p>
                                    <p>（1）<b style="text-decoration:underline">用户兑换夺宝币并使用后可根据宝石获得规则获取相应的宝石。夺宝币的有效期自兑换之日起算360天，有效期不可中断或延期，有效期届满后，用户帐户中有效期届满的夺宝币将被清空，且不可恢复。宝石自获取之日起生效，使用期限将在宝石规则中规定，详见“我的夺宝”下的“我的宝石”栏目。</b></p>
                                    <p>（2）夺宝币必须通过网易公司提供或认可的平台获得，从非网易公司提供或认可的平台所获得的夺宝币将被认定为来源不符合本服务协议，网易公司有权拒绝从非网易公司提供或认可的平台所获得的夺宝币在一元夺宝平台中使用。</p>
                                    <p>（3）<b style="text-decoration:underline">夺宝币及宝石不能在一元夺宝平台之外使用或者转移给其他用户。</b></p>
                                    <p>4、用户应当保证在使用一元夺宝平台服务的过程中遵守诚实信用原则，不扰乱一元夺宝平台的正常秩序，<b style="text-decoration:underline">不得通过使用他人帐户、一人注册多个帐户、使用程序自动处理等非法方式损害他人或网易公司的利益。</b></p>
                                    <p>5、若用户存在任何违法或违反本服务协议约定的行为，网易公司有权视用户的违法或违规情况适用以下一项或多项处罚措施：</p>
                                    <p><b style="text-decoration:underline">（1）责令用户改正违法或违规行为；</b></p>
                                    <p><b style="text-decoration:underline">（2）中止、终止部分或全部服务；</b></p>
                                    <p><b style="text-decoration:underline">（3）取消用户夺宝订单并取消商品发放（若用户已获得商品）， 且用户已获得的夺宝币不予退回；</b></p>
                                    <p><b style="text-decoration:underline">（4）冻结或注销用户帐号及其帐号中的夺宝币（如有）；</b></p>
                                    <p><b style="text-decoration:underline">（5）其他网易公司认为合适在符合法律法规规定的情况下的处罚措施。</b></p>
                                    <p><b style="text-decoration:underline">若用户的行为造成网易公司及其关联公司损失的，用户还应承担赔偿责任。</b></p>
                                    <p><b style="text-decoration:underline">6、若用户发表侵犯他人权利或违反法律规定的言论，网易公司有权停止传输并删除其言论、禁止该用户发言、注销用户帐号及其帐号中的夺宝币（如有），同时，网易公司保留根据国家法律法规、相关政策向有关机关报告的权利。</b></p>
                                    <p style="margin-top:20px;"><b>三、一元夺宝平台服务的规则</b></p>
                                    <p>1、释义</p>
                                    <p>（1）夺宝币：指用户为获得商品所支付并由网易公司预收货款后获得的使用一元夺宝平台服务的凭据。</p>
                                    <p>（2）夺宝号码：指用户使用夺宝币参与一元夺宝服务时所获取的随机分配号码。</p>
                                    <p>（3）幸运号码：指与某件商品的全部夺宝号码分配完毕后，一元夺宝根据夺宝规则（详见一元夺宝官方页面）计算出的一个号码。持有该幸运号码的用户可直接获得该商品。</p>
                                    <p>（4）宝石：指用户参与一元夺宝平台活动后，其获得的夺宝号码中未包含幸运号码的情况下，可获取的相应回报。</p>
                                    <p>（5）全价购买：指用户以固定价格直接获得一元夺宝平台商品的形式。</p>
                                    <p>（6）一元夺宝：指用户花费一元兑换一个夺宝币，然后凭夺宝币参与一元夺宝平台活动，并在使用夺宝币后根据宝石获得规则获取相应宝石的形式。</p>
                                    <p>2、网易公司承诺遵循公平、公正、公开的原则运营一元夺宝平台，确保所有用户在一元夺宝平台中享受同等的权利与义务，夺宝结果向所有用户公示。</p>
                                    <p>3、用户知悉，除本协议另有约定外，无论是否获得商品，用户用于参与一元夺宝平台活动的夺宝币不能退回；其完全了解参与一元夺宝平台活动存在的风险，网易公司不保证用户参与一元夺宝一定会获得商品，但参与后可根据宝石规则获得相应的宝石。宝石规则，详见“我的夺宝”下的“我的宝石”栏目。</p>
                                    <p><b style="text-decoration:underline">4、用户通过参与一元夺宝平台活动获得商品后，应在7天内登录一元夺宝平台提交或确认收货地址，否则视为放弃该商品，用户因此行为造成的损失，网易公司不承担任何责任。</b>商品由网易公司或经网易公司确认的第三方商家提供及发货。</p>
                                    <p>5、用户通过参与一元夺宝平台活动获得的商品，享受该商品生产厂家提供的三包服务，具体三包规定以该商品生产厂家公布的为准。</p>
                                    <p><b style="text-decoration:underline">6、如果下列情形发生，网易公司有权取消用户夺宝订单：</b></p>
                                    <p><b style="text-decoration:underline">（1）因不可抗力、一元夺宝平台系统发生故障或遭受第三方攻击，或发生其他网易公司无法控制的情形；</b></p>
                                    <p><b style="text-decoration:underline">（2）根据网易公司已经发布的或将来可能发布或更新的各类规则、公告的规定，网易公司有权取消用户订单的情形。</b></p>
                                    <p><b style="text-decoration:underline">网易公司有权取消用户的订单时，用户可申请退还夺宝币，所退夺宝币将在3个工作日内退还至用户帐户中。</b></p>
                                    <p>7、若某件商品的夺宝号码从开始分配之日起90天未分配完毕，则网易公司有权取消该件商品的夺宝活动，并向用户退还夺宝币，所退还夺宝币将在3个工作日内退还至用户帐户中。</p>
                                    <p style="margin-top:20px;"><b>四、本服务协议的修改</b></p>
                                    <p>用户知晓网易公司不时公布或修改的与本服务协议有关的其他规则、条款及公告等是本服务协议的组成部分。网易公司有权在必要时通过在一元夺宝平台内发出公告等合理方式修改本服务协议，用户在享受各项服务时，应当及时查阅了解修改的内容，并自觉遵守本服务协议。用户如继续使用本服务协议涉及的服务，则视为对修改内容的同意，当发生有关争议时，以最新的服务协议为准；用户在不同意修改内容的情况下，有权停止使用本服务协议涉及的服务。</p>
                                    <p style="margin-bottom:30px;">如用户对本规则内容有任何疑问，可拨打客服电话（<span style="text-decoration:underline">4000178163</span>）或登录帮助中心（<a href="http://help.mail.163.com/feedback.do?m=add&amp;categoryName=%e4%b8%80%e5%85%83%e5%a4%ba%e5%ae%9d" target="_blank">http://help.mail.163.com/feedback.do?m=add&amp;categoryName=%e4%b8%80%e5%85%83%e5%a4%ba%e5%ae%9d</a>）进行查询。</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../../components/footer.php';?>
    <script type="text/javascript" src="../../public/js/app.js"></script>
    <script type="text/javascript" src="../../public/js/pages/recharge.js"></script>
</body>
</html>