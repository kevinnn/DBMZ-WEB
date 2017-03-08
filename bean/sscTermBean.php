<?php
Class sscTermBean {
	public $id;
	public $sscTerm;
	public $B;
	public $time;
	public $isMalfunction;
	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				$this->$key = $value;
			}
		}
	}
}
?>