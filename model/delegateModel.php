<?php
include_once __ROOT__.'/database/mysql.php';
include_once __ROOT__.'/model/modelApi.php';
Class delegateModel extends modelApi {
	private static $table = 'Delegate';

    public static $rate = 10;
	public static function getTable() {
		return self::$table;
	}
    

    public static function addCashRecords($delegateid,$amount){
        $result = true;
        self::startTransaction();        
        date_default_timezone_set("PRC");
        $now = date('Y-m-d H:i:s');
        $sql = "insert into `Cash` set delegateId = {$delegateid},time ='{$now}',amount = {$amount},type = 1";
        $result &= mysql::execute($sql);
        $sql = "update `User` set cash = cash + {$amount} where id = {$delegateid}";
        $result &= mysql::execute($sql);
        if($result){
            self::commitTransaction();   
        }else{
            self::rollBackTransaction();  
        }
        return $result;
    }
    
    public static function getMemberNum($delegateid){
        $sql = "select count(id) as count from `User` right join (select code from `User` where id = 41) as uu on `User`.invitedBy = uu.code group by uu.code";
        return mysql::count($sql);
    }
    public static function getMonthStatics($date,$delegateid) {
        $date = $date."-01 00:00:00";        
		$sql = "select sum(amount) as sum from `Cash` where delegateId  = {$delegateid} and time between  '{$date}' and DATE_ADD('{$date}',INTERVAL 1 MONTH) and type = 1";  
        $tmpsum = mysql::sum($sql);
        $monthcount =  $tmpsum?$tmpsum:0;
        $sql = "select count(id) as count from `User` right join (select code from `User` where id = {$delegateid}) as uu on uu.code=`User`.`invitedBy`  where registerTime between '{$date}' and DATE_ADD('{$date}',INTERVAL 1 MONTH)";
        $monthregister =  mysql::count($sql);
        $result = array('monthRegister' => $monthregister,'rate' => self::$rate, 'monthCount' => $monthcount);
        return $result;
	}
    public static function applyWithdraw($delegateid,$amount){
        date_default_timezone_set("PRC");
        $now = date('Y-m-d H:i:s');
        $sql = "insert into `Cash` set delegateId = {$delegateid},time ='{$now}',amount =  {$amount},type = 2,isApproved = 0";
        return mysql::execute($sql);
    }
    public static function approveWithdraw($withdrawid){
        $sql = "update `Cash` set isApproved = 1 where id = {$withdrawid} and isApproved = 0";
        mysql::execute($sql);
        if(mysql_affected_rows()>0){
            $sql = "select delegateId,amount from `Cash` where id = {$withdrawid}";
            $tmpres = mysql::queryTables($sql)[0];
            $userid = $tmpres['delegateId'];
            $amount = $tmpres['amount'];
            $sql = "update `User` set cash = cash - {$amount} where id = {$userid}";
            return mysql::execute($sql);
        }        
        return false;        
    }
    public static function getCash($delegateid,$isapproved = 1){
        $sql = "select cash from `User` where id = {$delegateid}";
        $results = mysql::queryTables($sql);
        $cash = $results[0]['cash'];

        if ( $isapproved == 1){
            return $cash;
        }else{
            $sql = "select sum(amount) as sum from `Cash` where delegateId = {$delegateid} and isApproved = 0";
            $withdraw =  mysql::sum($sql);
            return $cash - $withdraw;
        }   
    }
    public static function getWithdrawRecords($delegateid,$type,$isapproved = 2,$page = 1,$limit = 20){
        $start = ($page-1)*$limit;
        $where = "";
        if($delegateid != 0){
            $where = " delegateId = {$delegateid} and ";           
        }
        if ( $type == 2){
            if ($isapproved == 0 || $isapproved == 1){
                $sql = "select *,`Cash`.id as withdrawId,`Cash`.type as cashType from `Cash`,`User` where ".$where." `Cash`.type = 2 and isApproved = {$isapproved} and `User`.id = `Cash`.delegateId order by time desc limit $start, $limit";
            }else{
                $sql = "select *,`Cash`.id as withdrawId,`Cash`.type as cashType from `Cash`,`User` where ".$where." `Cash`.type = 2 and `User`.id = `Cash`.delegateId order by time desc limit $start, $limit";
            }
        }elseif($type == 1){
             $sql = "select *,`Cash`.id as withdrawId,`Cash`.type as cashType from `Cash`,`User` where ".$where." `Cash`.type = 1 and `User`.id = `Cash`.delegateId order by time desc limit $start, $limit";
        }else{
            $sql = "select *,`Cash`.id as withdrawId,`Cash`.type as cashType from `Cash`,`User` where ".$where." `User`.id = `Cash`.delegateId order by time desc limit $start, $limit";
        }       
        $results = mysql::queryTables($sql);
        $output = array();
        foreach ($results as $key => $result) {
            $tmp = array('id' => $result['withdrawId'], 'type' => $result['cashType'], 'userName' => $result['userName'], 'delegateId' => $result['delegateId'], 'time' => $result['time'], 'amount' => $result['amount'], 'isApproved' => $result['isApproved']);
            array_push($output,$tmp);
        }
        return $output;
    }
    public static function getWithdrawCount($delegateid,$type,$isapproved = 2){
        $where = "";
        if($delegateid != 0){
            $where = " delegateId = {$delegateid} and ";           
        }
        if ( $type == 2){
            if ($isapproved == 0 || $isapproved == 1){
                $sql = "select count(*) as count from `Cash`,`User` where ".$where." `Cash`.type = 2 and isApproved = {$isapproved} and `User`.id = `Cash`.delegateId";
            }else{
                $sql = "select count(*) as count from `Cash`,`User` where ".$where." `Cash`.type = 2 and `User`.id = `Cash`.delegateId";
            }
        }elseif($type == 1){
            $sql = "select count(*) as count from `Cash`,`User` where ".$where." `Cash`.type = 1 and `User`.id = `Cash`.delegateId";
        }else{
            $sql = "select count(*) as count from `Cash`,`User` where ".$where." `User`.id = `Cash`.delegateId";
        }
        return mysql::count($sql);
    }
    
    public static function getDelegateList($date,$page = 1,$limit = 20 ){
        $start = ($page-1)*$limit;
        $date = $date."-01 00:00:00";   
        $sql = "select delegate2.*,sum(c.amount) as monthCash from (select delegate1.*,count(uu.id) as monthRegister from (select u.*,count(`User`.id) as memberNum from `User` right join (select id,userName,cash,code from `User` where isDelegate = 1) as u on `User`.invitedBy = u.code group by u.code) as delegate1 left join (select id,invitedBy,registerTime from `User` where registerTime between '{$date}' and DATE_ADD('{$date}',INTERVAL 1 MONTH)) as uu on  delegate1.code=uu.`invitedBy` group by delegate1.code) as delegate2 left join (select * from `Cash` where type = 1 and time between '{$date}' and DATE_ADD('{$date}',INTERVAL 1 MONTH)) as c on delegate2.id = c.`delegateId` group by delegate2.code";
        return mysql::queryTables($sql);
    }
    
    public static function getDelegateCount(){
        $sql = "select count(id) as count from `User` where isDelegate = 1";
        return mysql::count($sql);
    }

}
?>