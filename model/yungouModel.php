<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
require_once __ROOT__.'/wx/template_msg/templateModel.php';
require_once __ROOT__.'/wx/wx.Config.php';

Class yungouModel extends modelApi {
	private static $bean = 'yungouBean';
	private static $table = 'Yungou';

	public static function getTable() {
		return self::$table;
	}

	public static function getYungouById($id) {
		$sql = "select * from Yungou where id='$id'";
		return mysql::query($sql,self::$bean);
	}

	public static function getYungouByProductId($productId) {
		$sql = "select * from Yungou where status = 0 and productId = '$productId'";
		return mysql::query($sql,self::$bean);
	}

	public static function getAllNotEnd($search) {
		if ($search != null) {
			$sql = "select p.priority,p.id as productId,p.title,p.price,p.thumbnailUrl,p.singlePrice,y.saleCount,y.createdTime,y.term,y.id as yungouId from Product p,Yungou y where p.id=y.productId and y.status=0 and p.title like '%$search%' order by y.createdTime desc";
		} else {
			$sql = "select p.priority,p.id as productId,p.title,p.price,p.thumbnailUrl,p.singlePrice,y.saleCount,y.createdTime,y.term,y.id as yungouId from Product p,Yungou y where p.id=y.productId and y.status=0 order by y.createdTime desc";
		}
		return mysql::queryTables($sql);
	}

	public static function getAllByCategory($categoryId) {
		$sql = "select p.priority,p.id as productId,p.title,p.price,p.thumbnailUrl,p.singlePrice,y.saleCount,y.createdTime,y.term,y.id as yungouId from Product p,Yungou y where p.categoryId='$categoryId' and p.id=y.productId and y.status=0 order by y.createdTime desc";
		return mysql::queryTables($sql);
	}

	public static function getYungouByHot($limit) {
		$sql = "select p.title,p.price,y.saleCount,y.term,p.id as productId,y.id as yungouId,p.thumbnailUrl,p.singlePrice from Product p,Yungou y where p.id=y.productId and y.status=0 order by y.term desc limit $limit";
		return mysql::queryTables($sql);
	}

	public static function getYungouByProductIdArr($productIdArr,$limit) {
		if (count($productIdArr) == 0) return array();
		$sql = "select * from Yungou where status=0 and productId in (";
		foreach ($productIdArr as $key => $value) {
			$sql .= $value.",";
		}
		$sql = substr($sql, 0,strlen($sql)-1);
		$sql .= ")";
		if ($limit) {
			$sql .= " order by createdTime limit $limit";
		}
		return mysql::query($sql,self::$bean);
	}

	public static function getYungouByStartTime($limit) {
		$sql = "";
		if ($limit) {
			$sql = "select * from Yungou order by startTime desc limit $limit";
		} else {
			$sql = "select * from Yungou order by startTime desc";
		}
		return mysql::query($sql,self::$bean);
	}

	public static function getYungouByCreatedTime($productIdArr,$limit) {
		if (count($productIdArr) == 0) return array();
		$sql = "select * from Yungou where status=0 and productId in (";
		foreach ($productIdArr as $key => $value) {
			$sql .= $value.",";
		}
		$sql = substr($sql, 0,strlen($sql)-1);
		$sql .= ") order by createdTime desc limit $limit";
		return mysql::query($sql,self::$bean);
	}

	public static function getYungouByTerm($productId, $term) {
		$sql = "select * from Yungou where productId='$productId' and term='$term'";
		return mysql::query($sql,self::$bean);
	}

	public static function getYungouDetailByTerm($productId,$term) {
		$sql = "select *,y.id as yungouId from Yungou y,Product p where p.id='$productId' and y.productId='$productId' and y.term='$term'";
		return mysql::queryTables($sql);
	}

	public static function getYungouDetailByYungou($yungouId) {
		$sql = "select *,y.id as yungouId from Yungou y,Product p where y.id='$yungouId' and p.id=y.productId";
		return mysql::queryTables($sql);
	}

	public static function getFastNotStart($limit) {
		$sql = "select * from Yungou , Product where Yungou.status=0 and Yungou.productId=Product.id order by (Product.price - Yungou.saleCount) asc limit $limit";
		return mysql::queryTables($sql);
	}

	public static function getFastStart($startTime,$limit) {
		if ($startTime == null) {
			$sql = "select y.status,y.productId,y.term,p.title,p.thumbnailUrl,p.price,y.saleCount,y.startTime,y.endTime,y.orderId,y.result,y.id as yungouId from Yungou y,Product p where y.status!=0 and y.productId=p.id order by y.startTime desc limit $limit";
		} else {
			$sql = "select y.status,y.productId,y.term,p.title,p.thumbnailUrl,p.price,y.saleCount,y.startTime,y.endTime,y.orderId,y.result,y.id as yungouId from Yungou y,Product p where y.status!=0 and y.startTime<'$startTime' and y.productId=p.id order by y.startTime desc limit $limit";
		}
		return mysql::queryTables($sql);
	}
	public static function getYungouStatus($yungouId) {
		$sql = "select status from Yungou where id='$yungouId'";
		return mysql::query($sql,self::$bean);
	}

	public static function enough($yungouId,$productId,$term) {
		date_default_timezone_set('PRC');
		$startTime = date('Y-m-d H:i:s');
		$arr = explode(" ", $startTime);
		$ymd = explode("-", $arr[0]);
		$hms = explode(":", $arr[1]);
		$st = mktime($hms[0],$hms[1],$hms[2],$ymd[1],$ymd[2],$ymd[0]);
		$minute5 = mktime(21,59,40,$ymd[1],$ymd[2],$ymd[0]);
		$minuteEnd = mktime(9,59,40,$ymd[1],$ymd[2],$ymd[0]);
		$minuteStart = mktime(1,56,50,$ymd[1],$ymd[2],$ymd[0]);
		if ($st>$minuteStart && $st<=$minuteEnd) {
			$endTime = mktime(10,4,0,$ymd[1],$ymd[2],$ymd[0]);
		} else if ($st>$minuteEnd && $st<=$minute5){
			$range = ($st-240)%600;
			$endTime = ($st-$range)+600*(($range<=340)?1:2);
		} else {
			$range = ($st-240)%300;
			$endTime = ($st-$range)+300*(($range<=170)?1:2);
		}
		$endTime = date('Y-m-d H:i:s',$endTime);
		$sqls = array();
		//获取sscTerm
		$sql = "select sscTerm from SscTerm where time<'$endTime' order by time desc limit 1";
		$arr = mysql::query($sql,'sscTermBean');
		$obj = $arr[0];
		$sscTerm = $obj->sscTerm;
		//获取cashier的buyTime
		$sql = "select buyTime from Cashier c,`Order` o where o.yungouId = '$yungouId' and o.status=1 and c.userId=o.userId and c.cashierid = o.cashierid order by buyTime desc limit 1";
		$buyTime = mysql::queryTables($sql);
		$buyTime = $buyTime[0]['buyTime'];
		$sql = "select c.buyTime from Cashier c,`Order` o where c.buyTime<='$buyTime' and o.status=1 and o.userId=c.userId and o.cashierid=c.cashierid order by buyTime desc limit 50";
		$arr = mysql::query($sql,'cashierBean');
		$A = 0;
		foreach ($arr as $key => $item) {
			$buyTime = explode(" ", $item->buyTime);
			$hmsm = explode(".", $buyTime[1]);
			$hms = explode(":", $hmsm[0]);
			$time = intval($hms[0].$hms[1].$hms[2].$hmsm[1]);
			$A += $time;
		}
		$sql = "update Yungou set startTime='$startTime',endTime='$endTime',status=1,sscTerm='$sscTerm',A='$A' where id='$yungouId'";
		array_push($sqls, $sql);
		$sql = "delete from ShoppingCart where yungouId='$yungouId'";
		array_push($sqls, $sql);
		mysql::transaction($sqls);		
		
		$sql = "select type,openId,count from (select userId,sum(count) as count from `Order` where yungouId = {$yungouId} and status = 1 group by userId) uu,`User` u where u.id = uu.userId";
		$users = mysql::queryTables($sql);
		foreach ($users as $key => $user) {
			$sql = "select title from `Product` where id = {$productId}";
			$products = mysql::queryTables($sql);
			$productName = $products[0]['title'];								
			$participateCount = $user['count'];	
			$openid = $user['openId'];
			$msg = "$productName\n商品期号：$term\n参与人次：{$participateCount}人次";
			date_default_timezone_set("PRC");
			$startdate=date("Y-m-d H:i:s");
			$enddate=$endTime;
			$minute=floor((strtotime($enddate)-strtotime($startdate))/60);
			$second=floor((strtotime($enddate)-strtotime($startdate))%60);
			$timediff = "{$minute}分钟{$second}秒";
			$templateMsg = new templateMsg(WxConfig::appID, WxConfig::appsecret);
			$templateMsg->sendRevealTime($openid,"http://".SERVER_DOMAIN."/wx/login?returnAddress=/tab/profile?location=recordBuy","您参与的开奖已进入开奖倒计时！",$msg,$endTime,$timediff);
		}
		
		$sql = "select isOn from Product where id='$productId'";
		$arr = mysql::query($sql,self::$bean);
		if (count($arr) > 0) {
			$arr = $arr[0];
			if ($arr->isOn) {
				return self::insertData(array(
				'productId'=>$productId,
				'term'=>$term+1,
				'status'=>0,
				'saleCount'=>0
				),self::$table);
			}
		}
	}

	public static function getHistoryYungou($productId,$startTime,$limit) {
		if ($startTime == null) {
			$sql = "select term,result,endTime,orderId,status,startTime,id as yungouId from Yungou where productId='$productId' and status!=0 order by term desc limit $limit";
		} else {
			$sql = "select term,result,endTime,orderId,status,startTime,id as yungouId from Yungou where productId='$productId' and status!=0 and startTime<'$startTime' order by term desc limit $limit";
		}
		return mysql::query($sql,self::$bean);
	}

	public static function getHistoryYungouCount($productId) {
		$sql = "select count(*) as count from Yungou where productId='$productId' and status!=0";
		return mysql::count($sql);
	}

	public static function getHistoryMobileYungou($productId,$startTime,$limit) {
		if ($startTime == null) {
			$sql = "select term,result,endTime,orderId,status,startTime,id as yungouId from Yungou where productId='$productId' and status=2 order by term desc limit $limit";
		} else {
			$sql = "select term,result,endTime,orderId,status,startTime,id as yungouId from Yungou where productId='$productId' and status=2 and startTime<'$startTime' order by term desc limit $limit";
		}
		return mysql::query($sql,self::$bean);
	}
	public static function getYgForShow($userId,$yungouId){
		mysql::execute("SET sql_mode = ''");
		$sql = "select Product.title,`Order`.id as orderId,Yungou.term,Yungou.result,sum(`Order`.count) as parCount,Yungou.endTime  from Yungou,`Order`,Product where Yungou.id = {$yungouId} and `Order`.userId = {$userId} and Yungou.id = Order.yungouId and Yungou.productId = Product.id";
		// echo $sql;
		// exit();
		return mysql::queryTables($sql);	
	}

	public static function getWinYungou($yungouId) {
		$sql = "select term,result,endTime,orderId,status,startTime,id as yungouId from Yungou where id='$yungouId' and status=2";
		return mysql::query($sql,self::$bean);
	}

	public static function getCompute($yungouId) {
		$sql = "select buyTime from Cashier c,`Order` o where o.yungouId = '$yungouId' and o.status=1 and c.userId=o.userId and c.cashierid = o.cashierid order by buyTime desc limit 1";
		$buyTime = mysql::queryTables($sql);
		$buyTime = $buyTime[0]['buyTime'];
		$sql = "select c.buyTime,u.userName,o.userId,p.title,o.productId,y.term,o.count from Cashier c,`User` u,`Order` o,Product p,Yungou y where c.buyTime<='$buyTime' and o.status=1 and o.productId=p.id and o.yungouId=y.id and o.userId=u.id and o.userId=c.userId and o.cashierid=c.cashierid order by buyTime desc limit 55";
		return mysql::queryTables($sql);

	}

	public static function getResult($yungouId) {
		$sql = "select A,B,result,sscTerm from Yungou where id='$yungouId'";
		return mysql::query($sql,self::$bean);
	}


	public static function doProcess($now) {
		$isOk = true;
		$sql = "update Yungou set status=2 where status=1 and endTime<='$now' and isMalfunction=0";
		$isOk &= mysql::execute($sql);
		$sql = "select id,term,result from Yungou where status=2 and orderId is NULL";
		$arr = mysql::query($sql,self::$bean);
		if (count($arr) == 0) {
			return $isOk;
		}
		$insertSql = "insert into WinOrder (orderId,yungouId,productId,userId) ";
		$valueSql = "";
		foreach ($arr as $key => $yungou) {
			$yungouId = $yungou->id;			
			$result = $yungou->result;
			$sql = "update `Order` set isWin=1 where yungouId='$yungouId' and status=1 and numberStart<='$result' and numberEnd>='$result'";
			$isOk &= mysql::execute($sql);
			if (!$isOk) {
				return $sql;
			}
			$sql = "select id,productId,userId from `Order` where isWin=1 and yungouId='$yungouId'";
			$orders = mysql::query($sql,'orderBean');
			if (count($orders) > 0) {
				$order = $orders[0];
				$orderId = $order->id;
				$productId = $order->productId;
				$userId = $order->userId;
				//发送模板消息
				$sql = "select id,type,openId from `User` where id='$userId'";
				$users = mysql::query($sql,'userBean');
				$user = $users[0];
				if ($user->type == 2){
					$now = ((string)time())."000";
					$sql = "select id,title from `Product` where id='$productId'";
					$term = $yungou->term;
					$products = mysql::query($sql,'productBean');
					$product = $products[0];
					$productName = $product->title;
					$openid = $user->openId;
					$url = "http://".SERVER_DOMAIN."/wx/login?returnAddress=/tab/profile?location=recordWin";
					$msg = "{$productName}\n商品期号：{$term}\n中奖号码：{$result}";

					$templateMsg = new templateMsg(WxConfig::appID, WxConfig::appsecret);
					$templateMsg->sendWinMsg($openid,$url,"恭喜您！夺宝成功！","夺宝萌主",$msg);			
				}
				$sql = "update Yungou set orderId='$orderId' where id='$yungouId'";
				$isOk &= mysql::execute($sql);
				$valueSql .= "('$orderId','$yungouId','$productId','$userId'),";
			}
		}
		$valueSql = substr($valueSql, 0,strlen($valueSql)-1);
		$insertSql .= "values ".$valueSql;
		$isOk &= mysql::execute($insertSql);
		if (!$isOk) {
			return $insertSql;
		}
		// return $insertSql;
		return $isOk;
	}

	public static function getLastYungouByYungou($yungouId) {
		$sql = "select y2.id,y2.productId from Yungou y1,Yungou y2 where y1.id='$yungouId' and  y2.status=0 and y1.productId = y2.productId order by y2.createdTime desc limit 1";
		return mysql::query($sql,self::$bean);
	}

	public static function getTenZone($page,$limit) {
		$start = ($page-1)*$limit;
		$sql = "select p.id as productId,p.title,p.price,p.thumbnailUrl,p.singlePrice,y.saleCount,y.createdTime,y.term,y.id as yungouId from Product p,Yungou y where p.singlePrice=10 and p.id=y.productId and y.status=0 order by y.createdTime desc limit $start,$limit";
		return mysql::queryTables($sql);
	}

	public static function getCountTenZone() {
		$sql = "select count(*) as count from Product p,Yungou y where p.singlePrice=10 and p.id=y.productId and y.status=0";
		return mysql::count($sql);		
	}
	/*后台接口*/
	public static function getYungouList($page,$status,$productId) {
		$start = ($page-1)*20;
		$sql = "select *,Yungou.id as yungouId from Yungou,Product where Yungou.productId = Product.id";
		if ($status != -1) {
			$sql .= " and Yungou.status='$status'";
		}
		if ($productId != 0) {
			$sql .= " and Yungou.productId='$productId'";
		}
		$sql .= " limit $start,20";
		return mysql::queryTables($sql);
	}
	public static function getYungouCount($status,$productId) {
		$sql = "select count(*) as count from Yungou where 1=1";
		if ($status != -1) {
			$sql .= " and status='$status'";
		}
		if ($productId != 0) {
			$sql .= " and productId='$productId'";
		}
		return mysql::count($sql);
	}
	public static function getYungouDetail($id) {
		$sql = "select * from Yungou,Product where Yungou.id='$id' and Yungou.productId=Product.id";
		return mysql::queryTables($sql);
	}

	public static function getLastYungouByProduct($productId) {
		$sql = "select * from Yungou where productId='$productId' order by term desc limit 1";
		return mysql::query($sql,self::$bean);
	}
}
?>