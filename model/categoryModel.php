<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
Class categoryModel extends modelApi {

	private static $bean = 'categoryBean';
	private static $table = 'Category';

	public static function getTable() {
		return self::$table;
	}

	public static function getAll () {
		$sql = "select * from ".self::$table." where isOn=1 order by id asc";
		return mysql::query($sql,self::$bean);
	}

	public static function getById ($id) {
		$sql = "select * from ".self::$table." where id='$id' ";
		return mysql::query($sql,self::$bean);
	}

	public static function getByIsOnIndex() {
		$sql = "select * from ".self::$table." where isOn=1 and isOnIndex=1";
		return mysql::query($sql,self::$bean);
	}
}
?>