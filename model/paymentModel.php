<?php
include_once __ROOT__.'/database/mysql.php';
Class paymentModel {
	private static $balanceSql = "";
	private static $sqls = array();
	//开启事务
	public static function startTransaction() {
		mysql::startTransaction();
	}
	//结束提交事务
	public static function endTransaction() {
		mysql::commitTransaction();
	}

	public static function clearSqls() {
		$sqls = array();
		$balanceSql = "";
	}

	public static function getYungouById($id) {
		$sql = "select id,saleCount,term from Yungou where id='$id' for update";
		return mysql::query($sql,'yungouBean');
	}

	public static function execute() {
		if (self::$balanceSql != "");
			array_push(self::$sqls,self::$balanceSql);
		// return json_encode(self::$sqls);

		$result = mysql::transaction(self::$sqls);
		self::clearSqls();
		return $result;
	}

	public static function updateUserBalance($userId,$balance) {
		$sql = "update `User` set balance='$balance' where id='$userId'";
		array_push(self::$sqls,$sql);
	}

	public static function insertBalanceRecording($type,$userId,$cashierid,$count) {
		if (self::$balanceSql == "")
			self::$balanceSql = "insert into Balance (userId,type,tradeId,amount) values('$userId','$type','$cashierid','$count')";
		else
			self::$balanceSql .= ",('$userId','$type','$cashierid','$count')";
	}

	public static function getBalanceRecording($userId,$tradeId) {
		$sql = "select * from Balance where userId='$userId' and tradeId='$tradeId'";
		return mysql::query($sql,'balanceBean');
	}

	public static function updateCashierIsPay($userId,$cashierid,$balancePay,$payment) {
		date_default_timezone_set('PRC');
		$buyTime = date('Y-m-d H:i:s');
		$t = explode('.', microtime(true));
		while(strlen($t[1]) < 4) {
			$t[1] .= '0';
		}
		$buyTime .= '.'.$t[1];
		$buyTime = substr($buyTime, 0,strlen($buyTime)-1);
		if ($payment != null) {
			$sql = "update Cashier set isPay=1,buyTime='$buyTime',balancePay='$balancePay',payment='$payment' where cashierid='$cashierid' and userId='$userId'";
		} else {
			$sql = "update Cashier set isPay=1,buyTime='$buyTime',balancePay='$balancePay' where cashierid='$cashierid' and userId='$userId'";
		}
		mysql::execute($sql);
	}
	public static function updateOrderStatus($orderId) {
		$sql = "update `Order` set status=1 where id='$orderId'";
		mysql::execute($sql);
	}

	public static function updateYungouSaleCount($yungouId,$count) {
		$sql = "update Yungou set saleCount=saleCount+$count where id='$yungouId'";
		mysql::execute($sql);
	}

	public static function distributionNumbers($orderId,$saleCount,$count) {
		$startNumber = 10000000+$saleCount+1;
		$endNumber = $startNumber+($count-1);
		$sql = "update `Order` set numberStart='$startNumber',numberEnd='$endNumber' where id='$orderId'";
		mysql::execute($sql);
	}

	public static function updateCashierPayStatus($userId,$cashierid,$payStatus) {
		$sql = "update Cashier set payStatus='$payStatus' where cashierid='$cashierid' and userId='$userId'";
		mysql::execute($sql);
		
	}

}
?>