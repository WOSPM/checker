<?php
use WOSPM\Checker;
class LicenseExistsMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;
    public function __construct()
    {
        $this->metric = new Checker\LicenseExistsMetric();
    }

    public function testLicenseExists()
    {
        // Upper case 1
        $files = array(
            "LICENSE"
        );

        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testLicenseNotExists()
    {
        $files = array(
            "NOLICENSE"
        );

        $this->assertFalse($this->metric->check($files)["status"]);
    }

}
