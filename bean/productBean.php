<?php
Class productBean {
	public $id;
	public $categoryId;
	public $brandId;
	public $title;
	public $subTitle;
	public $keywords;
	public $description;
	public $price;
	public $singlePrice;
	public $isOn;
	public $imgUrls;
	public $thumbnailUrl;
	public $content;
	public $isRecommend;
	public $isHot;
	public $isNew;
	public $createdTime;
	public function __construct($obj) {
		foreach ($obj as $key => $value) {
			if (!is_numeric($key)) {
				if (is_numeric($value)) {
					$value = intval($value);
				}
				if ($key == 'keywords' || $key == 'imgUrls') {
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