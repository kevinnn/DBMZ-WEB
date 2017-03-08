<?php
require_once __ROOT__.'/qiniu/autoload.php';
include_once __ROOT__.'/bean/jsonBean.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

Class uploaderController {
	private static $accessKey = 'v_d4R_-nzDOrMJUnB5tynyL5IRfTtM9clDKj8Gtr';
	private static $secretKey = 's0LCB1kKbYBfKOIQqiNKstpjwAdhH31pzmzO-vfN';
	private static $baseUrl = 'http://7xs9hx.com1.z0.glb.clouddn.com';
	private static $bucket = 'yyyg';
	public static function uploader ($name) {
		// 构建鉴权对象
		$auth = new Auth(self::$accessKey, self::$secretKey);

		// 生成上传 Token
		$token = $auth->uploadToken(self::$bucket);

		// 要上传文件的本地路径
		$filePath = $_FILES['file']['tmp_name'];

		// 上传到七牛后保存的文件名
		$names = explode('.', $_FILES['file']['name']);
		$key = $name.'.'.$names[1];

		// 初始化 UploadManager 对象并进行文件的上传
		$uploadMgr = new UploadManager();

		// 调用 UploadManager 的 putFile 方法进行文件的上传
		list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
		if ($err !== null) {
		    return json_encode(new jsonBean(403,$err,'上传失败'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		} else {
			$key = self::$baseUrl.'/'.$ret['key'];
			return json_encode(new jsonBean(200,$key,'上传成功'),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		}
	}
	public static function ueditorUploader ($name) {
		$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(__ROOT__."/public/ueditor/php/config.json")), true);
		$action = $_GET['action'];
		switch ($action) {
		    case 'config':
		        $result =  json_encode($CONFIG);
		        break;

		    /* 上传图片 */
		    case 'uploadimage':
		    /* 上传涂鸦 */
		    case 'uploadscrawl':
		    /* 上传视频 */
		    case 'uploadvideo':
		    /* 上传文件 */
		    case 'uploadfile':
		        $result = include(__ROOT__."/public/ueditor/php/action_upload.php");
		        break;

		    /* 列出图片 */
		    case 'listimage':
		        $result = include(__ROOT__."/public/ueditor/php/action_list.php");
		        break;
		    /* 列出文件 */
		    case 'listfile':
		        $result = include(__ROOT__."/public/ueditor/php/action_list.php");
		        break;

		    /* 抓取远程文件 */
		    case 'catchimage':
		        $result = include(__ROOT__."/public/ueditor/php/action_crawler.php");
		        break;

		    default:
		        $result = json_encode(array(
		            'state'=> '请求地址出错'
		        ));
		        break;
		}
		$result = json_decode($result,true);

		// 构建鉴权对象
		$auth = new Auth(self::$accessKey, self::$secretKey);

		// 生成上传 Token
		$token = $auth->uploadToken(self::$bucket);

		// 要上传文件的本地路径
		$filePath = '../../'.str_replace($_SERVER['DOCUMENT_ROOT'], "", $result['url']);

		// 上传到七牛后保存的文件名
		$key = $name.$result['type'];
		if ($result['type'] == '') {
			return json_encode($result);
		}
		// 初始化 UploadManager 对象并进行文件的上传
		$uploadMgr = new UploadManager();

		// 调用 UploadManager 的 putFile 方法进行文件的上传
		list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
		if ($err !== null) {
		    unlink($filePath);
		    return json_encode(array(
		    	"state" => $err,
		    ));
		} else {
			$key = self::$baseUrl.'/'.$ret['key'];
			$result['url'] = $key;
		    unlink($filePath);
			return json_encode($result);
		}
	}
}

?>