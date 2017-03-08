<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
Class areaModel extends modelApi {

	private static $table = 'Area';
	private static $bean = 'areaBean';

	public static function getAllByCity($cityId) {
		$sql = "select * from Area where father='$cityId'";
		return mysql::query($sql,self::$bean);
	}
}
?>