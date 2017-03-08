<?php
require_once "jssdk.php";
require_once "../../config/config.php";
require_once __ROOT__.'/wx/wx.Config.php';
include_once __ROOT__.'/bean/jsonBean.php';

$url = $_GET['url'];
$jssdk = new JSSDK(WxConfig::appID, WxConfig::appsecret,$url);
$signPackage = $jssdk->GetSignPackage();


$result = array();
$result["appId"] = $signPackage["appId"];
$result["timestamp"] = $signPackage["timestamp"];
$result["nonceStr"] = $signPackage["nonceStr"];
$result["signature"] = $signPackage["signature"];
$result["url"] = $signPackage["url"];
$result["rawString"] = $signPackage["rawString"];
echo json_encode(new jsonBean(200,$result,'获取jsApiConfig成功'),JSON_UNESCAPED_UNICODE);