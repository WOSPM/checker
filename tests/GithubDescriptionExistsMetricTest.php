<?php
use WOSPM\Checker;

class GithubDescriptionExistsMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;

    public function testDescriptionExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getDescription'])
        ->getMock();
        $repo->method('getDescription')->will($this->returnValue("Description"));

        $this->metric = new Checker\GithubDescriptionExistsMetric($repo);


        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testDescriptionNotExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getDescription'])
        ->getMock();
        $repo->method('getDescription')->will($this->returnValue(""));

        $this->metric = new Checker\GithubDescriptionExistsMetric($repo);


        $this->assertFalse($this->metric->check($files)["status"]);
    }

}
