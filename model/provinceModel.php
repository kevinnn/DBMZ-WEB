<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
Class provinceModel extends modelApi {

	private static $table = 'Province';
	private static $bean = 'provinceBean';

	public static function getAll() {
		$sql = "select * from Province";
		return mysql::query($sql,self::$bean);
	}
}
?>