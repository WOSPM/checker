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
        // Case 1
        $files = array(
            "LICENSE"
        );

        $this->assertTrue($this->metric->check($files)["status"]);

        // Case 2
        $files = array(
            "LICENSE.md"
        );

        $this->assertTrue($this->metric->check($files)["status"]);

        // Case 3
        $files = array(
            "license"
        );

        $this->assertTrue($this->metric->check($files)["status"]);

        // Case 4
        $files = array(
            "license.md"
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
