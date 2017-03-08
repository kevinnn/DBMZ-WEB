<?php
include_once __ROOT__.'/JWT/JWT.php';
include __ROOT__.'/model/delegateModel.php';
include_once __ROOT__.'/model/userModel.php';
include_once __ROOT__.'/bean/jsonBean.php';
include_once __ROOT__.'/model/globalModel.php';
use \Firebase\JWT\JWT;
Class delegateController {  
    
    //后台接口，app接口
    public static function getWithdrawRecords($delegateid = -1,$type = -1,$isapproved = -1,$page = -1,$limit = -1){
        $enoughParams = true;
        if( $delegateid == -1 ){
            if( isset($_GET)&&isset($_GET['delegateid']) ){
                $delegateid = $_GET['delegateid'];
            }else{
                $enoughParams = false;
            }
        } 
        if( $type == -1 ){
            if( isset($_GET)&&isset($_GET['type']) ){
                $type = $_GET['type'];
                if ( $type == 2 ){
                    if( $isapproved == -1 ){
                        if( isset($_GET)&&isset($_GET['isapproved']) ){
                            $isapproved = $_GET['isapproved'];
                        }else{
                            $enoughParams = false;
                        }
                    }
                }
            }else{
                $enoughParams = false;
            }
        }         
        if( $page == -1 ){
            if( isset($_GET)&&isset($_GET['page']) ){
                $page = $_GET['page'];
            }else{
                $enoughParams = false;
            }
        }
        if( $limit == -1 ){
            if( isset($_GET)&&isset($_GET['limit']) ){
                $limit = $_GET['limit'];
            }else{
                $enoughParams = false;
            }
        }
        if ($enoughParams){
            $result =  delegateModel::getWithdrawRecords($delegateid,$type,$isapproved,$page,$limit);
            return json_encode(new jsonBean(200,$result,'获取提现记录成功'),JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(new jsonBean(403,array(),'参数不全'),JSON_UNESCAPED_UNICODE);
        }       
    }
    
    public static function getWithdrawCount($delegateid = -1,$type = -1,$isapproved = -1){
        $enoughParams = true;
        if( $delegateid == -1 ){
            if( isset($_GET)&&isset($_GET['delegateid']) ){
                $delegateid = $_GET['delegateid'];
            }else{
                $enoughParams = false;
            }
        }
        if( $type == -1 ){
            if( isset($_GET)&&isset($_GET['type']) ){
                $type = $_GET['type'];
                if ( $type == 2 ){
                    if( $isapproved == -1 ){
                        if( isset($_GET)&&isset($_GET['isapproved']) ){
                            $isapproved = $_GET['isapproved'];
                        }else{
                            $enoughParams = false;
                        }
                    }
                }
            }else{
                $enoughParams = false;
            }
        }       
        if ($enoughParams){
            $result =  delegateModel::getWithdrawCount($delegateid,$type,$isapproved);
            return json_encode(new jsonBean(200,$result,'获取提现记录数目成功'),JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(new jsonBean(403,array(),'参数不全'),JSON_UNESCAPED_UNICODE);
        }
        
    }
    //后台接口
    public static function approveWithdraw($withdrawid = -1){
        $enoughParams = true;
        if( $withdrawid == -1 ){
            if( isset($_POST)&&isset($_POST['withdrawid']) ){
                $withdrawid = $_POST['withdrawid'];
            }else{
                $enoughParams = false;
            }
        }
        if($enoughParams){
            if(delegateModel::approveWithdraw($withdrawid)){
                return json_encode(new jsonBean(200,array(),'批准成功'),JSON_UNESCAPED_UNICODE);
            }else{
                return json_encode(new jsonBean(700,array(),'批准失败'),JSON_UNESCAPED_UNICODE);
            }
        }else{
            return json_encode(new jsonBean(403,array(),'未传参数'),JSON_UNESCAPED_UNICODE);
        }
        
    }

    //后台接口
    public static function addDelegate($userid = -1){
        $enoughParams = true;
        if( $userid == -1 ){
            if( isset($_POST)&&isset($_POST['userid']) ){
                $userid = $_POST['userid'];
            }else{
                $enoughParams = false;
            }
        }
        if ($enoughParams){
            $user =  userModel::getUserById($userid);
            if(count($user) == 1){            
                if($user[0]->isDelegate == 0){                
                    delegateModel::startTransaction();
    
    
                    $result = userModel::updateDelegate($userid);
                    if($result){
                        delegateModel::commitTransaction();	
                        return json_encode(new jsonBean(200,array(),'新增代理成功'),JSON_UNESCAPED_UNICODE);
                    }else{
                        delegateModel::rollBackTransaction();
                        return json_encode(new jsonBean(700,array(),'更新user表失败'),JSON_UNESCAPED_UNICODE);
                    }

                }else{
                    return json_encode(new jsonBean(405,array(),'用户已经是代理'),JSON_UNESCAPED_UNICODE);
                }
            }else{
                return json_encode(new jsonBean(404,array(),'没有此用户'),JSON_UNESCAPED_UNICODE);
            }
        }else{
            return json_encode(new jsonBean(401,array(),'参数不全'),JSON_UNESCAPED_UNICODE);
        }
        
    }
    //后台接口
    public static function getDelegateList($date = -1,$page = -1,$limit = -1 ){        
        date_default_timezone_set('PRC');
        $now = date('Y-m');
        $date = $now;
        $page = $page == -1?1:$_GET['page'];
        $limit = $limit == -1?20:$_GET['limit'];
        $results = delegateModel::getDelegateList($date,$page,$limit);
        $output = array('delegates' => $results , 'rate' => delegateModel::$rate );

        return json_encode(new jsonBean(200,$output,'获取代理列表成功'),JSON_UNESCAPED_UNICODE);
        
    }
    //后台接口
    public static function getDelegateCount(){
        return json_encode(new jsonBean(200,delegateModel::getDelegateCount(),'获取代理数量成功'),JSON_UNESCAPED_UNICODE);
    }
    
    
    //app接口
    public static function getMemberNumAndCash($delegateid){
        $result = array('memberNum' => delegateModel::getMemberNum($delegateid), 'cash' => delegateModel::getCash($delegateid));
        return json_encode(new jsonBean(200,$result,'获取总会员数和钱包余额成功'),JSON_UNESCAPED_UNICODE);
    }
    
    //app接口
    public static function getThisMonthStatics($delegateid){
        date_default_timezone_set('PRC');
        $now = date('Y-m');
        $result =  delegateModel::getMonthStatics($now,$delegateid);
        return json_encode(new jsonBean(200,$result,'获取当月数据成功'),JSON_UNESCAPED_UNICODE);        
    }
    //app接口
    public static function applyWithdraw($delegateid,$amount = -1){
        $enoughParams = true;
        if( $amount == -1 ){
            if( isset($_POST)&&isset($_POST['amount']) ){
                $amount = $_POST['amount'];
            }else{
                $enoughParams = false;
            }
        }        
        if($enoughParams ){
            $cash = delegateModel::getCash($delegateid,0);
            if( $cash - $amount >= 0 ){
                if($amount>0){
                    $result = delegateModel::applyWithdraw($delegateid,$amount);
                    if($result){
                        return json_encode(new jsonBean(200,array(),'申请成功'),JSON_UNESCAPED_UNICODE);
                    }else{
                        return json_encode(new jsonBean(700,array(),'申请失败，原因不明'),JSON_UNESCAPED_UNICODE);
                    }
                }else{
                    return json_encode(new jsonBean(405,array(),'申请数额必须大于0'),JSON_UNESCAPED_UNICODE);
                }
                
            }else{
                return json_encode(new jsonBean(405,array(),'余额不足'),JSON_UNESCAPED_UNICODE);
            }             
        }else{
            return json_encode(new jsonBean(403,array(),'参数不全'),JSON_UNESCAPED_UNICODE);
        }
        
    }

}
?>