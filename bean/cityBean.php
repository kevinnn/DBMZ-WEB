<?php
Class cityBean {
	public $id;
	public $cityID;
	public $city;
	public $father;

	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				$this->$key = $value;
			}
		}
	}
}

?>