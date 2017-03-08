<?php
require_once "../../config/config.php";
require_once __ROOT__.'/wx/wx.Config.php';
require_once __ROOT__.'/controller/userController.php';
include_once __ROOT__.'/database/mysql.php';
//$_GET['state']是邀请码

if(isset($_GET)){
    if(isset($_GET['code'])){         
        $code = $_GET['code'];
        $state = $_GET['state'];
        $appid = WxConfig::appID;
        $appsecret = WxConfig::appsecret;
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
        $res = file_get_contents($url);
        if( $code=='authdeny'){
            header("location: http://".SERVER_DOMAIN."/mobile.html#");
            exit();            
        }
        // echo $res;    
        // echo "<script>alert('$res')</script>";
        $res_array = json_decode($res,true);
        //这里可以获取$res_array['refresh_token']
        if(isset($res_array['scope'])){
            if($res_array['scope'] == 'snsapi_userinfo'){
                if (isset($res_array['openid'])){
                    //尝试用openid登陆
                    $openid = $res_array['openid'];
                    

                    if(userController::loginWithWxOpenid($openid)){
                        //登陆成功
                        echo "您已注册过，无法使用此邀请码";
                    }else{
                        //登陆失败,以scope=snsapi_userinfo来获取用户信息                      
                        
                        $openid = $res_array['openid'];
                        $oauth2_access_token = $res_array['access_token'];
                        
                        
                        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$oauth2_access_token&openid=$openid&lang=zh_CN";
                        $res = file_get_contents($url);
                        // echo $res;
                        // echo "<script>alert('$res')</script>";
                        $res_array = json_decode($res,true);
                        // 正确时返回的JSON数据包如下：
                        // {
                        // "openid":" OPENID",
                        // " nickname": NICKNAME,
                        // "sex":"1",
                        // "province":"PROVINCE"
                        // "city":"CITY",
                        // "country":"COUNTRY",
                        //     "headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46", 
                        //     "privilege":[
                        //     "PRIVILEGE1"
                        //     "PRIVILEGE2"
                        //     ],
                        //     "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
                        // 
                        userController::registerWithWx($res_array,$state);
                        header("location: http://".SERVER_DOMAIN."/mobile.html#");       
                    }

                }
            }else if ($res_array['scope'] == 'snsapi_base') {
                if (isset($res_array['openid'])){
                    //尝试用openid登陆
                    $openid = $res_array['openid'];
                    

                    if(userController::loginWithWxOpenid($openid)){
                        //登陆成功
                        $userArr = json_decode(userController::isLogin(),true);
                        if ($userArr['code'] == 200) {
                            $user = $userArr['data'];
                            $user = $user[0];
                        }
?>
<html>
<head>
    <title>夺宝萌主</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="/public/css/share.css">
</head>
<body class="share-old-page">
    <div class="share" onclick="share()" id="pointer"></div>
    <div class="share-old">
        <img src="/public/img/mobile/page-share-old.png">
        <div class="avatar-wrap">
            <p class="avatar">
                <img src="<?php echo $user['avatorUrl'];?>"/></a>
            </p>
        </div>
         <a class="invite-button" onclick="share()"></a>
    </div>

    <script type="text/javascript" src="/public/js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript" src="/public/js/mobile/share.js"></script>
    <script type="text/javascript">
        var userName = "<?php echo $user['userName'];?>",
            code = "<?php echo $user['code'];?>";
        $.ajax({
            url: '/wx/getJsApiConfig?url='+encodeURIComponent(location.href),
            type: 'GET',
            success: function (data) {
                if (data.code == 200) {
                    wx.config({
                        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                        appId: data.data.appId, // 必填，公众号的唯一标识
                        timestamp: data.data.timestamp, // 必填，生成签名的时间戳
                        nonceStr: data.data.nonceStr, // 必填，生成签名的随机串
                        signature: data.data.signature,// 必填，签名，见附录1
                        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
                    });
                    wx.checkJsApi({
                        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'], // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
                        success: function(res) {
                            // 以键值对的形式返回，可用的api值true，不可用为false
                            // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
                        }
                    });
                    wx.ready(function(){
                        wx.onMenuShareTimeline({
                            title: userName+'分享了1元红包，你也可以领取参与收获惊喜！', // 分享标题
                            link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx5978bb99a4b31667&redirect_uri=http%3a%2f%2ftest.dangke.co%2fwx%2foauthwithcode&response_type=code&scope=snsapi_base&state='+code+'#wechat_redirect', // 分享链接
                            imgUrl: 'http://7xs9hx.com1.z0.glb.clouddn.com/champion.jpg', // 分享图标
                            success: function () {
                                // 用户确认分享后执行的回调函数
                                share();
                            },
                            cancel: function () {
                                // 用户取消分享后执行的回调函数
                            }
                        });
                        wx.onMenuShareAppMessage({
                            title: userName+'送你1元红包，领取夺宝惊喜无限！', // 分享标题
                            desc: '只需花一元就有机会获得梦想奖品，勇夺武林萌主，尽享"逆袭"激情！', // 分享描述
                            link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx5978bb99a4b31667&redirect_uri=http%3a%2f%2ftest.dangke.co%2fwx%2foauthwithcode&response_type=code&scope=snsapi_base&state='+code+'#wechat_redirect', // 分享链接
                            imgUrl: 'http://7xs9hx.com1.z0.glb.clouddn.com/champion.jpg', // 分享图标
                            type: 'link', // 分享类型,music、video或link，不填默认为link
                            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                            success: function () { 
                                // 用户确认分享后执行的回调函数
                                share();
                            },
                            cancel: function () { 
                                // 用户取消分享后执行的回调函数
                            }
                        });
                    });
                }
            },
            error : function (data) {
                console.log(data);
            }

        })
    </script>
</body>

</html>
<?php
                    }else{

?>
<!DOCTYPE html>
<html>
<head>
    <title>夺宝萌主</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="/public/css/share.css">
</head>
<body class="share-new-page">
    <div class="share-new">
        <img src="/public/img/mobile/page-share-new.png">
        <div class="avatar-wrap">
            <p class="avatar">
                <img src=""/></a>
            </p>
            <p class="nickname">我就是要成为萌主</p>
        </div>
        <a class="open-button" href='<?php echo "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx5978bb99a4b31667&redirect_uri=http%3a%2f%2f".SERVER_DOMAIN."%2fwx%2foauthwithcode&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";?>'></a>
    </div>
    
    <script type="text/javascript" src="/public/js/jquery/jquery.min.js"></script>
    <script type="text/javascript">
        var code = '<?php echo $state;?>';
        $.ajax({
            url: '/user/getUserByInviteCode?inviteCode='+code,
            type: 'GET',
            success: function (data) {
                if (data.code == 200) {
                    $('.nickname').text(data.data.userName);
                    $('.avatar img')[0].src = data.data.avatorUrl;
                }   
            },
            error: function (data) {
                console.log(data);
            }
        })
    </script>
</body>
</html>
<?php
                    }

                }
            }
        }
    }
}
?>