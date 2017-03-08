<?php
Class globalBean {
	public $id;
	public $key;
	public $value;
	public $createdTime;

	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				$this->$key = $value;
			}
		}
	}
}
?>