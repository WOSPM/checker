<?php
namespace WOSPM\Checker;

class ReadmeExistsMetric extends Metric {
	public function __construct() {
		$this->code    = "WOSPM0002";
		$this->title   = "README file shoud be created";
		$this->message = "Every open source project should have a README file.";
		$this->type    = MetricType::ERROR;
	}

	public function check($files) {
		$check = in_array("README", $files) ||
			in_array("README.md", $files) ||
			in_array("readme", $files) ||
			in_array("readme.md", $files);


		return parent::result($check);
	}
}