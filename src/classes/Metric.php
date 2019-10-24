<?php
namespace WOSPM\Checker;

class Metric {
	private $code    = "WOSPM0001";
	private $title   = "Nothing is working";
	private $message = "It seems nothing is working.";
	private $type    = MetricType::INFO;

	public function check($files) {
		// do some check
		$this->success();
	}

	public function success() {
		return $this->result(true);
	}

	public function fail() {
		return $this->result();
	}

	private function result($status = false) {
		return array(
			"code"    => $this->code,
			"title"   => $this->title,
			"message" => $this->message,
			"type"    => $this->type,
			"status"  => $status
		);
	}
}