<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';

Class shoppingCartModel extends modelApi {
	private static $bean = 'shoppingCartBean';
	private static $table = 'ShoppingCart';

	public static function getTable() {
		return self::$table;
	}

	public static function getShoppingCart($userId,$yungouId) {
		$sql = "select * from ShoppingCart where userId='$userId' and yungouId='$yungouId'";
		return mysql::query($sql,self::$bean);
	}
	public static function getListByUserId($userId) {
		$sql = "select s.id as shoppingCartId,p.title,p.singlePrice,p.thumbnailUrl,s.amount,y.id as yungouId,y.saleCount,p.price from ShoppingCart s,Product p,Yungou y where s.userId='$userId' and s.yungouId=y.id and y.productId=p.id";
		return mysql::queryTables($sql);
	}
	public static function getIntoCartByUserId($userId) {
		$sql = "select *,s.id as shoppingCartId from ShoppingCart s,Product p,Yungou y where s.userId='$userId' and s.yungouId=y.id and y.productid=p.id";
		return mysql::queryTables($sql);
	}
	public static function deleteLot($ids) {
		$sql = "delete from ShoppingCart where";
		foreach ($ids as $key => $id) {
			$sql .= " id='$id' or";
		}
		$sql = substr($sql, 0,strlen($sql)-3);
		return mysql::execute($sql);
	}

	public static function deleteByYungou($userId, $yungouId) {
		$sql = "delete from ShoppingCart where yungouId='$yungouId' and userId='$userId'";
		return mysql::execute($sql);
	}
}
?>