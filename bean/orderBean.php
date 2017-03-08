<?php
Class orderBean {
	public $id;
	public $userId;
	public $yungouId;
	public $productId;
	public $count;
	public $createdTime;
	public $numberStart;
	public $numberEnd;
	public $isWin;
	public $status;
	public $logisticsCompany;
	public $expressTradeId;
	public $carriage;
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