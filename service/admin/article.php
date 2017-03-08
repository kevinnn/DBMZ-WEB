<?php
header('Content-type:application/json;charset=UTF-8');
include '../../config/config.php';
include __ROOT__.'/controller/adminController.php';
$check = json_decode(adminController::isLogin(),true);
if ($check['code'] == 200) {
	include __ROOT__.'/controller/articleController.php';
	$url = explode("/", $_SERVER['PHP_SELF']);
	$method = $url[count($url)-1];
	switch($method) {
		case 'addArticle' :
			echo articleController::addArticle();
			break;
		case 'articleList' :
			echo articleController::articleList();
			break;
		case 'articleEdit' :
			echo articleController::articleEdit();
			break;
		case 'updateArticle' :
			echo articleController::updateArticle();
			break;
		case 'deleteArticle' :
			echo articleController::deleteArticle();
			break;
	}
}
?>