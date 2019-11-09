<?php
use WOSPM\Checker;

class GithubPRTemplateExistsMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;
    public function __construct()
    {
        $this->metric = new Checker\GithubPRTemplateExistsMetric();
    }

    public function testTemplatesExist()
    {
        $files = array(
            ".github",
            "./.github/PULL_REQUEST_TEMPLATE",
            "./.github/PULL_REQUEST_TEMPLATE/t1.md",
            "./.github/PULL_REQUEST_TEMPLATE/t2.md"
        );

        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testGithubNotExists()
    {
        $files = array(
            "nogithub"
        );

        $this->assertFalse($this->metric->check($files)["status"]);
    }

    public function testTemplateNotExists()
    {
        $files = array(
            ".github",
            "./.github/PULL_REQUEST_TEMPLATE",
        );

        $this->assertFalse($this->metric->check($files)["status"]);
    }

}
