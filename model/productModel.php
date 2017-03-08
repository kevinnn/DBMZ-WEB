<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
Class productModel extends modelApi {
	private static $bean = 'productBean';
	private static $table = 'Product';

	public static function getTable() {
		return self::$table;
	}

	public static function getProductByIsRecommend ($limit) {
		$sql = "select * from ".self::$table." where isRecommend=1 order by id desc limit $limit";
		return mysql::query($sql,self::$bean);
	}

	public static function getProductByIsNew ($limit) {
		$sql = "select * from ".self::$table." where isNew = 1 order by id desc limit $limit";
		return mysql::query($sql,self::$bean);
	}

	public static function getProductByIsHot ($limit) {
		$sql = "select * from ".self::$table." where isHot = 1 order by id desc limit $limit";
		return mysql::query($sql,self::$bean);
	}

	public static function getProductByCategoryId ($categoryId,$limit) {
		$sql = "";
		if ($limit) {
			$sql = "select * from ".self::$table." where categoryId='$categoryId' limit $limit";
		} else {
			$sql = "select * from ".self::$table." where categoryId='$categoryId'";
		}
		return mysql::query($sql,self::$bean);
	}

	public static function getAllProduct () {
		$sql = "select * from ".self::$table." order by createdTime desc";
		return mysql::query($sql,self::$bean);
	}

	public static function getProductById($id) {
		$sql = "select * from ".self::$table." where id=$id";
		return mysql::query($sql,self::$bean);
	}

	public static function getProductList ($page,$categoryId,$brandId) {
		$start = ($page-1)*20;
		$sql = "select p.id,p.title,b.name as brandName,b.id as brandId,c.name as categoryName,c.id as categoryId,p.price,p.singlePrice,p.isOn,p.isRecommend,p.isHot,p.isNew from Product p, Category c, Brand b where p.categoryId=c.id and p.brandId=b.id";
		if ($categoryId != 0) {
			$sql .= " and c.id=$categoryId";
		}
		else if ($brandId != 0) {
			$sql .= " and b.id=$brandId";
		}
		$sql .= " order by p.id asc limit $start,20";
		return mysql::queryTables($sql);
	}

	public static function getProductCount ($categoryId,$brandId) {
		$sql = "select count(*) as count from Product";
		if ($categoryId != 0) {
			$sql .= " where categoryId=$categoryId";
		} else if ($brandId != 0) {
			$sql .= " where brandId=$brandId";
		}
		return mysql::count($sql);
	}

}
?>