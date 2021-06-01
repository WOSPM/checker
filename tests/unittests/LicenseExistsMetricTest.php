<?php
namespace WOSPM\Checker\Tests;

use WOSPM\Checker;
use PHPUnit\Framework\TestCase;

class LicenseExistsMetricTest extends TestCase
{
    public function testLicenseExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLicense'])
        ->getMock();
        $repo->method('getLicense')->will($this->returnValue(array("name" => "MIT License")));

        $metric = new Checker\LicenseExistsMetric($repo);

        $this->assertTrue($metric->check($files)["status"]);
    }

    public function testLicenseNotExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLicense'])
        ->getMock();
        $repo->method('getLicense')->will($this->returnValue(null));

        $metric = new Checker\LicenseExistsMetric($repo);

        $this->assertFalse($metric->check($files)["status"]);
    }
}
