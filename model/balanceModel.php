<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';

Class balanceModel extends modelApi {
	private static $bean = 'balanceBean';
	private static $table = 'Balance';

	public static function getTable () {
		return self::$table;
	}

	public static function getByTradeId ($tradeId,$userId) {
		$sql = "select * from Balance where tradeId='$tradeId' and userId='$userId'";
		return mysql::query($sql,self::$bean);
	}

	public static function getBalanceByPage ($userId,$page,$limit) {
		$start = ($page-1)*$limit;
		$sql = "select * from Balance where userId='$userId' order by time desc limit $start, $limit";
		return mysql::query($sql,self::$bean);
	}

	public static function getBalanceList ($page,$type,$userId) {
		$start = ($page-1) * 20;
		$sql = "select *,Balance.id as bid,`User`.id as userId,Balance.type as btype from Balance,`User` where `User`.id = Balance.userId order by Balance.time desc limit $start,20";
		if ($type != -1){
			$sql = "select *,Balance.id as bid,`User`.id as userId,Balance.type as btype from Balance,`User` where `User`.id = Balance.userId and Balance.type = {$type} order by Balance.time desc limit $start,20";
		}
		if ($userId != -1){
			$sql = "SELECT nowBalance FROM `Balance` where userId = {$userId} and nowBalance is not null order by time desc limit 1";
			$tmpres = mysql::queryTables($sql);
			$nowBalance = 0;
			if(count($tmpres)==1){
				$nowBalance = $tmpres[0]['nowBalance'];
			}
			$sql = "SELECT * FROM `Balance` where userId = {$userId} and nowBalance is null order by time asc";		
			$tmpres = mysql::queryTables($sql);
			$tmpinput = array();
			foreach ($tmpres as $key => $balancRecord) {
				if($balancRecord['type'] == 3){
					$nowBalance -= $balancRecord['amount'];
				}else{
					$nowBalance += $balancRecord['amount'];
				}
				$balancRecord['nowBalance'] =  $nowBalance;
				array_push($tmpinput,$balancRecord);
			}
			$sql = "UPDATE `Balance` SET nowBalance = CASE id "; 
			foreach ($tmpinput as $key => $balancRecord) { 
				$sql .= sprintf("WHEN %d THEN %f ", $balancRecord['id'], $balancRecord['nowBalance']); 
			}
			$sql .= "END WHERE nowBalance is null";
			mysql::execute($sql);
			$sql = "select *,nowBalance,Balance.id as bid,`User`.id as userId,Balance.type as btype from Balance,`User` where `User`.id = Balance.userId and `User`.id = {$userId} order by Balance.time desc limit $start,20";
			$results = mysql::queryTables($sql);
			return $results;
		}else{
			$results = mysql::queryTables($sql);
			return $results;
		}
	}
	public static function getBalanceCount ($type,$userId) {
		$sql = "select count(*) as count from Balance,`User` where `User`.id = Balance.userId";
		if ($type != -1){
			$sql = "select count(*) as count from Balance,`User` where `User`.id = Balance.userId and Balance.type = {$type}";
		}
		if ($userId != -1){
			$sql = "select count(*) as count from Balance,`User` where `User`.id = Balance.userId and `User`.id = {$userId}";
		}
		return mysql::count($sql);
	}
	public static function addBalance ($userId,$amount,$type,$tradeId = "") {
		date_default_timezone_set("PRC");
		$now = date("Y-m-d H:i:s");
		$result = true;
		$sql = "update `User` set balance = (balance + {$amount}) where id = {$userId}";
		$result &= mysql::execute($sql);
		if ($tradeId == "") {
			$sql = "insert into `Balance` set userId = {$userId},time = '{$now}',amount = {$amount},type = {$type}";
		} else {
			$sql = "insert into `Balance` set userId = {$userId},time = '{$now}',amount = {$amount},type = {$type},tradeId ='{$tradeId}' ";
		}
		$result &= mysql::execute($sql);
		return $result;
	}
	public static function getHbRecords($userId) {
		$sql = "select `User`.userName,`User`.avatorUrl,`Balance`.id,`Balance`.time,`Balance`.amount,`Balance`.type from `Balance`,`Cashier`,`User` where `Balance`.userId = {$userId} and `Balance`.type = 6 and `Balance`.tradeId = `Cashier`.cashierid and `Cashier`.UserId = `User`.id";
		// echo $sql;
		// exit();
		return  mysql::queryTables($sql);
	}
}
?>