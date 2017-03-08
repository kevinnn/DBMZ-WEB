<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
Class adminModel extends modelApi{
	private static $bean = 'adminBean';
	private static $table = 'Admin';

	public static function getTable () {
		return self::$table;
	}

	public static function getAdminByUserNameAndPassword ($username,$encryptedPass) {
		$sql = "select * from ".self::$table." where username='$username' and encryptedPass='$encryptedPass'";
		return mysql::query($sql,self::$bean);
	}

	public static function getAdminByUserName ($username) {
		$sql = "select * from ".self::$table." where username='$username'";
		return mysql::query($sql,self::$bean);
	}

	/*后台接口*/
	public static function getAdminList () {
		$sql = "select * from ".self::$table;
		return mysql::query($sql,self::$bean);
	}
}
?>