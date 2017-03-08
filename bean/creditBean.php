<?php
Class creditBean {
	public $id;
	public $userId;
	public $time;
	public $type;
	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				$this->$key = $value;
			}
		}
	}
}
?>