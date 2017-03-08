<?php

include_once __ROOT__.'/database/mysql.php';

class templateMsg {
  private $appId;
  private $appSecret;


  public function __construct($appId, $appSecret) {
    $this->appId = $appId;
    $this->appSecret = $appSecret;
  } 

  private function getAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = $this->getAccessTokenAndTimeFromDB();
    if ($data[1] < time()) {
      // 如果是企业号用以下URL获取access_token
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
      $res = json_decode(file_get_contents($url));

      
      $access_token = $res->access_token;
      if ($access_token) {
        $data[1] = time() + 7000;
        $data[0] = $access_token;
        $this->updateAccessTokenAndTimeFromDB($data[0],$data[1]);
      }
    } else {
      $access_token = $data[0];
    }
    return $access_token;
  }
  
  private function getAccessTokenAndTimeFromDB() {
    $sql = "select value,expire_time from `Wx` where `key` = 'access_token'";
    return mysql::queryTables($sql)[0];
  }
  
  private function updateAccessTokenAndTimeFromDB($value,$time) {
    $sql = "update `Wx` set value='$value',expire_time=$time where `key` = 'access_token'";
    return mysql::execute($sql);
  }
  
  public function getAllPrivateTemplate(){
    $access_token = $this->getAccessToken();
    $url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=$access_token";
    $res = json_decode(file_get_contents($url),true);
    return $res;  
  }
  
  public function sendPurchaseSuccessMsg($openid,$url = null,$name_value,$remark_value = null ,$name_color = "#000000",$remark_color = "#000000"){
    
    $payload = array();
    $payload['touser'] = $openid;
    $payload['template_id'] = 'Mnl8iHOVg-Cj5O5flNdlFs2zXMWn6OatFOBl684L8Uk';
    if ($url === null){      
    }else{
      $payload['url'] = $url;
    }
    $name = array('value' => $name_value, 'color' => $name_color);
    $data = array();
    $data['name'] = $name;
    if ($remark_value === null){      
    }else{
      $remark = array('value' => $remark_value, 'color' => $remark_color);
      $data['remark'] = $remark;
    }
    $payload['data'] = $data;
    $payload_json = json_encode($payload);
    // echo $payload_json;
  

    $access_token = $this->getAccessToken();
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
    $this->send_post($url,$payload_json);
  }
  
  
  public function sendWinMsg($openid,$url = null,$first_value,$keyword1_value,$keyword2_value,$remark_value = null ,$first_color = "#4A90E2",$keyword1_color = "#000000",$keyword2_color = "#000000",$remark_color = "#4A90E2"){
    
    $payload = array();
    $payload['touser'] = $openid;
    $payload['template_id'] = '8Cpxl4QF5BRs4mH2JHvYShM6fuKyiHX6F7Hwdm-MoRo';
    
    if ($url === null){      
    }else{
      $payload['url'] = $url;
    }
    
    $first = array('value' => $first_value, 'color' => $first_color);
    $data = array();
    $data['first'] = $first;
    $keyword1 = array('value' => $keyword1_value, 'color' => $keyword1_color);
    $data['keyword1'] = $keyword1;
    $keyword2 = array('value' => $keyword2_value, 'color' => $keyword2_color);
    $data['keyword2'] = $keyword2;
    if ($remark_value === null){      
    }else{
      $remark = array('value' => $remark_value, 'color' => $remark_color);
      $data['remark'] = $remark;
    }
    $payload['data'] = $data;
    $payload_json = json_encode($payload);

    // echo $payload_json;
  

    $access_token = $this->getAccessToken();
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
    $this->send_post($url,$payload_json);
  }
  
  
  public function sendRevealTime($openid,$url = null,$first_value,$keyword1_value,$keyword2_value,$keyword3_value,$remark_value = null ,$first_color = "#4A90E2",$keyword1_color = "#000000",$keyword2_color = "#000000",$keyword3_color = "#000000",$remark_color = "#4A90E2"){
    $payload = array();
    $payload['touser'] = $openid;
    $payload['template_id'] = 'LIozUU2vxCFmWQDAl7r8antKbJHKY4ND9Iun5696Gvw';    
    if ($url === null){      
    }else{
      $payload['url'] = $url;
    }    
    $first = array('value' => $first_value, 'color' => $first_color);
    $data = array();
    $data['first'] = $first;
    $keyword1 = array('value' => $keyword1_value, 'color' => $keyword1_color);
    $data['keyword1'] = $keyword1;
    $keyword2 = array('value' => $keyword2_value, 'color' => $keyword2_color);
    $data['keyword2'] = $keyword2;
    $keyword3 = array('value' => $keyword3_value, 'color' => $keyword3_color);
    $data['keyword3'] = $keyword3;
    if ($remark_value === null){      
    }else{
      $remark = array('value' => $remark_value, 'color' => $remark_color);
      $data['remark'] = $remark;
    }
    $payload['data'] = $data;
    $payload_json = json_encode($payload);
    // echo $payload_json;
    $access_token = $this->getAccessToken();
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
    $this->send_post($url,$payload_json);
  }
  
  public function sendDepositSuccess($openid,$url = null,$first_value,$money_value,$product_value,$remark_value = null ,$first_color = "#4A90E2",$money_color = "#000000",$product_color = "#000000",$remark_color = "#4A90E2"){
    $payload = array();
    $payload['touser'] = $openid;
    $payload['template_id'] = 'p9G19v-R1Bufa_itjftoYQVA_nGPD-v9HLJCceBz9NI';    
    if ($url === null){      
    }else{
      $payload['url'] = $url;
    }    
    $first = array('value' => $first_value, 'color' => $first_color);
    $data = array();
    $data['first'] = $first;
    $money = array('value' => $money_value, 'color' => $money_color);
    $data['money'] = $money;
    $product = array('value' => $product_value, 'color' => $product_color);
    $data['product'] = $product;
    if ($remark_value === null){      
    }else{
      $remark = array('value' => $remark_value, 'color' => $remark_color);
      $data['remark'] = $remark;
    }
    $payload['data'] = $data;
    $payload_json = json_encode($payload);
    // echo $payload_json;
    $access_token = $this->getAccessToken();
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
    $this->send_post($url,$payload_json);
  }
  
  private function send_post($url, $postdata) {  
  
    
    $options = array(  
      'http' => array(  
        'method' => 'POST',  
        'header' => 'Content-type:application/json; encoding=utf-8',  
        'content' => $postdata,  
        'timeout' => 15 * 60 // 超时时间（单位:s）  
      )  
    );  
    $context = stream_context_create($options);  
    $result = file_get_contents($url, false, $context);  
    
    return $result;  
  }  
}

