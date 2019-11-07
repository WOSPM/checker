<?php
use WOSPM\Checker;

class ReadmeInstallationExistsMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;
    public function __construct()
    {
        $this->metric = new Checker\ReadmeInstallationExistsMetric();
    }

    public function testTocLinkExists()
    {
        // Upper case 1
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $project = $this->getMockBuilder('Checker\Project')->setMethods(['getReadmeFileName'])
        ->getMock();
        $project->method('getReadmeFileName')->will($this->returnValue("README"));

        $this->metric->setProject($project);

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
                ),
                4 => array(
                    "raw"    => "## Local Install",
                    "parsed" => "Local Install",
                    "slug"   => "local-install",
                    "level"  => 2
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
                ),
                2 => array(
                    "raw" => "- [Basic](#basic)",
                    "links" => array(
                        0 => array(
                            "text" => "Basic",
                            "url"  => "#basic"
                        )
                    )
                ),
                3 => array(
                    "raw" => "- [Local Install](#local-install)",
                    "links" => array(
                        0 => array(
                            "text" => "Local Install",
                            "url"  => "#local-install"
                        )
                    )
                )
            )
        );
        $parser = $this->getMockBuilder('Checker\Parser')->setMethods(['parse', 'setFile'])
        ->getMock();
        $parser->method('parse')->will($this->returnValue($parsed));
        $parser->method('setFile')->will($this->returnValue(true));

        $this->metric->setParser($parser);


        $this->assertTrue($this->metric->check($files)["status"]);
    }

    public function testTocLinkNotExists()
    {
        // Upper case 1
        $files = array(
            "README",
            "CODE_OF_CONDUCT"
        );

        $project = $this->getMockBuilder('Checker\Project')->setMethods(['getReadmeFileName'])
        ->getMock();
        $project->method('getReadmeFileName')->will($this->returnValue("README"));

        $this->metric->setProject($project);

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
                    "raw" => "[Contribute](contribute.md)",
                    "links" => array(
                        0 => array(
                            "text" => "Contribute",
                            "url"  => "contribute.md"
                        )
                    )
                )
            )
        );
        $parser = $this->getMockBuilder('Checker\Parser')->setMethods(['parse', 'setFile'])
        ->getMock();
        $parser->method('parse')->will($this->returnValue($parsed));
        $parser->method('setFile')->will($this->returnValue(true));

        $this->metric->setParser($parser);


        $this->assertFalse($this->metric->check($files)["status"]);
    }
}
