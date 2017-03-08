<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
Class userModel extends modelApi {
	private static $bean = 'userBean';
	private static $table = 'User';

	public static function getTable() {
		return self::$table;
	}

	public static function getUserByPhoneNumberAndPassword($phoneNumber, $encryptedPass) {
		$sql = "select * from User where phoneNumber='$phoneNumber' and encryptedPass='$encryptedPass'";
		return mysql::query($sql,self::$bean);
	}
	
	public static function getUserByIdAndPassword($userid, $encryptedPass) {
		$sql = "select * from User where id='$userid' and encryptedPass='$encryptedPass'";
		return mysql::query($sql,self::$bean);
	}
	
	public static function getUserByWxOpenid($openid) {
		$sql = "select * from User where `type`=2 and `openId`='$openid'";
		return mysql::query($sql,self::$bean);
	}
	public static function getUserByInviteCode($inviteCode){
		$sql = "select * from User where code = '{$inviteCode}'";
		return mysql::query($sql,self::$bean);
	}

	public static function getUserById($id) {
		$sql = "select * from User where id='$id'";
		return mysql::query($sql,self::$bean);
	}

	public static function getUserList($page) {
		$start = ($page-1)*20;
		$sql = "select * from User limit $start,20";
		return mysql::query($sql,self::$bean);
	}
	public static function getUserCount() {
		$sql = "select count(*) as count from User";
		return mysql::count($sql);
	}
	public static function getUserByPhoneNumber ($phoneNumber) {
		$sql = "select * from User where phoneNumber='$phoneNumber'";
		return mysql::query($sql,self::$bean);
	}
	public static function getSelectUserByUserName ($userName) {
		$sql = "select * from User where userName like '%$userName%'";
		return mysql::query($sql,self::$bean);
	}
	public static function getSelectUserByPhoneNumber ($phoneNumber) {
		$sql = "select * from User where phoneNumber like '%$phoneNumber%'";
		return mysql::query($sql,self::$bean);
	}
	public static function getWinUserByOrder ($orderId) {
		$sql = "select ip,loginArea,avatorUrl,userName,o.userId,buyTime from `User` u,Cashier c,`Order` o where o.id='$orderId' and u.id=o.userId and c.userId=o.userId and c.cashierid=o.cashierid";
		return mysql::queryTables($sql);
	}

	public static function getUserByUser($userId) {
		$sql = "select avatorUrl,userName,id from `User` where id='$userId'";
		return mysql::query($sql,self::$bean);
	}

	public static function updateCreditByCode($code) {
		$sql = "update `User` set credits=credits+100 where code='$code'";
		return mysql::execute($sql);
	}
	
	public static function updatePassword($userId,$newpwd){
		$sql = "update `User` set encryptedPass = '{$newpwd}' where id = {$userId}";
		return mysql::execute($sql);
	}
	
	public static function updateNickname($userId,$nickname){
		$sql = "update `User` set userName = '{$nickname}' where id = {$userId}";
		return mysql::execute($sql);
	}
	
	public static function updateDelegate($userid) {
		$sql = "update `User` set isDelegate=1 where id={$userid}";
		return mysql::execute($sql);
	}

	public static function getUserByCode($code) {
		$sql = "select * from `User` where code='$code'";
		return mysql::query($sql,self::$bean);
	}

	public static function updateLoginTime($userId) {
		date_default_timezone_set("PRC");
		$now = date('Y-m-d H:i:s');
		$sql = "update `User` set `loginTime`='$now' where id = '$userId'";
		return mysql::execute($sql);
	}
	public static function suggest($userId,$theme,$email,$content){
		date_default_timezone_set("PRC");
		$now = date('Y-m-d H:i:s');
		$sql = "insert into `Suggest` set `userId`=$userId,`theme`=$theme,`email`='$email',`content`='$content',`time`='$now'";
		return mysql::execute($sql);
	}
	
	public static function notShowHb($userId) {
		$sql = "update `User` set showHb = 0 where id = {$userId}";
		return mysql::execute($sql);
	}
}
?>