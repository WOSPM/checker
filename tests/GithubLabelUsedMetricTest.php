<?php
use WOSPM\Checker;

class GithubLabelUsedMetric extends PHPUnit_Framework_TestCase
{
    private $metric;

    public function testLabelUsed()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels', 'getLabelIssues'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue(array(array("name" => "label1"), array("name" => "label2"))));
        $repo->method('getLabelIssues')->will($this->returnValue(array("issue1", "issue2")));

        $this->metric = new Checker\GithubLabelUsedMetric($repo);


        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testLabelNotUsed()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels', 'getLabelIssues'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue(array(array("name" => "label1"), array("name" => "label2"))));
        $repo->method('getLabelIssues')->will($this->returnValue(array()));

        $this->metric = new Checker\GithubLabelUsedMetric($repo);


        $this->assertFalse($this->metric->check($files)["status"]);
    }

}
