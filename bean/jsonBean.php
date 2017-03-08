<?php
Class jsonBean {
	public $code;
	public $data;
	public $msg;
	public function __construct ($code,$data,$msg) {
		$this->code = $code;
		$this->data = $data;
		$this->msg = $msg;
	}
}
?>