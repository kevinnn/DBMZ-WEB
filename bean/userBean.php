<?php
Class userBean {
	public $id;
	public $userName;
	public $phoneNumber;
	public $encryptedPass;
	public $balance;
	public $credits;
	public $loginTIme;
	public $remark;
	public $loginArea;
	public $ip;
	public $registerTime;
	public $code;
	public $type;
	public $openId;
	public $qqId;
	public $avatorUrl;
	public $invitedBy;
	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				$this->$key = $value;
			}
		}
	}
}
?>