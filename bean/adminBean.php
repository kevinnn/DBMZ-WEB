<?php
Class adminBean {
	public $id;
	public $username;
	public $encryptedPass;
	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				$this->$key = $value;
			}
		}
	}
}