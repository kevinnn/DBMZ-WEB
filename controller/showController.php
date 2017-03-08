<?php
include __ROOT__.'/model/showModel.php';
include __ROOT__.'/model/creditModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
Class showController {
	public static function getAll() {
		$firstId = isset($_GET['firstId']) ? $_GET['firstId'] : null;
		$lastId = isset($_GET['lastId']) ? $_GET['lastId'] : null;
		$limit = isset($_GET['limit']) ? $_GET['limit'] : 40;
		$arr = showModel::getAll($firstId,$lastId,$limit);
		if (count($arr) == 0) {
			return json_encode(new jsonBean(405,array(),'数据为空'),JSON_UNESCAPED_UNICODE);
		}
		$result = array();
		include __ROOT__.'/model/orderModel.php';
		foreach ($arr as $key => $value) {
			array_push($result, array(
				'avatorUrl'=>$value['avatorUrl'],
				'endTime'=>$value['endTime'],
				'showId'=>$value['showId'],
				'title'=>$value['title'],
				'result'=>$value['result'],
				'productTitle'=>$value['productTitle'],
				'userName'=>$value['userName'],
				'userId'=>$value['userId'],
				'term'=>$value['term'],
				'productId'=>$value['productId'],
				'yungouId'=>$value['yungouId'],
				'createdTime'=>$value['createdTime'],
				'content'=>$value['content'],
				'imgUrls'=>explode(",", $value['imgUrls']),
				'count'=>orderModel::getOrderCountByUserAndYungou( $value['userId'], $value['yungouId'] ),
				'userId'=>$value['userId']
				));
			
		}
		return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}

	public static function getCountAll() {
		return json_encode(new jsonBean(200,showModel::getCountAll(),'获取成功'),JSON_UNESCAPED_UNICODE);
	}

	public static function getDetail() {
		if (isset($_GET['showId']) || isset($_GET['yungouId'])) {
			$showId = isset($_GET['showId']) ? $_GET['showId'] : null;
			$yungouId = isset($_GET['yungouId']) ? $_GET['yungouId'] : null;
			$arr = showModel::getDetail($showId,$yungouId);
			if (count($arr) > 0 && $arr[0]['avatorUrl'] != null) {
				$result = array(
					'avatorUrl'=>$arr[0]['avatorUrl'],
					'userName'=>$arr[0]['userName'],
					'userId'=>$arr[0]['userId'],
					'title'=>$arr[0]['title'],
					'createdTime'=>$arr[0]['createdTime'],
					'count'=>$arr[0]['count'],
					'result'=>$arr[0]['result'],
					'endTime'=>$arr[0]['endTime'],
					'productTitle'=>$arr[0]['productTitle'],
					'term'=>$arr[0]['term'],
					'price'=>$arr[0]['price'],
					'content'=>$arr[0]['content'],
					'imgUrls'=>explode(",", $arr[0]['imgUrls']),
					'thumbnailUrl'=>$arr[0]['thumbnailUrl'],
					'yungouId'=>$arr[0]['yungouId'],
					'orderId'=>$arr[0]['orderId'],
					'productId'=>$arr[0]['productId']
					);
				return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			} else {
				return json_encode(new jsonBean(405,array(),'数据为空'),JSON_UNESCAPED_UNICODE);
			}
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
		
	}

	public static function getByProduct() {
		if (isset($_GET['productId']) && isset($_GET['limit'])) {
			$productId = $_GET['productId'];
			$lastId = isset($_GET['lastId'])?$_GET['lastId'] : null;
			$firstId = isset($_GET['firstId']) ? $_GET['firstId'] : null;
			$limit = $_GET['limit'];
			$arr = showModel::getByProduct($productId,$lastId,$firstId,$limit);
			$result = array();
			foreach ($arr as $key => $value) {
				array_push($result, array(
					'showId'=>$value['showId'],
					'avatorUrl'=>$value['avatorUrl'],
					'userName'=>$value['userName'],
					'userId'=>$value['userId'],
					'title'=>$value['title'],
					'createdTime'=>$value['createdTime'],
					'content'=>$value['content'],
					'imgUrls'=>explode(",", $value['imgUrls']),
					'productId'=>$value['productId'],
					'yungouId'=>$value['yungouId']
					));
			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getCountByProduct() {
		if (isset($_GET['productId'])) {
			$count = showModel::getCountByProduct($_GET['productId']);
			return json_encode(new jsonBean(200,$count,'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getAllByUser($userId) {
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		if (isset($_GET['limit'])) {
			$limit = $_GET['limit'];
			$arr = array();
			if(isset($_GET['userId'])){
				$userId = $_GET['userId'];
				$arr = showModel::getAllByUser($userId,$limit,$page);
			}else{
				$arr = showModel::getOwnAllByUser($userId,$limit,$page);
			}		
			$result = array();
			if (count($arr) > 0) {
				foreach ($arr as $key => $value) {
					$obj = array();
					foreach ($value as $key1 => $value1) {
						if (!is_numeric($key1)) {
							if ($key1 == 'imgUrls') {
								$obj = array_merge($obj,array($key1=>explode(",", $value1)));
							} else {
								$obj = array_merge($obj,array($key1=>$value1));
							}
						}
					}
					array_push($result, $obj);
				}
			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getCountAllByUser($userId) {
		if ($userId != null && !isset($_GET['userId'])) {
			$_GET['userId'] = $userId;
		}
		if (isset($_GET['userId'])) {
			$userId = $_GET['userId'];
			return json_encode(new jsonBean(200,showModel::getCountAllByUser($userId),'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function add ($userId) {
		if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['imgUrls']) && isset($_POST['yungouId']) && isset($_POST['orderId']) && isset($_POST['term']) && count($_POST) == 6) {
			$_POST['userId'] = $userId;

			$count = showModel::getCountByYungou($_POST['yungouId']);
			if ($count != 0) {
				return json_encode(new jsonBean(404,array(),'已经晒过了'));
			}

			$result = true;
			showModel::startTransaction();

			$result &= showModel::insertData($_POST,showModel::getTable());

			$result &= showModel::updateLogisticsStatusByYungou($_POST['yungouId'],4);

			$result &= creditModel::addCreditRecord($userId,100,$type = 5);
			
			if ($result) {
				showModel::commitTransaction();
				return json_encode(new jsonBean(200,$result,'插入成功'),JSON_UNESCAPED_UNICODE);
			} else {
				showModel::rollBackTransaction();
				return json_encode(new jsonBean(405,$result,'插入失败'),JSON_UNESCAPED_UNICODE);
			}
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getUnshowByUser($userId){
		$limit = isset($_GET['limit']) ? $_GET['limit'] : 40;
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
			return json_encode(new jsonBean(200,showModel::getUnshowByUser($userId,$limit,$page),'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
		
	}
	/*后台接口*/
	public static function getShowList() {
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
			return json_encode(new jsonBean(200,showModel::getShowList($page),'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getShowInfo() {
		if (isset($_GET['showId'])) {
			$id = $_GET['showId'];
			return json_encode(new jsonBean(200,showModel::getShowInfo($id),'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		}else{
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	
	public static function deleteShow() {
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
			return json_encode(new jsonBean(200,showModel::deleteData($id,showModel::getTable()),'删除成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function approveShow() {
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
			if (showModel::approveShow($id)){
				return json_encode(new jsonBean(200,array(),'批准成功'),JSON_UNESCAPED_UNICODE);
			}else{
				return json_encode(new jsonBean(401,array(),'批准失败'),JSON_UNESCAPED_UNICODE);
			}
			
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function setIsApproved() {
		
		if (isset($_POST['id'])&&isset($_POST['isApproved'])) {
			$id = $_POST['id'];
			$isApproved = $_POST['isApproved'];
			if (showModel::setIsApproved($id,$isApproved)){
				return json_encode(new jsonBean(200,array(),'修改批准状态成功'),JSON_UNESCAPED_UNICODE);
			}else{
				return json_encode(new jsonBean(401,array(),'修改批准状态失败'),JSON_UNESCAPED_UNICODE);
			}
			
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
}
?>