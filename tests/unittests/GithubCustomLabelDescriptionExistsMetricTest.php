<?php
namespace WOSPM\Checker\Tests;

use WOSPM\Checker;
use PHPUnit\Framework\TestCase;

class GithubCustomLabelDescriptionExistsMetricTest extends TestCase
{
    private $metric;

    public function testCustomLabelDescriptionExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $labels = array(
            array("description" => "description"),
            array("description" => "description"),
            array("description" => "description")
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue($labels));

        $this->metric = new Checker\GithubCustomLabelDescriptionExistsMetric($repo);


        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testCustomLabelDescriptionNotExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $labels = array(
            array("description" => "description"),
            array("description" => ""),
            array("description" => "description")
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue($labels));

        $this->metric = new Checker\GithubCustomLabelDescriptionExistsMetric($repo);


        $this->assertFalse($this->metric->check($files)["status"]);
    }

}
