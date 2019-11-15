<?php
use WOSPM\Checker;

class GithubGFIAndHWLabelsUsedMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;

    public function testLabelGFIUsed()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabelIssues'])
        ->getMock();
        $repo->expects($this->once())
            ->method('getLabelIssues')
            ->with("good first issue")
            ->will($this->returnValue(array("issue1", "issue2")));
        

        $this->metric = new Checker\GithubGFIAndHWLabelsUsedMetric($repo);

        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testLabelHWUsed()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabelIssues'])
        ->getMock();
        $repo->expects($this->at(0))
            ->method('getLabelIssues')
            ->with($this->equalTo("good first issue"))
            ->will($this->returnValue(array()));
        $repo->expects($this->at(1))
            ->method('getLabelIssues')
            ->with($this->equalTo("help wanted"))
            ->will($this->returnValue(array("issue1", "issue2"))); 

        $this->metric = new Checker\GithubGFIAndHWLabelsUsedMetric($repo);

        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testLabelsNotUsed()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getLabelIssues'])
        ->getMock();
        $repo->expects($this->at(0))
            ->method('getLabelIssues')
            ->with("good first issue")
            ->will($this->returnValue(array()));
        $repo->expects($this->at(1))
            ->method('getLabelIssues')
            ->with("help wanted")
            ->will($this->returnValue(array())); 

        $this->metric = new Checker\GithubGFIAndHWLabelsUsedMetric($repo);

        $this->assertFalse($this->metric->check($files)["status"]);
    }

}
