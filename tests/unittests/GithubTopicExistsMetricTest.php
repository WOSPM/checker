<?php
namespace WOSPM\Checker\Tests;

use WOSPM\Checker;
use PHPUnit\Framework\TestCase;

class GithubTopicExistsMetricTest extends TestCase
{
    private $metric;

    public function testTopicExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getTopics'])
        ->getMock();
        $repo->method('getTopics')->will($this->returnValue(array("topic1", "topic2")));

        $this->metric = new Checker\GithubTopicExistsMetric($repo);


        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testTopicNotExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getTopics'])
        ->getMock();
        $repo->method('getTopics')->will($this->returnValue(array()));

        $this->metric = new Checker\GithubTopicExistsMetric($repo);


        $this->assertFalse($this->metric->check($files)["status"]);
    }

}
