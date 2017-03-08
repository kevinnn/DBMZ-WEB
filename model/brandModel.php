<?php
include __ROOT__.'/database/mysql.php';
include __ROOT__.'/model/modelApi.php';
Class brandModel extends modelApi {

	private static $bean = 'brandBean';
	private static $table = 'Brand';

	public static function getTable() {
		return self::$table;
	}

	public static function getAllBrand() {
		$sql = "select * from Brand";
		return mysql::query($sql,self::$bean);
	}

	public static function getById($id) {
		$sql = "select * from Brand where id='$id'";
		return mysql::query($sql,self::$bean);
	}

	public static function getBrandList ($page) {
		$start = ($page-1)*20;
		$sql = "select * from Brand order by id asc limit $start,20";
		return mysql::query($sql,'brandBean');
	}
	
	public static function getBrandCount () {
		$sql = "select count(*) as count from Brand";
		return mysql::count($sql);
	}
}
?>