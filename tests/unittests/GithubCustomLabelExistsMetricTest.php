<?php
namespace WOSPM\Checker\Tests;

use WOSPM\Checker;
use PHPUnit\Framework\TestCase;

class GithubCustomLabelExistsMetricTest extends TestCase
{
    private $metric;

    public function testCustomLableExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $labels = array(
            array("name" => "label1", "default" => true),
            array("name" => "label2", "default" => false),
            array("name" => "label3", "default" => true)
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
            array("name" => "label1", "default" => true),
            array("name" => "label2", "default" => true),
            array("name" => "label3", "default" => true)
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue($labels));

        $this->metric = new Checker\GithubCustomLabelExistsMetric($repo);


        $this->assertFalse($this->metric->check($files)["status"]);
    }

}
