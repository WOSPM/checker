<?php
namespace WOSPM\Checker\Tests;

use WOSPM\Checker;
use PHPUnit\Framework\TestCase;

class GithubIssueTemplateExistsMetricTest extends TestCase
{
    public function testTemplatesExist()
    {
        $metric = new Checker\GithubIssueTemplateExistsMetric();
        $files = array(
            ".github",
            "./.github/ISSUE_TEMPLATE",
            "./.github/ISSUE_TEMPLATE/t1.md",
            "./.github/ISSUE_TEMPLATE/t2.md"
        );

        $this->assertTrue($metric->check($files)["status"]);
    }

    public function testGithubNotExists()
    {
        $metric = new Checker\GithubIssueTemplateExistsMetric();
        $files = array(
            "nogithub"
        );

        $this->assertFalse($metric->check($files)["status"]);
    }

    public function testTemplateNotExists()
    {
        $metric = new Checker\GithubIssueTemplateExistsMetric();
        $files = array(
            ".github",
            "./.github/ISSUE_TEMPLATE",
        );

        $this->assertFalse($metric->check($files)["status"]);
    }

}
