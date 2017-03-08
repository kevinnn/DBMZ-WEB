<?php
Class articleBean {
	public $id;
	public $category;
	public $title;
	public $keywords;
	public $content;
	public $createdTime;
	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				if ($key == 'keywords') {
					if ($value) {
						$this->$key = explode(',', $value);
					} else {
						$this->$key = array();
					}
				} else {
					$this->$key = $value;
				}
			}
		}
	}
}
?>