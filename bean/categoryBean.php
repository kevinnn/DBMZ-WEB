<?php
Class categoryBean {
	public $id;
	public $name;
	public $isOn;
	public $isOnIndex;
	public $coverUrl;
	public $bannerUrl;
	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				if (is_numeric($value)) {
					$value = intval($value);
				}
				$this->$key = $value;
			}
		}
	}
}
?>