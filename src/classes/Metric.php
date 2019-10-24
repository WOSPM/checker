<?php
namespace WOSPM\Checker;

class Metric {
	private $code  = "WOSPM0001";

	private $title = "Nothing is working";

	private $message = "It seems nothing is working.";

	public function check($files) {
		
	}

	private function success() {
		return $this->result(true);
	}

	private function fail() {
		return $this->result();
	}

	private function result($status = false) {
		return array(
			"code" => $this->code,
			"title"   => $this->title,
			"message" => $this->message,
			"status"  => $status
		);
	}
}