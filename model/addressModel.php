<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
Class addressModel extends modelApi {

	private static $bean = 'addressBean';
	private static $table = 'Address';

	public static function getTable() {
		return self::$table;
	}

	public static function updateUndefault($userId) {
		$sql = "update Address set status=0 where status=1 and userId='$userId'";
		return mysql::execute($sql);
	}

	public static function getAllByUser($userId) {
		$sql = "select addr.id,addr.provinceID,addr.areaID,addr.cityID,addr.street,addr.postCode,addr.receiver,addr.idCode,addr.phoneNumber,addr.status,p.province,a.area,c.city from Address addr,Province p,Area a,City c where addr.isRemove=0 and addr.userId='$userId' and addr.provinceID=p.provinceID and addr.areaID=a.areaID and addr.cityID=c.cityID order by id";
		return mysql::queryTables($sql);
	}

	public static function remove($userId,$id) {
		$sql = "delete from Address where id='$id' and userId='$userId'";
		return mysql::execute($sql);
	}

	public static function getById($userId,$id) {
		$sql = "select addr.id,addr.provinceID,addr.areaID,addr.cityID,addr.street,addr.postCode,addr.receiver,addr.idCode,addr.phoneNumber,addr.status,p.province,a.area,c.city from Address addr,Province p,Area a,City c where addr.id='$id' and addr.isRemove=0 and addr.userId='$userId' and addr.provinceID=p.provinceID and addr.areaID=a.areaID and addr.cityID=c.cityID";
		return mysql::queryTables($sql);
	}

	public static function getLastId($userId) {
		$sql = "select id from Address where userId='$userId' order by id desc limit 1";
		return mysql::query($sql,self::$bean);
	}

	public static function getAllAddress() {
		$sql = "select * from Address";
		return mysql::query($sql,self::$bean);
	}

	public static function updateIsRemove($addressId) {
		$sql = "update Address set isRemove=1 where id='$addressId'";
		return mysql::execute($sql);
	}

}
?>