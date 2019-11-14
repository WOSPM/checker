<?php
use WOSPM\Checker;

class GithubGFIAndHWLabelsExistMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;

    public function testGFIAndHWExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $labels = array(
            array("name" => "good first issue"),
            array("name" => "help wanted"),
            array("name" => "documentation")
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue($labels));

        $this->metric = new Checker\GithubGFIAndHWLabelsExistMetric($repo);


        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testGFIAndHWNotExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $labels = array(
            array("name" => "bug"),
            array("name" => "feature"),
            array("name" => "documentation")
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue($labels));

        $this->metric = new Checker\GithubGFIAndHWLabelsExistMetric($repo);


        $this->assertFalse($this->metric->check($files)["status"]);
    }

    public function testGFINotExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $labels = array(
            array("name" => "bug"),
            array("name" => "help wanted"),
            array("name" => "documentation")
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue($labels));

        $this->metric = new Checker\GithubGFIAndHWLabelsExistMetric($repo);


        $this->assertFalse($this->metric->check($files)["status"]);
    }



    public function testHWNotExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $labels = array(
            array("name" => "bug"),
            array("name" => "good first issue"),
            array("name" => "documentation")
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabels'])
        ->getMock();
        $repo->method('getLabels')->will($this->returnValue($labels));

        $this->metric = new Checker\GithubGFIAndHWLabelsExistMetric($repo);


        $this->assertFalse($this->metric->check($files)["status"]);
    }
}
