<?php
include __ROOT__.'/model/yungouModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
Class yungouController {
	/*
	**获取最新开奖的yungou
	**parameter 数量
	*/
	public static function newStartTimeYungou ($limit) {
		$arr = yungouModel::getYungouByStartTime($limit);
		$jsonBean = new jsonBean(200,$arr,"获取成功");
		return json_encode($jsonBean,JSON_UNESCAPED_UNICODE);
	}
	/*
	**根据productId获取对应未结束的云购
	**parameter 商品id数组
	*/
	public static function yungouProductIdArr ($productIdArr,$limit) {
		$arr = yungouModel::getYungouByProductIdArr($productIdArr,$limit);
		$jsonBean = new jsonBean(200,$arr,"获取成功");
		return json_encode($jsonBean,JSON_UNESCAPED_UNICODE);
	}
	/*
	**根据productId获取对应未结束的云购
	**parameter 商品id
	*/
	public static function yungouProductId () {
		if (isset($_GET['pid'])) {
			$productId = $_GET['pid'];
			$arr = yungouModel::getYungouByProductId($productId);
			if (count($arr) == 1) {
				return json_encode(new jsonBean(200,$arr[0],"获取成功"),JSON_UNESCAPED_UNICODE);
			} else {
				return json_encode(new jsonBean(404,array(),'查找不到'),JSON_UNESCAPED_UNICODE);
			}
		} else {
			return json_encode(new jsonBean(403,$arr,"未传参数"),JSON_UNESCAPED_UNICODE);
		}
	}
	/*
	**按照创建时间排序，选出指定商品id的一定数量的云购
	**parameter 商品id数组
	**parameter 数量limit
	*/
	public static function yungouCreatedTime ($productIdArr,$limit) {
		$arr = yungouModel::getYungouByCreatedTime($productIdArr,$limit);
		$jsonBean = new jsonBean(200,$arr,"获取成功");
		return json_encode($jsonBean,JSON_UNESCAPED_UNICODE);
	}

	public static function getAllNotEnd() {
		$search = isset($_GET['search']) ? $_GET['search'] : null;
		$arr = yungouModel::getAllNotEnd($search);
		$result = array();
		foreach ($arr as $key => $value) {
			array_push($result, array(
				'yungou'=>array(
					'id'=>$value['yungouId'],
					'saleCount'=>$value['saleCount'],
					'createdTime'=>$value['createdTime'],
					'term'=>$value['term']
					),
				'product'=>array(
					'id'=>$value['productId'],
					'title'=>$value['title'],
					'price'=>$value['price'],
					'thumbnailUrl'=>$value['thumbnailUrl'],
					'singlePrice'=>$value['singlePrice'],
					'priority'=>$value['priority']
					)
				));
		}
		return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}

	public static function getAllByCategory() {
		if (isset($_GET['id'])) {
			$categoryId = $_GET['id'];
			$arr = yungouModel::getAllByCategory($categoryId);
			$result = array();
			foreach ($arr as $key => $value) {
				array_push($result, array(
					'yungou'=>array(
						'id'=>$value['yungouId'],
						'saleCount'=>$value['saleCount'],
						'createdTime'=>$value['createdTime'],
						'term'=>$value['term']
						),
					'product'=>array(
						'id'=>$value['productId'],
						'title'=>$value['title'],
						'price'=>$value['price'],
						'thumbnailUrl'=>$value['thumbnailUrl'],
						'singlePrice'=>$value['singlePrice'],
						'priority'=>$value['priority']
						)
				));
			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		}
	}

	public static function yungouHot($limit) {
		$arr = yungouModel::getYungouByHot($limit);
		$result = array();
		foreach ($arr as $key => $value) {
			array_push($result, array(
				'yungou'=>array(
					'saleCount'=>$value['saleCount'],
					'term'=>$value['term'],
					'id'=>$value['yungouId']
					),
				'product'=>array(
					'title'=>$value['title'],
					'price'=>$value['price'],
					'id'=>$value['productId'],
					'thumbnailUrl'=>$value['thumbnailUrl'],
					'singlePrice'=>$value['singlePrice']
					)
				));
		}
		return $result;
	}

	public static function yungouByTerm ($productId,$term) {
		$arr = yungouModel::getYungouByTerm($productId,$term);
		if (count($arr) < 1) {
			return json_encode(new jsonBean(404,$arr,"查找不到"),JSON_UNESCAPED_UNICODE);
		}
		$jsonBean = new jsonBean(200,$arr,"获取成功");
		return json_encode($jsonBean,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}

	public static function yungouDetailByTerm () {
		if (isset($_GET['pid']) && isset($_GET['term'])) {
			$productId = $_GET['pid'];
			$term = $_GET['term'];
			$arr = yungouModel::getYungouDetailByTerm($productId,$term);
			if (count($arr) == 0) {
				return json_encode(new jsonBean(404,$arr,"查找不到"),JSON_UNESCAPED_UNICODE);
			} else {
				$obj = $arr[0];
				$result = array(
					'yungou'=>array(
						'id'=>intval($obj['yungouId']),
						'productId'=>intval($obj['productId']),
						'term'=>intval($obj['term']),
						'status'=>intval($obj['status']),
						'A'=>intval($obj['A']),
						'B'=>intval($obj['B']),
						'result'=>intval($obj['result']),
						'orderId'=>intval($obj['orderId']),
						'createdTime'=>$obj['createdTime'],
						'saleCount'=>intval($obj['saleCount']),
						'startTime'=>$obj['startTime'],
						'sscTerm'=>$obj['sscTerm'],
						'endTime'=>$obj['endTime'],
						'isMalfunction'=>intval($obj['isMalfunction'])
						),
					'product'=>array(
						'id'=>intval($obj['productId']),
						'categoryId'=>intval($obj['categoryId']),
						'brandId'=>intval($obj['brandId']),
						'title'=>$obj['title'],
						'subTitle'=>$obj['subTitle'],
						'keywords'=>$obj['keywords'],
						'description'=>$obj['description'],
						'price'=>intval($obj['price']),
						'singlePrice'=>intval($obj['singlePrice']),
						'isOn'=>intval($obj['isOn']),
						'imgUrls'=>explode(',', $obj['imgUrls']),
						'thumbnailUrl'=>$obj['thumbnailUrl'],
						'content'=>$obj['content'],
						'isRecommend'=>intval($obj['isRecommend']),
						'isHot'=>intval($obj['isHot']),
						'isNew'=>intval($obj['isNew']),
						'createdTime'=>$obj['createdTime']
						)
					);
				return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			}

		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function yungouDetailByYungou() {
		if (isset($_GET['yid'])) {
			$yungouId = $_GET['yid'];
			$arr = yungouModel::getYungouDetailByYungou($yungouId);
			if (count($arr) == 0) {
				return json_encode(new jsonBean(404,$arr,"查找不到"),JSON_UNESCAPED_UNICODE);
			} else {
				date_default_timezone_set("PRC");
				$value = $arr[0];
				$obj = array(
					'yungou'=>array(
						'id'=>intval($value['yungouId']),
						'productId'=>intval($value['productId']),
						'term'=>intval($value['term']),
						'status'=>intval($value['status']),
						'A'=>intval($value['A']),
						'B'=>intval($value['B']),
						'result'=>intval($value['result']),
						'orderId'=>intval($value['orderId']),
						'createdTime'=>$value['createdTime'],
						'saleCount'=>intval($value['saleCount']),
						'startTime'=>$value['startTime'],
						'sscTerm'=>$value['sscTerm'],
						'endTime'=>$value['endTime'],
						"timeout"=> strtotime($value['endTime'])-time(),
						'isMalfunction'=>intval($value['isMalfunction'])
					),
					'product'=>array(
						'id'=>intval($value['productId']),
						'categoryId'=>intval($value['categoryId']),
						'brandId'=>intval($value['brandId']),
						'title'=>$value['title'],
						'subTitle'=>$value['subTitle'],
						'keywords'=>$value['keywords'],
						'description'=>$value['description'],
						'price'=>intval($value['price']),
						'singlePrice'=>intval($value['singlePrice']),
						'isOn'=>intval($value['isOn']),
						'imgUrls'=>explode(',', $value['imgUrls']),
						'thumbnailUrl'=>$value['thumbnailUrl'],
						'content'=>$value['content'],
						'isRecommend'=>intval($value['isRecommend']),
						'isHot'=>intval($value['isHot']),
						'isNew'=>intval($value['isNew']),
						'createdTime'=>$value['createdTime'],
					)
				);
				return json_encode(new jsonBean(200,$obj,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
			}
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function fastNotStart() {
		if (isset($_GET['limit'])) {
			$limit = $_GET['limit'];
			$arr = yungouModel::getFastNotStart($limit);
			$resultArr = array();
			foreach ($arr as $key => $value) {
				$obj = array(
					"productId"=> $value["productId"],
					"term"=> $value["term"],
					"title"=> $value["title"],
					"thumbnailUrl"=> $value["thumbnailUrl"],
					"price"=> $value["price"],
					"saleCount"=> $value["saleCount"]
				);
				array_push($resultArr, $obj);
			}
			return json_encode(new jsonBean(200,$resultArr,"获取成功"),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function fastStart() {
		if (isset($_GET['limit'])) {

			$startTime = isset($_GET['startTime']) ? $_GET['startTime'] : null;
			$limit = $_GET['limit'];
			$arr = yungouModel::getFastStart($startTime,$limit);
			
			$resultArr = array();

			date_default_timezone_set("PRC");
			
			foreach ($arr as $key => $value) {
				$obj = array(
					"productId"=> $value["productId"],
					"term"=> $value["term"],
					"title"=> $value["title"],
					"thumbnailUrl"=> $value["thumbnailUrl"],
					"price"=> $value["price"],
					"saleCount"=> $value["saleCount"],
					"startTime"=> $value["startTime"],
					"endTime"=> $value["endTime"],
					"timeout"=> strtotime($value['endTime'])-time(),
					"orderId"=> isset($value["orderId"]) ? $value["orderId"] : null,
					"status"=>$value["status"],
					"yungouId"=> $value["yungouId"]
				);
				$result = array();
				if ($obj['status'] == 2) {
					include_once __ROOT__.'/model/userModel.php';
					$arr = userModel::getWinUserByOrder($obj['orderId']);
					if (count($arr) > 0) {

						$result = array_merge($result,array(
							'avatorUrl'=>$arr[0]['avatorUrl'],
							'userName'=>$arr[0]['userName'],
							'userId'=>$arr[0]['userId'],
							'buyTime'=>$arr[0]['buyTime'],
							'ip'=>$arr[0]['ip'],
							'loginArea'=>$arr[0]['loginArea'],
							"result"=> isset($value["result"]) ? $value["result"] : null,
							));
						include_once __ROOT__.'/model/orderModel.php';
						$sum = orderModel::getOrderCountByUserAndYungou($arr[0]['userId'],$obj['yungouId']);
						$result = array_merge($result,array('count'=>$sum));
					}

				}
				$obj = array_merge($obj,array('winUser'=>$result));
				array_push($resultArr, $obj);
			}
			return json_encode(new jsonBean(200,$resultArr,"获取成功"),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数limit'),JSON_UNESCAPED_UNICODE);

		}

	}

	public static function getHistory() {
		if(isset($_GET['productId']) && isset($_GET['limit'])) {
			date_default_timezone_set("PRC");
			$productId = $_GET['productId'];
			$startTime = isset($_GET['startTime']) ? $_GET['startTime'] : null;
			$limit = $_GET['limit'];
			$arr = yungouModel::getHistoryYungou($productId,$startTime,$limit);
			$result = array();
			foreach ($arr as $key => $value) {
				$obj = array(
					'term'=>$value->term,
					'result'=>$value->result,
					'endTime'=>$value->endTime,
					'orderId'=>$value->orderId,
					'status'=>$value->status,
					'yungouId'=>$value->yungouId,
					'startTime'=>$value->startTime
					);
				array_push($result,$obj);
			}
			$valArr = array();
			include __ROOT__.'/model/userModel.php';
			include __ROOT__.'/model/orderModel.php';
			foreach ($result as $key => $value) {
				if ($value['status'] == 2) {
					$arr = userModel::getWinUserByOrder($value['orderId']);
					if (count($arr) > 0) {

						$value = array_merge($value,array(
							'avatorUrl'=>$arr[0]['avatorUrl'],
							'userName'=>$arr[0]['userName'],
							'userId'=>$arr[0]['userId'],
							'buyTime'=>$arr[0]['buyTime'],
							'loginArea'=>$arr[0]['loginArea']
							));
						$sum = orderModel::getOrderCountByUserAndYungou($arr[0]['userId'],$value['yungouId']);
						$value = array_merge($value,array('count'=>$sum));
						array_push($valArr, $value);
					}
				} else {
					$value['timeout'] = strtotime($value['endTime'])-time();
					array_push($valArr, $value);
				}
			}
			return json_encode(new jsonBean(200,$valArr,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getHistoryCount() {
		if (isset($_GET['productId'])) {
			$productId = $_GET['productId'];
			return json_encode(new jsonBean(200,yungouModel::getHistoryYungouCount($productId),'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getHistoryMobile() {
		if(isset($_GET['productId']) && isset($_GET['limit'])) {
			date_default_timezone_set("PRC");
			$productId = $_GET['productId'];
			$startTime = isset($_GET['startTime']) ? $_GET['startTime'] : null;
			$limit = $_GET['limit'];
			$arr = yungouModel::getHistoryMobileYungou($productId,$startTime,$limit);
			$result = array();
			foreach ($arr as $key => $value) {
				$obj = array(
					'term'=>$value->term,
					'result'=>$value->result,
					'endTime'=>$value->endTime,
					'orderId'=>$value->orderId,
					'status'=>$value->status,
					'yungouId'=>$value->yungouId,
					'startTime'=>$value->startTime
					);
				array_push($result,$obj);
			}
			$valArr = array();
			include __ROOT__.'/model/userModel.php';
			include __ROOT__.'/model/orderModel.php';
			foreach ($result as $key => $value) {
				$arr = userModel::getWinUserByOrder($value['orderId']);
				if (count($arr) > 0) {

					$value = array_merge($value,array(
						'avatorUrl'=>$arr[0]['avatorUrl'],
						'userName'=>$arr[0]['userName'],
						'userId'=>$arr[0]['userId'],
						'buyTime'=>$arr[0]['buyTime'],
						'loginArea'=>$arr[0]['loginArea']
						));
					$sum = orderModel::getOrderCountByUserAndYungou($arr[0]['userId'],$value['yungouId']);
					$value = array_merge($value,array('count'=>$sum));
					array_push($valArr, $value);
				}
			}
			return json_encode(new jsonBean(200,$valArr,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function getYgForShow($userId){
		if (isset($_GET['yid'])) {
			$yungouId = $_GET['yid'];			
			$results = yungouModel::getYgForShow($userId,$yungouId);
			if(isset($results[0]) && $results[0][0] != null){
				return json_encode(new jsonBean(200,$results[0],'获取成功'),JSON_UNESCAPED_UNICODE);
			}else{
				return json_encode(new jsonBean(404,array(),'没有查询到结果'),JSON_UNESCAPED_UNICODE);
			}
			
			
		}else{
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
		
	}

	public static function getWin($id) {
		if (isset($_GET['id']) || $id != null) {
			$yungouId = isset($_GET['id']) ? $_GET['id'] : $id;
			$arr = yungouModel::getWinYungou($yungouId);
			if (count($arr) > 0) {
				include __ROOT__.'/model/userModel.php';
				include __ROOT__.'/model/orderModel.php';
				$user = userModel::getWinUserByOrder($arr[0]->orderId);
				if (count($user) > 0) {
					$sum = orderModel::getOrderCountByUserAndYungou($user[0]['userId'],$arr[0]->yungouId);
					$result = array(
						'term'=>$arr[0]->term,
						'result'=>$arr[0]->result,
						'endTime'=>$arr[0]->endTime,
						'orderId'=>$arr[0]->orderId,
						'status'=>$arr[0]->status,
						'yungouId'=>$arr[0]->yungouId,
						'startTime'=>$arr[0]->startTime,
						'avatorUrl'=>$user[0]['avatorUrl'],
						'userName'=>$user[0]['userName'],
						'userId'=>$user[0]['userId'],
						'ip'=>$user[0]['ip'],
						'buyTime'=>$user[0]['buyTime'],
						'loginArea'=>$user[0]['loginArea'],
						'count'=>$sum
						);
					return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
				}
			} else {
				return json_encode(new jsonBean(405,array(),'数据为空'),JSON_UNESCAPED_UNICODE);
			}
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function compute() {
		if (isset($_GET['id'])) {
			$yungouId = $_GET['id'];
			$arr = yungouModel::getCompute($yungouId);
			$result = array();
			foreach ($arr as $key => $value) {
				array_push($result, array(
					'buyTime'=>$value['buyTime'],
					'userName'=>$value['userName'],
					'userId'=>$value['userId'],
					'title'=>$value['title'],
					'term'=>$value['term'],
					'productId'=>$value['productId'],
					'count'=>$value['count']
					));
			}
			return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getResult() {
		if (isset($_GET['id'])) {
			$yungouId = $_GET['id'];
			return json_encode(new jsonBean(200,yungouModel::getResult($yungouId),'获取成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	//检查是否有开奖的云购
	public static function process() {
		date_default_timezone_set('PRC');
		$now = date('Y-m-d H:i:s');
		return yungouModel::doProcess($now);
	}

	public static function getTenZone() {
		if (isset($_GET['limit']) && isset($_GET['page'])) {
			$limit = $_GET['limit'];
			$page = $_GET['page'];

			$arr = yungouModel::getTenZone($page,$limit);
			$result = array();
			foreach ($arr as $key => $value) {
				array_push($result, array(
					'yungou'=>array(
						'id'=>$value['yungouId'],
						'saleCount'=>$value['saleCount'],
						'createdTime'=>$value['createdTime'],
						'term'=>$value['term']
						),
					'product'=>array(
						'id'=>$value['productId'],
						'title'=>$value['title'],
						'price'=>$value['price'],
						'thumbnailUrl'=>$value['thumbnailUrl'],
						'singlePrice'=>$value['singlePrice']
						)
					));
			}
		return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);			
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getCountTenZone() {
		return json_encode(new jsonBean(200,yungouModel::getCountTenZone(),'获取成功'),JSON_UNESCAPED_UNICODE);
	}

	public static function getLatestYungou () {
		if (isset($_GET['yid'])) {
			$yungouId = $_GET['yid'];
			$arr = yungouModel::getLastYungouByYungou($yungouId);
			if (count($arr) == 1) {
				return json_encode(new jsonBean(200,$arr[0],'获取成功'),JSON_UNESCAPED_UNICODE);
			} else {
				return json_encode(new jsonBean(404,array(),'数据为空'),JSON_UNESCAPED_UNICODE);
			}
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	/*后台接口*/
	public static function yungouList () {
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$status = isset($_GET['status']) ? $_GET['status'] : -1;
		$productId = isset($_GET['productId']) ? $_GET['productId'] : 0;
		$arr = yungouModel::getYungouList($page,$status,$productId);
		return json_encode(new jsonBean(200,$arr,'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	}
	public static function yungouCount () {
		$status = isset($_GET['status']) ? $_GET['status'] : -1;
		$productId = isset($_GET['productId']) ? $_GET['productId'] : 0;
		$arr = yungouModel::getYungouCount($status,$productId);
		return json_encode(new jsonBean(200,$arr,'获取成功'),JSON_UNESCAPED_UNICODE); 
	}
	public static function yungouDetail () {
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			return json_encode(new jsonBean(200,yungouModel::getYungouDetail($id),'获取成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			return json_encode(new jsonBean(405,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
}
?>