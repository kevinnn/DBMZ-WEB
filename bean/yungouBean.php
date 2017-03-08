<?php
Class yungouBean {
	public $id;
	public $productId;
	public $term;
	public $status;
	public $A;
	public $B;
	public $result;
	public $orderId;
	public $createdTime;
	public $saleCount;
	public $startTime;
	public $sscTerm;
	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				if (is_numeric($value) && $key != 'B') {
					$value = intval($value);
				}
				$this->$key = $value;
			}
		}
	}
}
?>