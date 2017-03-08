<?php
Class areaBean {
	public $id;
	public $areaID;
	public $area;
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