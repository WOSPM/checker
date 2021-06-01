<?php
namespace WOSPM\Checker\Tests;

use WOSPM\Checker;
use PHPUnit\Framework\TestCase;

class ReadmeCocLinkExistsMetricTest extends TestCase
{
    public function testContributingLinkExists()
    {
        $metric = new Checker\ReadmeCocLinkExistsMetric();
        // Upper case 1
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $project = $this->getMockBuilder('Checker\Project')->setMethods(['getReadme'])
        ->getMock();
        $project->method('getReadme')->will($this->returnValue("Lorem ipsum [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md)"));

        $metric->setProject($project);
        $this->assertFalse($metric->check($files)["status"]);

        // Upper case 2
        $files = array(
            "README",
            "CODE_OF_CONDUCT.md"
        );

        $this->assertTrue($metric->check($files)["status"]);
    }
}
