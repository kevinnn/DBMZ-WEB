<?php
include_once __ROOT__.'/database/mysql.php';
Class globalModel {
	private static $bean = "globalBean";
	private static $table = "Global";

	public static function getTable() {
		return self::$table;
	}

	public static function getGlobal($key) {
		$sql = "select * from `Global` where `key`='$key'";
		return mysql::query($sql,self::$bean);
	}
	public static function setGlobal($key, $value) {
		$arr = self::getGlobal($key);
		if (count($arr) > 0) {
			$sql = "update `Global` set value='$value' where `key`='$key'";
		} else {
			$sql = "insert into `Global` (`key`,value) values('$key','$value')";
		}
		return mysql::execute($sql);
	}
	public static function clearGlobal($key) {
		$sql = "delete from `Global` where `key`='$key'";
		return mysql::execute($sql);
	}
}
?>