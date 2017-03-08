<?php

include_once __ROOT__.'/bean/jsonBean.php';

// header('location:/mobile.html#/tab/result');
// $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx5978bb99a4b31667&redirect_uri=http%3a%2f%2f".SERVER_DOMAIN."%2fwx%2foauth&response_type=code&scope=snsapi_userinfo&state=$state#wechat_redirect";

        
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$result = array();
if(isset($_GET)){
    if(isset($_GET['returnAddress'])){
        $state = $_GET['returnAddress'];
        if(isset($_SESSION['userId'])){
            $url = "http://".SERVER_DOMAIN."/mobile.html#".$state;
        }else{
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx5978bb99a4b31667&redirect_uri=http%3a%2f%2f".SERVER_DOMAIN."%2fwx%2foauth&response_type=code&scope=snsapi_userinfo&state=$state#wechat_redirect";
        }
        
        header("location: $url");
    }else{
        echo json_encode(new jsonBean(403,$result,'参数错误'));
    }
}else{
    echo json_encode(new jsonBean(403,$result,'参数错误'));
}
