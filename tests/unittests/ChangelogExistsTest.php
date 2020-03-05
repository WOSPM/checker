<?php
use WOSPM\Checker;

class ChangelogExistsTest extends PHPUnit_Framework_TestCase
{
    public function testChangelogNotExists()
    {
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $repo = $this->getMockBuilder('Checker\GithubVendor')->getMock();

        $project = $this->getMockBuilder('Checker\Project')->setMethods(['getReadme','getReadmeFileName'])
        ->getMock();
        $project->method('getReadme')->will($this->returnValue("Lorem ipsum [CONTRIBUTING.md](CONTRIBUTING.md)"));
        $project->method('getReadmeFileName')->will($this->returnValue("README"));

        $metric = new Checker\ChangelogExistsMetric($repo);

        $metric->setProject($project);

        $parser = $this->getMockBuilder('Checker\Parser')->setMethods(['parse', 'setFile'])
        ->getMock();
        $parser->method('parse')->will($this->returnValue(array("headlines" => array(), "links" => array())));
        $parser->method('setFile')->will($this->returnValue(true));

        $metric->setParser($parser);

        $this->assertFalse($metric->check($files)["status"]);
    }

    public function testChangelogExists()
    {

        $repo = $this->getMockBuilder('Checker\GithubVendor')->getMock();

        $metric = new Checker\ChangelogExistsMetric($repo);
        // Upper case 1
        $files = array(
            "README",
            "CODE_OF_CONDUCT",
            "CHANGELOG.md"
        );

        $project = $this->getMockBuilder('Checker\Project')->setMethods(['getReadmeFileName'])
        ->getMock();
        $project->method('getReadmeFileName')->will($this->returnValue("README"));

        $metric->setProject($project);

        $parsed = array(
            "headlines" => array(
                1 => array(
                    "raw"    => "# Headline",
                    "parsed" => "Headline",
                    "slug"   => "headline",
                    "level"  => 1
                )
            ),
            "links" => array(
                1 => array(
                    "raw" => "- [Intro](#intro)",
                    "links" => array(
                        0 => array(
                            "text" => "Intro",
                            "url"  => "#intro"
                        )
                    )
                )
            )
        );
        $parser = $this->getMockBuilder('Checker\Parser')->setMethods(['parse', 'setFile'])
        ->getMock();
        $parser->method('parse')->will($this->returnValue($parsed));
        $parser->method('setFile')->will($this->returnValue(true));

        $metric->setParser($parser);

        $this->assertTrue($metric->check($files)["status"]);
    }

    public function testChangelogLinkExists()
    {
        $repo = $this->getMockBuilder('Checker\GithubVendor')->getMock();

        $metric = new Checker\ChangelogExistsMetric($repo);
        // Upper case 1
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $project = $this->getMockBuilder('Checker\Project')->setMethods(['getReadmeFileName'])
        ->getMock();
        $project->method('getReadmeFileName')->will($this->returnValue("README"));

        $metric->setProject($project);

        $parsed = array(
            "headlines" => array(
                1 => array(
                    "raw"    => "# Headline",
                    "parsed" => "Headline",
                    "slug"   => "headline",
                    "level"  => 1
                ),
                2 => array(
                    "raw"    => "## Intro",
                    "parsed" => "Intro",
                    "slug"   => "intro",
                    "level"  => 2
                ),
                3 => array(
                    "raw"    => "## Basic",
                    "parsed" => "Basic",
                    "slug"   => "basic",
                    "level"  => 2
                )
            ),
            "links" => array(
                0 => array(
                    "raw" => "[Changelog](https://doc.repo.io/doc)",
                    "links" => array(
                        0 => array(
                            "text" => "Changelog",
                            "url"  => "https://doc.repo.io/doc"
                        )
                    )
                )
            )
        );
        $parser = $this->getMockBuilder('Checker\Parser')->setMethods(['parse', 'setFile'])
        ->getMock();
        $parser->method('parse')->will($this->returnValue($parsed));
        $parser->method('setFile')->will($this->returnValue(true));

        $metric->setParser($parser);


        $this->assertTrue($metric->check($files)["status"]);
    }
}
