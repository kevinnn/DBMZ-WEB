<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
Class orderModel extends modelApi {
	private static $bean = 'orderBean';
	private static $table = 'Order';

	public static function getTable() {
		return self::$table;
	}

	public static function getOrderByUserAndYungou($userId,$yungouId) {
		$sql = "select * from `Order` where userId='$userId' and yungouId='$yungouId'";
		return mysql::query($sql,self::$bean);
	}

	public static function getNumbersByUserAndYungou($userId,$yungouId) {
		$sql = "select numberStart,numberEnd,buyTime,o.count from `Order` o,Cashier c where o.userId='$userId' and o.yungouId='$yungouId' and o.status=1 and c.isPay=1 and c.cashierid=o.cashierid and c.userId=o.userId";
		return mysql::queryTables($sql);
	}

	public static function getOrderByUser($userId) {
		$sql = "select p.thumbnailUrl as thumbnailUrl,p.title as title,p.price as price,p.id as productId,y.saleCount as saleCount,y.term as term,o.count as count,y.startTime as startTime,y.status as status,y.result as result,o.numberStart,o.numberEnd,o.createdTime as createdTime,o.isWin as isWin from `Order` o,Yungou y,Product p where o.userId='$userId' and o.status==0 and o.productId=p.id and o.yungouId=y.id order by y.status asc,o.createdTime desc";
		return mysql::queryTables($sql);
	}

	public static function getWinOrderById($orderId,$yungouId) {
		if ($orderId == null) {
			$sql = "select *,u.id as uid from `Order` o,`User` u,Yungou y where y.id='$yungouId' and y.orderId=o.id and o.isWin=1 and o.userId = u.id";
		} else {
			$sql = "select *,u.id as uid from `Order` o, `User` u,Yungou y where y.id=o.yungouId and o.id='$orderId' and o.isWin=1 and o.userId = u.id";
		}
		return mysql::queryTables($sql,self::$bean);
	}

	public static function getOrdering($limit) {
		$sql = "select o.id as id,p.id as productId,p.title as title,y.term as term,u.userName as userName,u.id as userId,o.createdTime as createdTime,o.count as count,p.price as price,u.avatorUrl as avatorUrl from `Order` o,`User` u,Product p,Yungou y where o.status=1 and o.userId=u.id and o.productId=p.id and o.yungouId=y.id order by o.createdTime desc limit $limit";
		return mysql::queryTables($sql,self::$bean);
	}

	public static function getOrderByUserIdAndCashierid($userId,$cashierid) {
		$sql = "select id,count,productId,yungouId from `Order` where userId='$userId' and cashierid='$cashierid'";
		return mysql::query($sql,self::$bean);
	}

	public static function getOrderByYungou($yungouId,$date,$page) {
		$start = ($page-1)*50;
		$sql = "select o.id as orderId,u.id as userId,buyTime,userName,avatorUrl,loginArea,ip,o.count,o.numberStart,o.numberEnd from `Order` o,Cashier c,`User` u where o.yungouId='$yungouId' and o.status=1 and u.id=o.userId and c.cashierid=o.cashierid and c.userId=o.userId and buyTime<'$date' order by buyTime desc limit $start,50";
		return mysql::queryTables($sql);
	}

	public static function getOrderByYungouMobile($yungouId,$limit,$buyTime) {
		if ($buyTime == null) {
			$sql = "select o.id as orderId,u.id as userId,buyTime,userName,avatorUrl,loginArea,ip,o.count,o.numberStart,o.numberEnd from `Order` o,Cashier c,`User` u where o.yungouId='$yungouId' and o.status=1 and u.id=o.userId and c.cashierid=o.cashierid and c.userId=o.userId order by buyTime desc limit $limit";
		} else {
			$sql = "select o.id as orderId,u.id as userId,buyTime,userName,avatorUrl,loginArea,ip,o.count,o.numberStart,o.numberEnd from `Order` o,Cashier c,`User` u where o.yungouId='$yungouId' and o.status=1 and u.id=o.userId and c.cashierid=o.cashierid and c.userId=o.userId and buyTime<'$buyTime' order by buyTime desc limit $limit";
		}
		return mysql::queryTables($sql);
	}

	public static function getOrderCountByYungouId($yungouId) {
		$sql = "select count(*) as count from `Order` o,Cashier c where o.yungouId='$yungouId' and o.status!=0 and c.userId=o.userId and c.cashierid=o.cashierid";
		return mysql::count($sql);
	}

	public static function getOrderCountByUserAndYungou($userId,$yungouId) {
		$sql = "select sum(count) as sum from `Order` where userId='$userId' and yungouId='$yungouId' and status=1";
		return mysql::sum($sql);
	}

	public static function getRecordByUser($userId,$page,$limit,$status) {
		mysql::execute("SET sql_mode = ''");
		$s = ($page-1)*$limit;
		$sql = "select y.result,o.isWin,y.orderId,p.thumbnailUrl,p.title,p.price,p.id as productId,y.term,y.id as yungouId,y.status as status,y.endTime,y.startTime,y.saleCount,p.isOn,count(DISTINCT y.id) from `Order` o,Cashier c,Product p,Yungou y where o.status=1 and o.userId='$userId' and o.userId=c.userId and o.cashierid=c.cashierid and o.productId=p.id and o.yungouId=y.id";
		if ($status == 3) {
			$sql .= " and y.status!=2";
		}
		else if ($status != -1) {
			$sql .= " and y.status='$status'";
		}
		$sql .= " group by y.id order by c.buyTime desc limit $s,$limit";
		return mysql::queryTables($sql);
	}

	public static function getCountRecordByUser($userId,$status) {
		$sql = "select count(DISTINCT o.yungouId) as count from `Order` o,Cashier c,Yungou y where o.userId='$userId' and o.status=1 and o.userId=c.userId and o.cashierid=c.cashierid and o.yungouId=y.id";
		if ($status != -1) {
			$sql .= " and y.status='$status'";
		}
		return mysql::count($sql);
	}

	public static function getBuyTimeByOrder($orderId) {
		$sql = "select c.buyTime from Cashier c,`Order` o where o.id='$orderId' and o.userId=c.userId and o.cashierid=c.cashierid";
		return mysql::queryTables($sql);
	}

	public static function getWinRecordByUser($userId,$page,$limit) {
		$start = ($page-1)*$limit;
		$sql = "select y.id as yungouId,p.id as productId,p.title,y.term,p.price,y.result,y.orderId,c.buyTime,y.endTime,w.logisticsStatus,w.id as winOrderId,p.thumbnailUrl from Product p,Yungou y,Cashier c,WinOrder w,`Order` o where o.userId='$userId' and o.isWin=1 and o.cashierid=c.cashierid and o.userId=c.userId and o.productId=p.id and o.yungouId=y.id and w.yungouId=y.id order by y.endTime desc limit $start,$limit";
		return mysql::queryTables($sql);
	}

	public static function getCountWinRecordByUser($userId) {
		$sql = "select count(*) as count from `Order` o where o.userId='$userId' and o.isWin=1";
		return mysql::count($sql);
	}
	
	public static function winOrderDetailByYungou($yungouId) {
		$sql = "select y.term,u.id as userId,u.avatorUrl,u.loginArea,u.userName,y.result,y.endTime,c.buyTime from `User` u,`Order` o,Yungou y,Cashier c where y.id=$yungouId and y.orderId=o.id and o.userId=u.id and o.cashierid=c.cashierid and o.userId=c.userId";
		return mysql::queryTables($sql);
	}


	/*后台接口*/
	public static function getOrderList($page,$productId,$isWin,$logisticsStatus,$status,$ispay,$userId) {
		$start = ($page-1)*20;
		if ($logisticsStatus == -1) {
			$sql = "select *,`Order`.id as orderId from `Order`,Product,`User`,Cashier where `Order`.cashierid=Cashier.cashierid and `Order`.userId=Cashier.userId and `Order`.productId=Product.id and `Order`.userId=`User`.id";
		} else {
			$sql = "select *,`Order`.id as orderId from `Order`,Product,`User`,Cashier,WinOrder where `Order`.cashierid=Cashier.cashierid and `Order`.userId=Cashier.userId and `Order`.productId=Product.id and `Order`.userId=`User`.id and WinOrder.orderId=`Order`.id and WinOrder.logisticsStatus='$logisticsStatus'";
		}

		if ($productId != 0) {
			$sql .= " and Order.productId='$productId'";
		}
		if ($isWin != 0) {
			$sql .= " and Order.isWin='$isWin'";
		}
		if ($status != 0) {
			$sql .= " and Order.status='$status'";
		}		
		if ($ispay != -1) {
			$sql .= " and Cashier.isPay={$ispay}";
		}
		if ($userId != -1) {
			$sql .= " and `User`.id={$userId}";
		}
		
			$sql .= " order by buyTime desc limit $start,20";

		// echo $sql;
		// exit();
		return mysql::queryTables($sql);
	}

	public static function getOrderCount($productId,$isWin,$logisticsStatus,$status,$ispay,$userId) {
		if ($logisticsStatus == -1) {
			$sql = "select count(*) as count from `Order`,Product,`User`,Cashier where `Order`.cashierid=Cashier.cashierid and `Order`.userId=Cashier.userId and `Order`.productId=Product.id and `Order`.userId=`User`.id";
		} else {
			$sql = "select count(*) as count from `Order`,Product,`User`,Cashier,WinOrder where `Order`.cashierid=Cashier.cashierid and `Order`.userId=Cashier.userId and `Order`.productId=Product.id and `Order`.userId=`User`.id and WinOrder.orderId=`Order`.id and WinOrder.logisticsStatus='$logisticsStatus'";
		}
		if ($productId != -1) {
			$sql .= " and productId='$productId'";
		}
		if ($isWin != 0) {
			$sql .= " and isWin='$isWin'";
		}
		if ($status != -1) {
			$sql .= " and status='$status'";
		}
		if ($ispay != -1) {
			$sql .= " and Cashier.isPay={$ispay}";
		}
		if ($userId != -1) {
			$sql .= " and `User`.id={$userId}";
		}
		// echo $sql;
		// exit();
		return mysql::count($sql);
	}
	public static function getOrderDetail($id) {
		$sql = "select * from `Order`,Yungou,Product,User,Cashier,WinOrder where `Order`.id='$id' and WinOrder.orderId = `Order`.id and `Order`.yungouId=Yungou.id and `Order`.productId=Product.id and `Order`.userId=`User`.id and `Order`.cashierid=Cashier.cashierid and `Order`.userId=Cashier.userId";
		return mysql::queryTables($sql);
	}

	public static function getOrderDetailForWinOrder($id) {
		$sql = "select * from WinOrder w where w.orderId='$id'";
		return mysql::query($sql,'winOrderBean');
	}
	public static function getOrderDetailForAddress($addressId) {
		$sql = "select * from Address addr,Province p,City c,Area a where addr.id='$addressId' and p.provinceID=addr.provinceID and c.cityID=addr.cityID and a.areaID=addr.areaID";
		return mysql::queryTables($sql);
	}
}
?>