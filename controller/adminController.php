<?php
include_once __ROOT__.'/bean/jsonBean.php';
Class adminController {

	public static function isLogin() {
		session_start();
		if (isset($_SESSION['admin'])) {
			return json_encode(new jsonBean(200,$_SESSION['admin'],'已登录'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(404,array(),'未登录'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function login() {
		include __ROOT__.'/model/adminModel.php';
		$username = $_POST['username'];
		$password = $_POST['password'];
		$encryptedPass = md5($password);
		$arr = adminModel::getAdminByUserNameAndPassword($username,$encryptedPass);
		if (count($arr) > 0) {
			date_default_timezone_set('PRC');
			adminModel::updateData($arr[0]->id,array("ip" => $_SERVER["REMOTE_ADDR"],"loginTime" => date("Y-m-d H:i:s")),adminModel::getTable());
			$jsonBean = new jsonBean(201,$arr,'登录成功');
			$obj = $arr[0];
			session_start();
			$_SESSION['admin'] = $obj;
		} else {
			$jsonBean = new jsonBean(405,$arr,'用户名或密码错误');
		}
		return json_encode($jsonBean,JSON_UNESCAPED_UNICODE);
	}
	public static function logout() {
		session_start();
		session_unset();
		session_destroy();
		return json_encode(new jsonBean(200,array(),'退出成功'),JSON_UNESCAPED_UNICODE);
	}

	/*后台接口*/
	public static function adminList () {
		include __ROOT__.'/model/adminModel.php';
		return json_encode(new jsonBean(200,adminModel::getAdminList(),'获取成功'),JSON_UNESCAPED_UNICODE);
	}
	public static function register () {
		include __ROOT__.'/model/adminModel.php';
		if (isset($_POST["username"]) && isset($_POST['password'])) {
			$username = $_POST['username'];
			$encryptedPass = md5($_POST['password']);
			$arr = adminModel::getAdminByUserName($username);
			if (count($arr) < 1) {
				return json_encode(new jsonBean(200,adminModel::insertData(array('username'=>$username,'encryptedPass'=>$encryptedPass),adminModel::getTable()),'注册成功'),JSON_UNESCAPED_UNICODE);
			} else {
				return json_encode(new jsonBean(405,array(),'用户已存在'),JSON_UNESCAPED_UNICODE);
			}
		} else {

		}
	}
	public static function adminDelete () {
		include __ROOT__.'/model/adminModel.php';
		if (isset($_POST["id"])) {
			$id = $_POST["id"];
			return json_encode(new jsonBean(200,adminModel::deleteData($id,adminModel::getTable()),'删除成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function adminUpdate () {
		include __ROOT__.'/model/adminModel.php';
		if (isset($_POST['id']) && isset($_POST['password'])) {
			$id = $_POST['id'];
			return json_encode(new jsonBean(200,adminModel::updateData($id,array('encryptedPass'=>md5($_POST['password'])),adminModel::getTable()),'修改成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

}
?>