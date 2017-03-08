<?php
include __ROOT__.'/model/addressModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
Class addressController {
	public static function add($userId) {
		if (isset($_POST['provinceID']) && isset($_POST['cityID']) && isset($_POST['areaID']) && isset($_POST['street']) && isset($_POST['receiver']) && isset($_POST['phoneNumber']) && isset($_POST['status'])) {
			$arr = addressModel::getAllByUser($userId);
			if (count($arr) == 5) {
				return json_encode(new jsonBean(405,false,'添加超过5条,失败'),JSON_UNESCAPED_UNICODE);
			}
			if ($_POST['status'] == 1) {
				if (!addressModel::updateUndefault($userId))
					return json_encode(new jsonBean(405,false,'发生错误'),JSON_UNESCAPED_UNICODE);
			}
			$_POST['userId'] = $userId;
			addressModel::insertData($_POST,addressModel::getTable());
			$arr = addressModel::getLastId($userId);
			$arr = $arr[0];
			return json_encode(new jsonBean(200,$arr->id,'插入成功'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
	public static function addMobile($userId) {
		if (isset($_POST['provinceID']) && isset($_POST['cityID']) && isset($_POST['areaID']) && isset($_POST['street']) && isset($_POST['receiver']) && isset($_POST['phoneNumber']) && isset($_POST['status'])) {
			$arr = addressModel::getAllByUser($userId);
			if (count($arr) == 5) {
				return json_encode(new jsonBean(405,false,'添加超过5条,失败'),JSON_UNESCAPED_UNICODE);
			}
			if ($_POST['status'] == 1) {
				if (!addressModel::updateUndefault($userId))
					return json_encode(new jsonBean(405,false,'发生错误'),JSON_UNESCAPED_UNICODE);
			}
			$_POST['userId'] = $userId;
			if (addressModel::insertData($_POST,addressModel::getTable())) {
				$arr = addressModel::getLastId($userId);
				$address = $arr[0];
				$arr = addressModel::getById($userId,$address->id);
				if (count($arr) > 0) {
					$obj = array(
						'id'=>$address->id,
						'userId'=>$userId,
						'provinceID'=>$arr[0]['provinceID'],
						'cityID'=>$arr[0]['cityID'],
						'areaID'=>$arr[0]['areaID'],
						'street'=>$arr[0]['street'],
						'postCode'=>$arr[0]['postCode'],
						'receiver'=>$arr[0]['receiver'],
						'idCode'=>$arr[0]['idCode'],
						'phoneNumber'=>$arr[0]['phoneNumber'],
						'status'=>$arr[0]['status'],
						'province'=>$arr[0]['province'],
						'city'=>$arr[0]['city'],
						'area'=>$arr[0]['area']
					);
					return json_encode(new jsonBean(200,$obj,'插入成功'),JSON_UNESCAPED_UNICODE);
				}
			} else {
				return json_encode(new jsonBean(404,array(),'插入失败'),JSON_UNESCAPED_UNICODE);
			}
			
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function remove($userId) {
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
			include __ROOT__.'/model/winOrderModel.php';
			$arr = winOrderModel::getByAddressId($id);

			if (count($arr) == 0) {
				$result = addressModel::remove($userId,$id);
			} else {
				$result = addressModel::updateIsRemove($id);
			}
			return json_encode(new jsonBean($result?200:405,$result,$result?'删除成功':'发生错误'),JSON_UNESCAPED_UNICODE);
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function modify($userId) {
		if (isset($_POST['address'])) {
			$address = $_POST['address'];
			$id = $address['id'];
			if (isset($address['status']) && $address['status'] == 1) {
				addressModel::updateUndefault($userId);
			}
			$result = addressModel::updateData($id,$address,addressModel::getTable());
			if ($result) {
				$arr = addressModel::getById($userId,$id);
				if (count($arr) > 0) {
					$obj = array(
						'id'=>$id,
						'userId'=>$userId,
						'provinceID'=>$arr[0]['provinceID'],
						'cityID'=>$arr[0]['cityID'],
						'areaID'=>$arr[0]['areaID'],
						'street'=>$arr[0]['street'],
						'postCode'=>$arr[0]['postCode'],
						'receiver'=>$arr[0]['receiver'],
						'idCode'=>$arr[0]['idCode'],
						'phoneNumber'=>$arr[0]['phoneNumber'],
						'status'=>$arr[0]['status'],
						'province'=>$arr[0]['province'],
						'city'=>$arr[0]['city'],
						'area'=>$arr[0]['area']
					);
					return json_encode(new jsonBean(200,$obj,'修改成功'),JSON_UNESCAPED_UNICODE);
				}
			} else {
				return json_encode(new jsonBean(404,array(),'修改失败'),JSON_UNESCAPED_UNICODE);

			}
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}

	public static function getAll($userId) {
		$arr = addressModel::getAllByUser($userId);
		$result = array();
		foreach ($arr as $key => $value) {
			$obj = array();
			foreach ($value as $key1 => $value1) {
				if (!is_numeric($key1)) {
					$obj = array_merge($obj,array($key1=>$value1));
				}
			}
			array_push($result, $obj);
		}
		return json_encode(new jsonBean(200,$result,'获取成功'),JSON_UNESCAPED_UNICODE);
	}

	public static function getById($userId) {
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$arr = addressModel::getById($userId,$id);
			if (count($arr) > 0) {
				$result = $arr[0];
				$obj = array();
				foreach ($result as $key => $value) {
					if (!is_numeric($key)) {
						$obj = array_merge($obj,array($key=>$value));
					}
				}
				return json_encode(new jsonBean(200,$obj,'获取成功'),JSON_UNESCAPED_UNICODE);
			} else {
				return json_encode(new jsonBean(404,array(),'数据为空'),JSON_UNESCAPED_UNICODE);

			}
		} else {
			return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
		}
	}
}
?>