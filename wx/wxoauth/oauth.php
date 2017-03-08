<?php
require_once "../../config/config.php";
require_once __ROOT__.'/wx/wx.Config.php';
require_once __ROOT__.'/controller/userController.php';
include_once __ROOT__.'/database/mysql.php';
//$_GET['state']是登陆后的返回地址

if(isset($_GET)){
    if(isset($_GET['code'])){
  
        $code = $_GET['code'];
        $state = $_GET['state'];
        $appid = WxConfig::appID;
        $appsecret = WxConfig::appsecret;
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
        $res = file_get_contents($url);
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
                        header("location: http://".SERVER_DOMAIN."/mobile.html#".$state);   
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
                        // }
                        userController::registerWithWx($res_array);
                        
                        header("location: http://".SERVER_DOMAIN."/mobile.html#".$state);          
                    }

                }
            }
        }
    }
}
?>