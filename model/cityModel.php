<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
Class cityModel extends modelApi {

	private static $table = 'City';
	private static $bean = 'cityBean';

	public static function getAllByProvince($provinceId) {
		$sql = "select * from City where father='$provinceId'";
		return mysql::query($sql,self::$bean);
	}
}
?>