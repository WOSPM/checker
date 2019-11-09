<?php
use WOSPM\Checker;
class GithubIssueTemplateExistsMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;
    public function __construct()
    {
        $this->metric = new Checker\GithubIssueTemplateExistsMetric();
    }

    public function testTemplatesExist()
    {
        $files = array(
            ".github",
            "./.github/ISSUE_TEMPLATE",
            "./.github/ISSUE_TEMPLATE/t1.md",
            "./.github/ISSUE_TEMPLATE/t2.md"
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
            "./.github/ISSUE_TEMPLATE",
        );

        $this->assertFalse($this->metric->check($files)["status"]);
    }

}
