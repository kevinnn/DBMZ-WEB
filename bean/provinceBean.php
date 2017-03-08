<?php
Class provinceBean {
	public $id;
	public $provinceID;
	public $province;

	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				$this->$key = $value;
			}
		}
	}
}
?>