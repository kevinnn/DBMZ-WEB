<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
Class winOrderModel extends modelApi {

	private static $bean = 'winOrderBean';
	private static $table = 'WinOrder';

	public static function getTable() {
		return self::$table;
	}

	public static function getWinOrderById($winOrderId,$userId) {
		$sql = "select w.deliveryTime,w.confirmReceivingTime,w.id as winOrderId,w.yungouId,w.addressId,p.id as productId,w.logisticsCompany,expressTradeId,y.term,p.thumbnailUrl,w.logisticsStatus,p.price,p.title,y.result,y.endTime,w.confirmAddressTime from WinOrder w,Product p,Yungou y,`Order` o where w.id='$winOrderId' and w.userId='$userId' and o.id=w.orderId and p.id=o.productId and y.id=o.yungouId";
		return mysql::queryTables($sql);
	}

	public static function getWinOrderForAddress($winOrderId,$userId) {
		$sql = "select addr.receiver,addr.phoneNumber,p.province,c.city,a.area,addr.street,addr.postCode from WinOrder w,Address addr,Province p,City c,Area a where w.id=$winOrderId and addr.id=w.addressId and w.userId='$userId' and p.provinceID=addr.provinceID and c.cityID=addr.cityID and a.areaID=addr.areaID";
		return mysql::queryTables($sql);
	}

	public static function confirmAddress($userId,$winOrderId,$addressId) {
		date_default_timezone_set('PRC');
		$now = date('Y-m-d H:i:s');
		$sql = "update WinOrder set logisticsStatus=1,addressId='$addressId',confirmAddressTime='$now' where id='$winOrderId' and userId='$userId' and logisticsStatus=0";
		return mysql::execute($sql);
	}

	public static function confirmGoods($winOrderId,$userId,$confirmReceivingTime) {
		$sql = "update WinOrder set logisticsStatus=3,confirmReceivingTime='$confirmReceivingTime' where id='$winOrderId' and userId='$userId' and logisticsStatus=2";
		return mysql::execute($sql);
	}

	public static function updateLogisticsStatus($winOrderId,$userId,$logisticsStatus) {
		$sql = "update WinOrder set logisticsStatus='$logisticsStatus' where id='$winOrderId' and userId='$userId'";
		return mysql::execute($sql);
	}

	public static function getByAddressId($addressId) {
		$sql = "select count(*) as count from WinOrder where addressId='$addressId'";
		return mysql::count($sql);
	}
	public static function winOrderList($page,$logisticsStatus) {
		$start = ($page-1)*20;
		$sql = "select logisticsStatus,Cashier.payment,`Order`.productId,Product.title,userName,`Order`.count as parCount,addressId,buyTime,endTime,confirmAddressTime,`Order`.id as orderId from `Order`,Product,`User`,Cashier,WinOrder,Yungou where `Order`.yungouId=Yungou.id and`Order`.cashierid=Cashier.cashierid and `Order`.userId=Cashier.userId and `Order`.productId=Product.id and `Order`.userId=`User`.id and WinOrder.orderId=`Order`.id";
		if ($logisticsStatus != -1) {
			$sql .= " and WinOrder.logisticsStatus='$logisticsStatus'";
		}


		
			$sql .= " limit $start,20";

		// echo $sql;
		// exit();
		return mysql::queryTables($sql);
	}
	public static function winOrderCount($logisticsStatus) {
		$sql = "select count(*) as count from `Order`,Product,`User`,Cashier,WinOrder,Yungou where `Order`.yungouId=Yungou.id and`Order`.cashierid=Cashier.cashierid and `Order`.userId=Cashier.userId and `Order`.productId=Product.id and `Order`.userId=`User`.id and WinOrder.orderId=`Order`.id";
		if ($logisticsStatus != -1) {
			$sql .= " and WinOrder.logisticsStatus={$logisticsStatus}";
		}

		// echo $sql;
		// exit();
		return mysql::count($sql);
	}
	
}
?>