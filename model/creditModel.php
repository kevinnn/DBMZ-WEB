<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
Class creditModel extends modelApi {

	private static $table = 'Credit';
	private static $bean = 'creditBean';

	public static function getTable() {
		return self::$table;
	}

	public static function isSignIn($userId,$now) {
		$sql = "select count(*) as count from Credit where userId='$userId' and time='$now' and type = 1";
		return mysql::count($sql);
	}
	public static function addCredits($userId,$count) {
		$sql = "update `User` set credits=credits+$count where id='$userId'";
		return mysql::execute($sql);
	}
	
	//增加用户的积分并添加积分变动记录
	public static function addCreditRecord($userId,$amount,$type = 2) {		
		date_default_timezone_set("PRC");
		$now = date("Y-m-d");
		$result = true;
		$result &= creditModel::insertData(array('userId'=>$userId,'time'=>$now,'type'=>$type,'amount'=>$amount),creditModel::getTable());
		$result &= creditModel::addCredits($userId,$amount);
		return $result;
	}

	public static function getRecordByUser($userId,$page,$limit) {
		$start = ($page-1)*$limit;
		$sql = "select * from Credit where userId='{$userId}' order by id desc limit $start,$limit";
		return mysql::query($sql,self::$bean);
	}
}
?>