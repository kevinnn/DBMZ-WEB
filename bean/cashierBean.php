<?php
Class cashierBean {
	public $id;
	public $count;
	public $overTime;
	public $isPay;
	public $balancePay;
	public $tradeId;
	public $payment;
	public $cashierid;
	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				$this->$key = $value;
			}
		}
	}
}
?>