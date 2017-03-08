<?php
Class addressBean {
	public $id;
	public $userId;
	public $provinceID;
	public $cityID;
	public $areaID;
	public $street;
	public $postCode;
	public $receiver;
	public $idCode;
	public $phoneNumber;
	public $status;

	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				$this->$key = $value;
			}
		}
	}
}
?>