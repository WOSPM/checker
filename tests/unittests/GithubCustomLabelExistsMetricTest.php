<?php
use WOSPM\Checker;

class GithubCustomLabelExistsMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;

    public function testCustomLableExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $labels = array(
            array("default" => true),
            array("default" => false),
            array("default" => true)
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue($labels));

        $this->metric = new Checker\GithubCustomLabelExistsMetric($repo);


        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testCustomLabelNotExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $labels = array(
            array("default" => true),
            array("default" => true),
            array("default" => true)
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue($labels));

        $this->metric = new Checker\GithubCustomLabelExistsMetric($repo);


        $this->assertFalse($this->metric->check($files)["status"]);
    }

}
