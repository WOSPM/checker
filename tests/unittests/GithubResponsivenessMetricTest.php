<?php
use WOSPM\Checker;

class GithubResponsivenessMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;

    public function testNoIssues()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getIssues'])
        ->getMock();
        $repo->method('getIssues')->will($this->returnValue(array()));

        $this->metric = new Checker\GithubResponsivenessMetric($repo);

        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testNoContributorIssues()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getIssues'])
        ->getMock();
        $repo->method('getIssues')->will($this->returnValue(array(array("author_association" => "OWNER", "number" => 1), array("author_association" => "OWNER", "number" => 2))));

        $this->metric = new Checker\GithubResponsivenessMetric($repo);

        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testNewIssue()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getIssues'])
        ->getMock();
        $repo->method('getIssues')->will(
            $this->returnValue(
                array(
                    array("author_association" => "NONE", "created_at" => date("y-m-d H:i:s"), "updated_at" => date("y-m-d H:i:s"), "number" => 1),
                    array("author_association" => "OWNER", "number" => 2)
                )
            )
        );

        $this->metric = new Checker\GithubResponsivenessMetric($repo);

        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testNew24HourLaterRespondedIssue()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getIssues'])
        ->getMock();
        $repo->method('getIssues')->will(
            $this->returnValue(
                array(
                    array("author_association" => "NONE", "created_at" => date("y-m-d H:i:s", strtotime("-2 days")), "updated_at" => date("y-m-d H:i:s"), "number" => 1),
                    array("author_association" => "OWNER", "number" => 2)
                )
            )
        );


        $this->metric = new Checker\GithubResponsivenessMetric($repo);

        $this->assertFalse($this->metric->check($files)["status"]);
    }

    public function testNew24HourNotRespondedIssue()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getIssues'])
        ->getMock();
        $repo->method('getIssues')->will(
            $this->returnValue(
                array(
                    array("author_association" => "NONE", "created_at" => date("y-m-d H:i:s", strtotime("-2 days")), "updated_at" => date("y-m-d H:i:s", strtotime("-2 days")), "number" => 1),
                    array("author_association" => "OWNER", "number" => 2)
                )
            )
        );


        $this->metric = new Checker\GithubResponsivenessMetric($repo);

        $this->assertFalse($this->metric->check($files)["status"]);
    }
}
