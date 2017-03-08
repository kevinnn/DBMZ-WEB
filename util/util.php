<?php
include_once dirname(dirname(__FILE__)).'/config/config.php';
include_once __ROOT__.'/config/config.php';
include_once __ROOT__.'/database/mysql.php';


	function get_client_ip() {
		$ipaddress = '';

		if (isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];	    
			
		return $ipaddress;
	}
	
	
	function getAreaByIp($ipaddress) {
		
		include_once __ROOT__.'/util/IpLocation.class.php';
		$Ip = new IpLocation(); // 实例化类
		$location = $Ip->getlocation($ipaddress); // 获取某个IP地址所在的位置
		$provinceCity = mb_convert_encoding($location['country'], 'utf-8', 'gbk');
		if($provinceCity){
			return $provinceCity;
		}else{
			return "查询地址失败";
		}
		// $ch = curl_init();
		// $url = "http://ip.taobao.com/service/getIpInfo.php?ip={$ipaddress}";
	
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($ch , CURLOPT_URL , $url);
		// $res = curl_exec($ch);
		// $res = json_decode($res,true);

		// if($res['code'] == 0){
		// 	$data = $res['data'];
		// 	$loginArea =  $data['region'].$data['city'];
		// 	if($loginArea){
		// 		return $loginArea;
		// 	}else{
		// 		return "查询地址失败";
		// 	}
		// }else{
		// 	return "查询地址失败";
		// }
	}
	
	
	function updateLoginArea($updateAll = false) {
		if ($updateAll){
			$updateallsql = "" ;
		}else{
			$updateallsql = "(loginArea is null or loginArea='') and " ;
		}
		$sql = "select id,ip from `User` where ".$updateallsql."ip is not null";
		$results = mysql::queryTables($sql);
		$execute = false;
		$sql = "UPDATE `User` SET loginArea = CASE id "; 		
		foreach ($results as $key => $result) {
			if($key < 100){
				$loginArea = getAreaByIp($result['ip']);
				$id = $result['id'];
				$sql .= sprintf("WHEN %d THEN '%s' ", $id, $loginArea); 			
				$execute = true;
			}			
		}
		$sql .= "END where ".$updateallsql." ip is not null";
		if ($execute){
			mysql::execute($sql);
		}		
	}
	
?>