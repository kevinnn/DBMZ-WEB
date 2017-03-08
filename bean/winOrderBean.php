<?php
Class winOrderBean {
	public $id;
	public $orderId;
	public $addressId;
	public $logisticsCompany;
	public $expressTradeId;
	public $carriage;
	public $logisticsStatus;
	public $yungouId;
	public $productId;
	public $userId;

	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				$this->$key = $value;
			}
		}
	}
}
?>