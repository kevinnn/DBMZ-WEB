<?php
include '../../config/config.php';

$url = explode("/", $_SERVER['PHP_SELF']);
$method = $url[count($url)-1];
switch($method) {
	case 'getJsApiConfig' :
		header('Content-type:application/json;charset=UTF-8');
		require_once __ROOT__.'/wx/jsapi/sample.php';
		break;
	case 'login' :
		require_once __ROOT__.'/wx/wxoauth/login.php';
		break;
	case 'oauth' :
		require_once __ROOT__.'/wx/wxoauth/oauth.php';
		break;
	case 'oauthwithcode' :
		require_once __ROOT__.'/wx/wxoauth/oauthwithcode.php';
		break;
}
?>