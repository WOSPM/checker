<?php
use WOSPM\Checker;

class UsingWOSPMMetricTest extends PHPUnit_Framework_TestCase
{
    public function testReadmeNotExists()
    {
        $metric = new Checker\UsingWOSPMMetric();

        $this->assertTrue($metric->check(array())["status"]);
    }
}
