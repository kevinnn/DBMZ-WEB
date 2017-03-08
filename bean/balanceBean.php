<?php
Class balanceBean {
	public $id;
	public $userId;
	public $type;
	public $payment;
	public $time;
	public $tradeId;
	public $amount;
	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				$this->$key = $value;
			}
		}
	}
}
?>