<?php

require_once "../../config/config.php";
include_once __ROOT__.'/database/mysql.php';
class JSSDK {
  private $appId;
  private $appSecret;
  private $url;

  public function __construct($appId, $appSecret ,$url) {
    $this->appId = $appId;
    $this->appSecret = $appSecret;
    $this->url = $url;
  }

  public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();

    // 注意 URL 一定要动态获取，不能 hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    

    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$this->url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $this->url,
      "signature" => $signature,
      "rawString" => $string,
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }



  private function getJsApiTicket() {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = $this->getJsApiTicketAndTimeFromDB();
    if ( $data && $data[1] > time()) {
      $ticket = $data[0];
    } elseif($data) {
      $accessToken = $this->getAccessToken();
      // 如果是企业号用以下 URL 获取 ticket
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode(file_get_contents($url));

      $ticket = $res->ticket;
      if ($ticket) {
        $data[1] = time() + 7000;
        $data[0] = $ticket;
        $this->updateJsApiTicketAndTimeFromDB($data[0],$data[1]);
      }
      
    }else{
      
      $accessToken = $this->getAccessToken();
      // 如果是企业号用以下 URL 获取 ticket
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode(file_get_contents($url));

      $ticket = $res->ticket;
      if ($ticket) {
        $data[1] = time() + 7000;
        $data[0] = $ticket;
        $this->insertJsApiTicketAndTimeFromDB($data[0],$data[1]);
      }
    }
    return $ticket;
  }
 

  

  private function getAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = $this->getAccessTokenAndTimeFromDB();
    if ( $data && $data[1] > time()) {      
      $access_token = $data[0];
    } elseif($data) {
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
    }else{
      // 如果是企业号用以下URL获取access_token
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
      $res = json_decode(file_get_contents($url));

      
      $access_token = $res->access_token;
      if ($access_token) {
        $data[1] = time() + 7000;
        $data[0] = $access_token;
        $this->insertAccessTokenAndTimeFromDB($data[0],$data[1]);
      }
    }
    return $access_token;
  }
  
  private function getAccessTokenAndTimeFromDB() {
    $sql = "select value,expire_time from `Wx` where `key` = 'access_token'";
    $result = mysql::queryTables($sql);
    if (isset($result[0])){
      return $result[0];
    }else{
      return false;
    }
  }
  
  private function updateAccessTokenAndTimeFromDB($value,$time) {
    $sql = "update `Wx` set value='$value',expire_time=$time where `key` = 'access_token'";
    return mysql::execute($sql);
  }
  private function insertAccessTokenAndTimeFromDB($value,$time) {
    $sql = "insert into `Wx` set value='$value',expire_time=$time,`key` = 'access_token'";
    return mysql::execute($sql);
  }
  
  private function getJsApiTicketAndTimeFromDB() {
    $sql = "select value,expire_time from `Wx` where `key`= 'jsapi_ticket'";
    $result = mysql::queryTables($sql);
    if (isset($result[0])){
      return $result[0];
    }else{
      return false;
    }
  }
  private function updateJsApiTicketAndTimeFromDB($value,$time) {
    $sql = "update `Wx` set value='$value',expire_time=$time where `key` = 'jsapi_ticket'";
    return mysql::execute($sql);
  }
  private function insertJsApiTicketAndTimeFromDB($value,$time) {
    $sql = "insert into `Wx` set value='$value',expire_time=$time,`key` = 'jsapi_ticket'";
    return mysql::execute($sql);
  }

  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 2);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 2);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }


}

