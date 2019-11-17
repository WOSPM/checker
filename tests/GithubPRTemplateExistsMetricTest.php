<?php
use WOSPM\Checker;

class GithubPRTemplateExistsMetricTest extends PHPUnit_Framework_TestCase
{
    public function testTemplatesExist()
    {
        $metric = new Checker\GithubPRTemplateExistsMetric();

        $files = array(
            ".github",
            "./.github/PULL_REQUEST_TEMPLATE",
            "./.github/PULL_REQUEST_TEMPLATE/t1.md",
            "./.github/PULL_REQUEST_TEMPLATE/t2.md"
        );

        $this->assertTrue($metric->check($files)["status"]);
    }

    public function testGithubNotExists()
    {
        $metric = new Checker\GithubPRTemplateExistsMetric();

        $files = array(
            "nogithub"
        );

        $this->assertFalse($metric->check($files)["status"]);
    }

    public function testTemplateNotExists()
    {
        $metric = new Checker\GithubPRTemplateExistsMetric();
        
        $files = array(
            ".github"
        );

        $this->assertFalse($metric->check($files)["status"]);
    }

}
