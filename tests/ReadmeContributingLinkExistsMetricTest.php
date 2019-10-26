<?php
use WOSPM\Checker;

class ReadmeContributingLinkExistsMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;
    public function __construct()
    {
        $this->metric = new Checker\ReadmeContributingLinkExistsMetric();
    }

    public function testContributingLinkExists()
    {
        // Upper case 1
        $files = array(
            "README",
            "CONTRIBUTING"
        );

        $project = $this->getMockBuilder('Checker\Project')->setMethods(['getReadme'])
        ->getMock();
        $project->method('getReadme')->will($this->returnValue("Lorem ipsum [CONTRIBUTING.md](CONTRIBUTING.md)"));

        $this->metric->setProject($project);
        $this->assertFalse($this->metric->check($files)["status"]);

        // Upper case 2
        $files = array(
            "README",
            "CONTRIBUTING.md"
        );

        $this->assertTrue($this->metric->check($files)["status"]);
    }
}
