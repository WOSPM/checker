<?php
namespace WOSPM\Checker;

class ReadmeExistsMetric {
	private $code  = "WOSPM0002";

	private $title = "README file shoud be created";

	private $message = "Every open source project should have a README file.";

	public function check($files) {
		$check = in_array("README", $files) ||
			in_array("README.md", $files) ||
			in_array("readme", $files) ||
			in_array("readme.md", $files);


		return $this->result($check);
	}

	private function success() {
		return $this->result(true);
	}

	private function fail() {
		return $this->result();
	}

	private function result($status = false) {
		return array(
			"code"    => $this->code,
			"title"   => $this->title,
			"message" => $this->message,
			"status"  => $status
		);
	}
}