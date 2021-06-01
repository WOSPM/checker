<?php
namespace WOSPM\Checker\Tests;

use WOSPM\Checker;
use PHPUnit\Framework\TestCase;

class UsingWOSPMMetricTest extends TestCase
{
    public function testReadmeNotExists()
    {
        $metric = new Checker\UsingWOSPMMetric();

        $this->assertTrue($metric->check(array())["status"]);
    }
}
