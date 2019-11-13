<?php
use WOSPM\Checker;

class GithubLabelExistsMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;

    public function testTopicExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue(array("label1", "label2")));

        $this->metric = new Checker\GithubLabelExistsMetric($repo);


        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testTopicNotExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue(array()));

        $this->metric = new Checker\GithubLabelExistsMetric($repo);


        $this->assertFalse($this->metric->check($files)["status"]);
    }

}
