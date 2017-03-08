<?php
Class modelApi {
	public static function insertData ($dataArr, $table) {
		$sql = "insert into `".$table;
		$keySql = "";
		$valueSql = "";
		foreach ($dataArr as $key => $value) {
			if ($value === '') {
				return false;
			}
			$keySql .= $key.",";
			$valueSql .= "'$value',";
		}
		$keySql = substr($keySql, 0,strlen($keySql)-1);
		$valueSql = substr($valueSql, 0,strlen($valueSql)-1);
		$sql .= "` (".$keySql.")"." values (".$valueSql.")";
		return mysql::execute($sql);
	}

	public static function deleteData ($id,$table) {
		$sql = "delete from `".$table."` where id='$id'";
		return mysql::execute($sql);
	}

	public static function updateData ($id,$dataArr,$table) {
		$sql = "update `".$table."` set";
		foreach ($dataArr as $key => $value) {
			$sql .= " $key='$value'";
			$sql .= ",";
		}
		$sql = substr($sql, 0,strlen($sql)-1);
		$sql .= " where id='$id'";
		return mysql::execute($sql);
	}

	//开启事务
	public static function startTransaction() {
		mysql::startTransaction();
	}
	//结束提交事务
	public static function commitTransaction() {
		mysql::commitTransaction();
	}

	public static function rollBackTransaction() {
		mysql::rollBackTransaction();
	}

	public static function getLastInsertId() {
		$sql = "select last_insert_id() as id";
		return mysql::queryTables($sql);
	}
}
?>