<?php
Class bannerBean {
	public $id;
	public $imgUrl;
	public $clickUrl;
	public $isOn;
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