<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
Class showModel extends modelApi {
	private static $table = "Show";
	private static $bean = "showBean";

	public static function getTable() {
		return self::$table;
	}

	public static function getById($showId) {
		$sql = "select * from `Show` where id='$showId'";
		return mysql::query($sql,self::$bean);
	}

	public static function getAll($firstId,$lastId,$limit) {
		if ($lastId != null) {
			$sql = "select u.avatorUrl,y.endTime,s.id as showId,s.title,y.result,s.yungouId,u.userName,s.userId,s.createdTime,s.content,s.imgUrls,p.title as productTitle,y.productId,s.term from `Show` s,Yungou y,`User` u,Product p where s.id<'$lastId' and s.yungouId=y.id and s.userId=u.id and p.id=y.productId and s.isApproved = 1 order by s.id desc limit $limit";
			
		} else if ($firstId != null) {
			$sql = "select u.avatorUrl,y.endTime,s.id as showId,s.title,y.result,s.yungouId,u.userName,s.userId,s.createdTime,s.content,s.imgUrls,p.title as productTitle,y.productId,s.term from `Show` s,Yungou y,`User` u,Product p where s.id>'$firstId' and s.yungouId=y.id and s.userId=u.id and p.id=y.productId and s.isApproved = 1 order by s.id asc limit $limit";
		} else {
		 	$sql = "select u.avatorUrl,y.endTime,s.id as showId,s.title,y.result,s.yungouId,u.userName,s.userId,s.createdTime,s.content,s.imgUrls,p.title as productTitle,y.productId,s.term from `Show` s,Yungou y,`User` u,Product p where s.yungouId=y.id and s.userId=u.id and p.id=y.productId and s.isApproved = 1 order by s.id desc limit $limit";
		}
		return mysql::queryTables($sql);
	}

	public static function getCountAll() {
		$sql = "select count(*) as count from `Show`,`User`,`Order`,Product where `Show`.userId = User.id and `Order`.id = `Show`.orderId and `Order`.productId = Product.id ";
		return mysql::count($sql);
	}

	public static function getByProduct($productId,$lastId,$firstId,$limit) {
		if ($lastId != null) {
			$sql = "select s.id as showId, s.yungouId,y.productId,s.userId,u.avatorUrl,u.userName,s.title,s.createdTime,s.content,s.imgUrls from `Show` s,`User` u,Yungou y,Product p where p.id='$productId' and y.productId=p.id and s.yungouId=y.id and s.userId=u.id and s.id<'$lastId' order by s.id desc limit $limit";
			
		} else if ($firstId != null) {
			$sql = "select s.id as showId, s.yungouId,y.productId,s.userId,u.avatorUrl,u.userName,s.title,s.createdTime,s.content,s.imgUrls from `Show` s,`User` u,Yungou y,Product p where p.id='$productId' and y.productId=p.id and s.yungouId=y.id and s.userId=u.id and s.id>'$firstId' order by s.id asc limit $limit";
		} else {
			$sql = "select s.id as showId,s.yungouId,y.productId,s.userId,u.avatorUrl,u.userName,s.title,s.createdTime,s.content,s.imgUrls from `Show` s,`User` u,Yungou y,Product p where p.id='$productId' and y.productId=p.id and s.yungouId=y.id and s.userId=u.id order by s.id desc limit $limit";
		}
		return mysql::queryTables($sql);
	}

	public static function getCountByProduct($productId) {
		$sql = "select count(*) as count from `Show` s,Yungou y,Product p where p.id='$productId' and y.productId=p.id and s.yungouId=y.id";
		return mysql::count($sql);
	}

	public static function getDetail ($showId,$yungouId) {
		if ($showId != null) {
			$sql = "select p.id as productId,u.id as userId,y.id as yungouId,o.id as orderId,s.userId,u.userName,u.avatorUrl,s.title,s.createdTime,y.result,y.endTime,p.thumbnailUrl,p.title as productTitle,p.price,y.term,s.content,s.imgUrls,sum(o.count) as count from `Show` s,`User` u,Yungou y,Product p,`Order` o where s.id='$showId' and s.yungouId=y.id and s.userId=u.id and y.productId=p.id and o.yungouId=y.id and o.userId=u.id and o.status=1";

		} else {
			$sql = "select p.id as productId,u.id as userId,y.id as yungouId,o.id as orderId,s.userId,u.userName,u.avatorUrl,s.title,s.createdTime,y.result,y.endTime,p.thumbnailUrl,p.title as productTitle,p.price,y.term,s.content,s.imgUrls,sum(o.count) as count from `Show` s,`User` u,Yungou y,Product p,`Order` o where s.yungouId='$yungouId' and s.yungouId=y.id and s.userId=u.id and y.productId=p.id and o.yungouId=y.id and o.userId=u.id and o.status=1";
		}
		mysql::execute("SET sql_mode = ''");
		return mysql::queryTables($sql);
	}

	public static function getAllByUser($userId,$limit,$page) {
		$start = ($page-1)*$limit;
		$sql = "select s.id as showId,s.title,y.result,s.yungouId,s.createdTime,s.content,s.imgUrls,p.title as productTitle,y.productId,s.term from `Show` s,Yungou y,Product p where s.userId='$userId' and s.yungouId=y.id and p.id=y.productId and s.isApproved=1 order by createdTime desc limit $start,$limit";
		return mysql::queryTables($sql);
	}
	
	public static function getOwnAllByUser($userId,$limit,$page) {
		$start = ($page-1)*$limit;
		$sql = "select s.id as showId,s.title,y.result,s.yungouId,s.createdTime,s.content,s.imgUrls,p.title as productTitle,y.productId,s.term from `Show` s,Yungou y,Product p where s.userId='$userId' and s.yungouId=y.id and p.id=y.productId order by createdTime desc limit $start,$limit";
		return mysql::queryTables($sql);
	}

	public static function getCountAllByUser($userId) {
		$sql = "select count(*) as count from `Show` where userId='$userId'";
		return mysql::count($sql);
	} 

	public static function updateLogisticsStatusByYungou($yungouId,$logisticsStatus) {
		$sql = "update WinOrder set logisticsStatus='$logisticsStatus' where yungouId='$yungouId'";
		return mysql::execute($sql);
	}

	public static function getCountByYungou($yungouId) {
		$sql = "select count(*) as count from `Show` where yungouId='$yungouId'";
		return mysql::count($sql);
	}
	
	public static function getUnshowByUser($userId,$limit,$page){
		$start = ($page-1)*$limit;		
		$sql = "SELECT p.thumbnailUrl,p.title,y.term,y.id,win.orderId from (select orderId,yungouId,productId,confirmReceivingTime from `WinOrder` where userId=$userId and logisticsStatus=3 and orderId NOT IN (select orderId from `Show` where userId=$userId)) as win,Yungou y,Product p WHERE win.productId=p.id and win.yungouId=y.id order by confirmReceivingTime desc limit $start,$limit";
		$result = mysql::queryTables($sql);
		$result1 = array();
		
		foreach($result as $key => $value){
			$tmp = array();
			foreach($value as $key1 => $value1){
				switch($key1) {
					case 'thumbnailUrl':
						$tmp['productImage'] = $value1;
						break;						
					case 'title':
						$tmp['productTitle'] = $value1;
						break;
					case 'term':
						$tmp['term'] = $value1;
						break;
					case 'id':
						$tmp['yungouId'] = $value1;
						break;
					case 'orderId':
						$tmp['orderId'] = $value1;
						break;						
				}				
			}
			array_push($result1,$tmp);
		}
		return $result1;
	}

	/*后台接口*/
	public static function getShowList($page) {
		$start = ($page-1)*20;
		$sql = "select `Show`.id,isApproved,userName,term,`Show`.orderId,Product.title as proTitle,`Show`.title as showTitle,`Show`.createdTime as showCreatedTime from `Show`,`User`,`Order`,Product where `Show`.userId = User.id and `Order`.id = `Show`.orderId and `Order`.productId = Product.id order by `Show`.createdTime limit {$start},20";
		// echo $sql;
		// exit();
		return mysql::query($sql,self::$bean);
	}
	
	public static function getShowInfo($id) {
		$sql = "select * from `Show` where id = {$id}";
		return mysql::queryTables($sql);
	}
	
	
	public static function approveShow($id){
		$sql = "update `Show` set isApproved = 1 where id = {$id}";
		return mysql::execute($sql);
	}
	
	public static function setIsApproved($id,$isApproved){
		$sql = "update `Show` set isApproved = {$isApproved} where id = {$id}";
		return mysql::execute($sql);
	}
}
?>