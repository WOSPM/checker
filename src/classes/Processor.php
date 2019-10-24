<?php

namespace WOSPM\Checker;

class Processor {
	private $metrics = array();
	public function __contruct() {
		$metric = new Checker\ReadmeExistsMetric();


	}
}