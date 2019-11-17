<?php
use WOSPM\Checker;

class GithubCustomLabelUsedMetric extends PHPUnit_Framework_TestCase
{
    private $metric;

    public function testCustomLabelUsed()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels', 'getLabelIssues'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue(array(array("name" => "label1", "default" => true), array("name" => "label2", "default" => false))));
        $repo->method('getLabelIssues')->will($this->returnValue(array("issue1", "issue2")));

        $this->metric = new Checker\GithubCustomLabelUsedMetric($repo);


        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testCustomLabelNotUsed()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels', 'getLabelIssues'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue(array(array("name" => "label1", "default" => true), array("name" => "label2", "default" => false))));
        $repo->method('getLabelIssues')->will($this->returnValue(array()));

        $this->metric = new Checker\GithubCustomLabelUsedMetric($repo);


        $this->assertFalse($this->metric->check($files)["status"]);
    }

}
