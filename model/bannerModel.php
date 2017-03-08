<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
Class bannerModel extends modelApi {
	private static $bean = 'bannerBean';
	private static $table = "Banner";

	public static function getTable() {
		return self::$table;
	}

	public static function getAllIsOn () {
		$sql = "select * from ".self::$table." where isOn = 1";
		return mysql::query($sql,self::$bean);
	}
	public static function getAll () {
		$sql = "select * from ".self::$table;
		return mysql::query($sql,self::$bean);
	}
	public static function getById($id) {
		$sql = "select * from ".self::$table." where id='$id'";
		return mysql::query($sql,self::$bean);
	}
}
?>