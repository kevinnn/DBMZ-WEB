<?php
Class shoppingCartBean {
	public $id;
	public $userId;
	public $yungouId;
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