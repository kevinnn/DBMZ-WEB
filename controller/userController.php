<?php
include __ROOT__.'/JWT/JWT.php';
include __ROOT__.'/model/userModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
include_once __ROOT__.'/model/globalModel.php';
include_once __ROOT__.'/model/balanceModel.php';
include_once __ROOT__.'/util/util.php';
use \Firebase\JWT\JWT;
Class userController {
	public static function isLogin() {
		if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}
		if (isset($_SESSION['userId'])) {
			$token = JWT::decode($_SESSION['userId'],SECRET_SERVER_KEY,array('HS256'));
			$arr = userModel::getUserById($token->id);
			return json_encode(new jsonBean(200,$arr,'已登录'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(404,array(),'未登录'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function register() {
		if (!isset($_POST['phoneNumber']) || !isset($_POST['password']) || !isset($_POST['code'])) {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
		date_default_timezone_set("PRC");
		$phoneNumber = $_POST['phoneNumber'];
		$password = $_POST['password'];
		$code = $_POST['code'];
		$inviteCode = isset($_POST['inviteCode']) ? $_POST['inviteCode'] : '';
		
		$arr = globalModel::getGlobal($phoneNumber);
		if (count($arr) == 1) {
			$phoneNumberObj = $arr[0];
			$verifyCode = $phoneNumberObj->value;
			if (time() - strtotime($phoneNumberObj->createdTime) >= 60*10) {
				globalModel::clearGlobal($phoneNumber);
				return json_encode(new jsonBean(405,array(),'验证码已过期'),JSON_UNESCAPED_UNICODE);
			}
		} else {
			return json_encode(new jsonBean(405,array(),'未获取验证码'),JSON_UNESCAPED_UNICODE);
		}
		if ($verifyCode != $code) {
			return json_encode(new jsonBean(405,array(),'验证码不正确'),JSON_UNESCAPED_UNICODE);
		}
		if (!preg_match("/^1[34578]{1}\d{9}$/",$phoneNumber)) {
			return json_encode(new jsonBean(405,array(),'手机号码不正确'),JSON_UNESCAPED_UNICODE);
		}
		$arr = userModel::getUserByPhoneNumber($phoneNumber);
		if (count($arr) > 0) {
			return json_encode(new jsonBean(405,array(),'手机号码已经存在'),JSON_UNESCAPED_UNICODE);
		}
		$encryptedPass = md5($password);
		userModel::startTransaction();
		$result = userModel::insertData(array(
				'phoneNumber' => $phoneNumber,
				'userName' => $phoneNumber,
				'avatorUrl' => "http://7xs9hx.com1.z0.glb.clouddn.com/avatar_default.jpg",
				'encryptedPass' => $encryptedPass
			), userModel::getTable());
		if ($result) {
			$arr = userModel::getUserByPhoneNumber($phoneNumber);
			$data = $arr[0];
			$code = (string)$data->id;
			while (strlen($code) < 6) {
				$code = '0'.$code; 
			}
			$ip = get_client_ip();
			$result = userModel::updateData($data->id,array('code'=>$code,'loginTime'=>$data->registerTime,'ip'=>$ip,'invitedBy'=>$inviteCode,'loginArea'=>getAreaByIp($ip)),userModel::getTable());
			if ($result) {
				globalModel::clearGlobal($phoneNumber);
				if ($inviteCode != '') {
					$arr = userModel::getUserByCode($inviteCode);
					if (count($arr) == 1) {
						if (balanceModel::addBalance($data->id,1,5)) {
							userModel::commitTransaction();
						} else {
							userModel::rollBackTransaction();
							return json_encode(new jsonBean(406,array(),'注册失败'),JSON_UNESCAPED_UNICODE);
						}
					} else {
						userModel::rollBackTransaction();
						return json_encode(new jsonBean(407,array(),'邀请码不正确'),JSON_UNESCAPED_UNICODE);
					}
				} else {
					userModel::commitTransaction();
				}
				self::login();
				return json_encode(new jsonBean(200,array(),'注册成功'),JSON_UNESCAPED_UNICODE);
			} else {
				userModel::rollBackTransaction();
				return json_encode(new jsonBean(406,array(),'注册失败'),JSON_UNESCAPED_UNICODE);
			}
		} else {
			userModel::rollBackTransaction();
			return json_encode(new jsonBean(406,array(),'注册失败'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function login() {
		$phoneNumber = $_POST['phoneNumber'];
		$password = $_POST['password'];
		$encryptedPass = md5($password);
		$arr = userModel::getUserByPhoneNumberAndPassword($phoneNumber,$encryptedPass);
		$jsonBean;
		session_start();
		if (count($arr) > 0) {
			$jsonBean = new jsonBean(200,$arr,'登录成功');
			$obj = $arr[0];
			$token = array();
			$token['id'] = $obj->id;
			userModel::updateLoginTime($obj->id);
			$_SESSION['userId'] = JWT::encode($token, SECRET_SERVER_KEY);
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
	public static function isUserExit() {
		$phoneNumber = $_GET['phoneNumber'];
		$arr = userModel::getUserByPhoneNumber($phoneNumber);
		$jsonBean;
		if (count($arr) > 0) {
			$jsonBean = new jsonBean(200,array(),'用户存在');
		} else {
			$jsonBean = new jsonBean(404,array(),'用户不存在');
		}
		return json_encode($jsonBean,JSON_UNESCAPED_UNICODE);
	}

	public static function modify($userId) {
		if (isset($_POST) && !isset($_POST['encryptedPass']) && !isset($_POST['balance']) && !isset($_POST['credits']) && !isset($_POST['loginArea']) && !isset($_POST['loginTime']) && !isset($_POST['ip']) && !isset($_POST['registerTime']) && !isset($_POST['code'])) {
			if ($userId == null) {
				$check = json_decode(userController::isLogin(),true);
				if ($check['code'] == 200) {
					$users = $check['data'];
					$user = $users[0];
					$userId = $user['id'];

					if (isset($_POST['phoneNumber']) && isset($_POST['verifyCode'])) {
						if (isset($_SESSION['code']) && $_POST['verifyCode'] == $_SESSION['code']) {
							unset($_POST['verifyCode']);
						} else {
							return json_encode(new jsonBean(404,array(),'验证码错误'),JSON_UNESCAPED_UNICODE);
						}
					}
				} else {
					return json_encode(new jsonBean(403,array(),'未登录'),JSON_UNESCAPED_UNICODE);
				}
			}
			return json_encode(new jsonBean(200,userModel::updateData($userId,$_POST,userModel::getTable()),'修改成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
			
	}

	public static function getUser() {
		if (isset($_GET['uid'])) {
			$userId = $_GET['uid'];
			$arr = userModel::getUserByUser($userId);
			if (count($arr) == 0) {
				header('location:/');
			} else {
				return $arr[0];
			}
		} else {
			header('location:/');
		}
	}
	public static function getUserByInviteCode($inviteCode){
		$results = userModel::getUserByInviteCode($inviteCode);
		if(isset($results[0])){
			$result = $results[0];
			$result1 = array('userName' => $result->userName , 'avatorUrl' => $result->avatorUrl );
			return json_encode(new jsonBean(200,$result1,'获取成功'),JSON_UNESCAPED_UNICODE);
		}else{
			return json_encode(new jsonBean(404,array(),'无此用户'),JSON_UNESCAPED_UNICODE);
		}

		
	}

	public static function getUserMobile() {
		if (isset($_GET['uid'])) {
			$userId = $_GET['uid'];
			$arr = userModel::getUserByUser($userId);
			if (count($arr) == 0) {
				return json_encode(new jsonBean(404,array(),'找不到此用户'),JSON_UNESCAPED_UNICODE);
			}
			return json_encode(new jsonBean(200,$arr,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	
	public static function suggest($userId) {
		if (isset($_POST['theme'])&&isset($_POST['email'])&&isset($_POST['content'])) {
			if(userModel::suggest($userId,$_POST['theme'],$_POST['email'],$_POST['content'])){
				return json_encode(new jsonBean(200,array(),'提交建议成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			}else{
				return json_encode(new jsonBean(401,array(),'提交建议失败'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			}
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	
	/*后台接口*/
	public static function userList() {
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		return json_encode(new jsonBean(200,userModel::getUserList($page),'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}
	public static function addUser() {
		if (isset($_POST)) {
			$_POST['encryptedPass'] = md5($_POST['password']);
			unset($_POST['password']);
			return json_encode(new jsonBean(200,userModel::insertData($_POST,userModel::getTable()),'插入成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function userCount() {
		return json_encode(new jsonBean(200,userModel::getUserCount(),'获取成功'),JSON_UNESCAPED_UNICODE);
	}
	public static function updateUser() {
		if (isset($_POST)) {
			if (isset($_POST['password'])) {
				$_POST['encryptedPass'] = md5($_POST['password']);
				unset($_POST['password']);
			}
			return json_encode(new jsonBean(200,userModel::updateData($_POST['id'],$_POST,userModel::getTable()),'修改成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function deleteUser() {
		if (isset($_POST['id'])) {
			return json_encode(new jsonBean(200,userModel::deleteData($_POST['id'],userModel::getTable()),'删除成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function userEdit() {
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			return json_encode(new jsonBean(200,userModel::getUserById($id),'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function selectUser() {
		if (isset($_GET)) {
			if (isset($_GET['id'])) {
				return json_encode(new jsonBean(200,userModel::getUserById($_GET['id']),'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			} else if(isset($_GET['userName'])) {
				return json_encode(new jsonBean(200,userModel::getSelectUserByUserName($_GET['userName']),'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			} else if (isset($_GET['phoneNumber'])) {
				return json_encode(new jsonBean(200,userModel::getSelectUserByPhoneNumber($_GET['phoneNumber']),'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			}
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function registerWithWx($wxArr,$inviteCode = null) {
		if (isset($wxArr['errcode'])) {
			$errcode = $wxArr['errcode'];
			$errmsg = $wxArr['errmsg'];
			echo "<script>alert('errcode:$errcode,errmsg:$errmsg')</script>";
		}else{
			userModel::startTransaction();
			$result = userModel::insertData(array(
					'openId' => $wxArr['openid'],
					'userName' => $wxArr['nickname'],
					'avatorUrl' => $wxArr['headimgurl'],
					'type' => 2
				), userModel::getTable());
			if ($result) {
				$arr = userModel::getUserByWxOpenid($wxArr['openid']);
				$data = $arr[0];
				$code = (string)$data->id;
				while (strlen($code) < 6) {
					$code = '0'.$code; 
				}
				$ip = get_client_ip();
				$result = userModel::updateData($data->id,array('code'=>$code,'invitedBy'=>$inviteCode,'loginTime'=>$data->registerTime,'ip'=>$ip,'loginArea'=>getAreaByIp($ip)),userModel::getTable());	
				if ($result) {					
					if ($inviteCode != null) {
						$arr = userModel::getUserByCode($inviteCode);
						if (count($arr) == 1) {
							if (balanceModel::addBalance($data->id,1,5)) {
								userModel::commitTransaction();
								self::loginWithWxOpenid($wxArr['openid']);
								return json_encode(new jsonBean(200,array(),'注册成功'),JSON_UNESCAPED_UNICODE);								
							} else {
								userModel::rollBackTransaction();
								return json_encode(new jsonBean(406,array(),'注册失败'),JSON_UNESCAPED_UNICODE);
							}
						} else {
							userModel::rollBackTransaction();
							return json_encode(new jsonBean(407,array(),'邀请码不正确'),JSON_UNESCAPED_UNICODE);
						}
					} else {
						userModel::commitTransaction();
						self::loginWithWxOpenid($wxArr['openid']);
						return json_encode(new jsonBean(200,array(),'注册成功'),JSON_UNESCAPED_UNICODE);
					}
				} else {
					userModel::rollBackTransaction();					
					echo "<script>alert('注册失败')</script>";
					// return json_encode(new jsonBean(406,array(),'注册失败'),JSON_UNESCAPED_UNICODE);
				}
			} else {
				userModel::rollBackTransaction();
				echo "<script>alert('注册失败')</script>";
				// return json_encode(new jsonBean(406,array(),'注册失败'),JSON_UNESCAPED_UNICODE);
			}
		}
	}
	public static function loginWithWxOpenid($openid) {
		$arr = userModel::getUserByWxOpenid($openid);
		$loginSuccess = false;
		if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}
		if (count($arr) > 0) {
			$obj = $arr[0];
			$token = array();
			$token['id'] = $obj->id;
			userModel::updateLoginTime($obj->id);
			$_SESSION['userId'] = JWT::encode($token, SECRET_SERVER_KEY);			

			$loginSuccess = true;
		}
		
		return $loginSuccess;
	}
	public static function updateNickname($userId) {
		if( !isset($_POST) || !isset($_POST['nickname'])){
			return json_encode(new jsonBean(403,array(),'参数错误'),JSON_UNESCAPED_UNICODE);
		}
		$nickname = $_POST['nickname'];
		if (userModel::updateNickname($userId,$nickname)) {
			return json_encode(new jsonBean(200,array(),'昵称修改成功'),JSON_UNESCAPED_UNICODE);
		}else{
			return json_encode(new jsonBean(700,array(),'未知错误'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function updatePassword($userId) {
		if( !isset($_POST) || !isset($_POST['oldpwd']) || !isset($_POST['newpwd'])){
			return json_encode(new jsonBean(403,array(),'参数错误'),JSON_UNESCAPED_UNICODE);
		}
		$password = $_POST['oldpwd'];
		$encryptedPass = md5($password);
		$arr = userModel::getUserByIdAndPassword($userId,$encryptedPass);
		if (count($arr) > 0) {
			$newpwd = md5($_POST['newpwd']);
			if(userModel::updatePassword($userId,$newpwd)){
				return json_encode(new jsonBean(200,array(),'密码修改成功'),JSON_UNESCAPED_UNICODE);
			}else{
				return json_encode(new jsonBean(700,array(),'未知错误'),JSON_UNESCAPED_UNICODE);
			}
		}else{
			return json_encode(new jsonBean(404,array(),'旧密码不正确'),JSON_UNESCAPED_UNICODE);
		}

	}
	public static function getHbRecords($userId) {
		return json_encode(new jsonBean(200,balanceModel::getHbRecords($userId),'获取红包记录成功'),JSON_UNESCAPED_UNICODE);

	}
	
	public static function notShowHb($userId) {
		if(userModel::notShowHb($userId)){
			return json_encode(new jsonBean(200,array(),'修改成功'),JSON_UNESCAPED_UNICODE);
		}else{
			return json_encode(new jsonBean(401,array(),'修改失败'),JSON_UNESCAPED_UNICODE);
		}
		
	}
}
?>