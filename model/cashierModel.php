<?php
include_once __ROOT__.'/database/mysql.php';
Class cashierModel {
	private static $bean = 'cashierBean';
	private static $table = 'Cashier';

	public static function getTable() {
		return self::$table;
	}
	public static function addCashier($orders,$userId,$overTime,$cashierid) {
		$sqls = array();
		$counts = 0;
		$sql = "insert into `Order` (yungouId,productId,count,userId,cashierid) values";
		$deleteSql = "delete from ShoppingCart where userId='$userId' and";
		foreach ($orders as $key => $order) {
			$yungouId = $order['yungouId'];
			$productId = $order['productId'];
			$count = $order['count'];
			$counts += $count;
			$sql .= "('$yungouId','$productId','$count','$userId','$cashierid'),";
			$deleteSql .= " yungouId='$yungouId' or";
		}
		$sql = substr($sql, 0,strlen($sql)-1);
		$deleteSql = substr($deleteSql, 0,strlen($deleteSql)-3);
		array_push($sqls, $sql);
		array_push($sqls, $deleteSql);
		$sql = "insert into Cashier (cashierid,userId,count,overTime) values('$cashierid','$userId','$counts','$overTime')";
		array_push($sqls, $sql);
		return mysql::transaction($sqls);
	}
	public static function getCashierByCashierid($cashierid,$userId) {
		$sql = "select * from Cashier where cashierid='$cashierid'";
		if ($userId != null) {
			$sql .= " and userId='$userId'";
		}
		return mysql::query($sql,self::$bean);
	}

	public static function getDetailByCashier ($cashierid,$userId) {
		$sql = "select c.payStatus,o.status,c.buyTime,p.id as productId,p.title,y.term,o.count,o.numberStart,o.numberEnd,y.id as yungouId from Cashier c,Product p,Yungou y,`Order` o where c.cashierid='$cashierid' and c.userId='$userId' and o.cashierid=c.cashierid and o.userId=c.userId and c.isPay=1 and o.productId=p.id and o.yungouId=y.id";
		return mysql::queryTables($sql);
	}

	public static function getCashierMobile ($cashierid,$userId) {
		$sql = "select p.title,o.count,c.overTime,c.isPay from Product p,`Order` o,Cashier c where c.cashierid='$cashierid' and c.userId='$userId' and o.cashierid=c.cashierid and o.userId=c.userId and o.productId=p.id";
		return mysql::queryTables($sql);
	}
	public static function getPaidCashierCount ($userId) {
		$sql = "SELECT COUNT(id) as count from `Cashier` where userId = {$userId} and isPay = 1";
		return mysql::count($sql);
	}
}
?>