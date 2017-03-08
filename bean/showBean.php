<?php
Class showBean {
	public $id;
	public $title;
	public $content;
	public $imgUrls;
	public $userId;
	public $yungouId;
	public $createdTime;
	public $term;
	public $total;
	public $resultTime;
	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				if ($key == 'imgUrls') {
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