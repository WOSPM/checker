<?php
use WOSPM\Checker;
class LicenseExistsMetricTest extends PHPUnit_Framework_TestCase
{
    public function testLicenseExists()
    {
        $metric = new Checker\LicenseExistsMetric();

        // Case 1
        $files = array(
            "LICENSE"
        );

        $this->assertTrue($metric->check($files)["status"]);

        // Case 2
        $files = array(
            "LICENSE.md"
        );

        $this->assertTrue($metric->check($files)["status"]);

        // Case 3
        $files = array(
            "license"
        );

        $this->assertTrue($metric->check($files)["status"]);

        // Case 4
        $files = array(
            "license.md"
        );

        $this->assertTrue($metric->check($files)["status"]);
    }

    public function testLicenseNotExists()
    {
        $metric = new Checker\LicenseExistsMetric();

        $files = array(
            "NOLICENSE"
        );

        $this->assertFalse($metric->check($files)["status"]);
    }

}
