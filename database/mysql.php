<?php
error_reporting(E_ALL ^ E_DEPRECATED);
Class mysql {
	public static function connect() {
		$conn = mysql_connect(HOST,USERNAME,PASSWORD) or die("connect failed".mysql_error());
		mysql_query("set character set 'utf8'");
		mysql_query("set names 'utf8'");
		mysql_select_db(DATABASE,$conn);
		return $conn;
	}
	public static function close($conn) {
		mysql_close($conn);
	}

	public static function query($sql,$bean) {
		include_once __ROOT__.'/bean/'.$bean.'.php';
		$conn = self::connect();
		$result = mysql_query($sql,$conn);
		$arr = array();
		while ($row = mysql_fetch_array($result)) {
			$obj = new $bean ($row);
			array_push($arr, $obj);
		}
		return $arr;
	}

	public static function queryTables($sql) {
		$conn = self::connect();
		$result = mysql_query($sql,$conn);
		$arr = array();
		while ($row = mysql_fetch_array($result)) {
			array_push($arr, $row);
		}
		return $arr;
	}

	public static function count($sql) {
		$conn = self::connect();
		$result = mysql_query($sql,$conn);
		$count = 0;
		while ($row = mysql_fetch_array($result)) {
			$count = $row['count'];
		}
		return $count;
	}

	public static function sum($sql) {
		$conn = self::connect();
		$result = mysql_query($sql,$conn);
		$sum = 0;
		while ($row = mysql_fetch_array($result)) {
			$sum = $row['sum'];
		}
		return $sum;
	}

	public static function execute($sql) {
		$conn = self::connect();
		$result = mysql_query($sql,$conn);
		if ($result === true) {
			return true;
		} else {
			return false;
		}
	}

	public static function transaction($sqls) {
		// return json_encode($sqls);
		$conn = self::connect();
		mysql_query("BEGIN");
		$result = true;
		foreach ($sqls as $key => $sql) {
			$result &= mysql_query($sql);
		}
		if ($result) {
			mysql_query("COMMIT");
		} else {
			mysql_query("ROLLBACK");
		}
		mysql_query("END");
		return $result;
	}

	public static function startTransaction() {
		$conn = self::connect();
		mysql_query("BEGIN");
	}

	public static function rollBackTransaction() {
		mysql_query("ROLLBACK");
		mysql_query("END");
	}

	public static function commitTransaction() {
		mysql_query("COMMIT");
		mysql_query("END");
	}
}
?>