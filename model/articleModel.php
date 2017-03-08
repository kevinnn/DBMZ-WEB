<?php
include __ROOT__.'/database/mysql.php';
include __ROOT__.'/model/modelApi.php';
Class articleModel extends modelApi{
	private static $bean = 'articleBean';
	private static $table = 'Article';

	public static function getTable() {
		return self::$table;
	}
	
	public static function getAllTitle () {
		$sql = 'select id,title,category from Article';
		return mysql::query($sql,self::$bean);
	}

	public static function getArticleById ($id) {
		$sql = "select * from Article where id = $id";
		return mysql::query($sql,self::$bean);
	}

	public static function getArticleByTitle ($title) {
		$sql = "select * from Article where title = '$title'";
		return mysql::query($sql,self::$bean);
	}

	/*后台接口*/
	public static function getArticleList () {
		$sql = "select * from Article order by id asc";
		return mysql::query($sql,self::$bean);
	}
}
?>
