<?php
use WOSPM\Checker;

class ReadmeCocLinkExistsMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;
    public function __construct()
    {
        $this->metric = new Checker\ReadmeCocLinkExistsMetric();
    }

    public function testContributingLinkExists()
    {
        // Upper case 1
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $project = $this->getMockBuilder('Checker\Project')->setMethods(['getReadme'])
        ->getMock();
        $project->method('getReadme')->will($this->returnValue("Lorem ipsum [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md)"));

        $this->metric->setProject($project);
        $this->assertFalse($this->metric->check($files)["status"]);

        // Upper case 2
        $files = array(
            "README",
            "CODE_OF_CONDUCT.md"
        );

        $this->assertTrue($this->metric->check($files)["status"]);
    }
}
