<?php
namespace WOSPM\Checker\Tests;

use WOSPM\Checker;
use PHPUnit\Framework\TestCase;

class GithubLabelExistsMetricTest extends TestCase
{
    private $metric;

    public function testLabelExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue(array(array("name" =>"label1"), array("name" =>"label2"))));

        $this->metric = new Checker\GithubLabelExistsMetric($repo);


        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testLabelNotExists()
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
