<?php
namespace WOSPM\Checker\Tests;

use WOSPM\Checker;
use PHPUnit\Framework\TestCase;

class ReadmeContributingLinkExistsMetricTest extends TestCase
{
    public function testContributingLinkExists()
    {
        $metric = new Checker\ReadmeContributingLinkExistsMetric();
        // Upper case 1
        $files = array(
            "README",
            "CONTRIBUTING"
        );

        $project = $this->getMockBuilder('Checker\Project')->setMethods(['getReadme'])
        ->getMock();
        $project->method('getReadme')->will($this->returnValue("Lorem ipsum [CONTRIBUTING.md](CONTRIBUTING.md)"));

        $metric->setProject($project);
        $this->assertFalse($metric->check($files)["status"]);

        // Upper case 2
        $files = array(
            "README",
            "CONTRIBUTING.md"
        );

        $this->assertTrue($metric->check($files)["status"]);
    }
}
