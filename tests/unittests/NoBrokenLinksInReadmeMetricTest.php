<?php
namespace WOSPM\Checker\Tests;

use WOSPM\Checker;
use PHPUnit\Framework\TestCase;

class NoBrokenLinksInReadmeMetricTest extends TestCase
{
    public function testAllHeadlineLinksExist()
    {
        $metric = new Checker\NoBrokenLinksInReadmeMetric();
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

        $metric->setParser($parser);


        $this->assertTrue($metric->check($files)["status"]);
    }

    public function testSomeHeadlineLinksNotExist()
    {
        $metric = new Checker\NoBrokenLinksInReadmeMetric();
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
                ),
                4 => array(
                    "raw"    => "## Local Install",
                    "parsed" => "Local Install",
                    "slug"   => "local-install",
                    "level"  => 2
                )
            ),
            "links" => array(
                0 => array(
                    "raw" => "[Non-existing Link](#Non-existing)",
                    "links" => array(
                        0 => array(
                            "text" => "Non-existing Link",
                            "url"  => "Non-existing"
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


        $this->assertFalse($metric->check($files)["status"]);
    }

    public function testAllExternalLinksExist()
    {
        $metric = new Checker\NoBrokenLinksInReadmeMetric();
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
            "headlines" => array(),
            "links" => array(
                0 => array(
                    "raw" => "[External Link 1](http://www.example.com/page1)",
                    "links" => array(
                        0 => array(
                            "text" => "External Link 1",
                            "url"  => "http://www.example.com/page1"
                        )
                    )
                ),
                1 => array(
                    "raw" => "[External Link 2](http://www.example.com/page2)",
                    "links" => array(
                        0 => array(
                            "text" => "External Link 2",
                            "url"  => "http://www.example.com/page2"
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

        $response = $this->getMockBuilder('\GuzzleHttp\Psr7\Response')->setMethods(['getStatusCode'])
        ->getMock();
        $response->method('getStatusCode')->will($this->returnValue("200"));

        $browser = $this->getMockBuilder('\GuzzleHttp\Client')->setMethods(['request'])
        ->getMock();
        $browser->method('request')->will($this->returnValue($response));

        $metric->setBrowser($browser);

        $this->assertTrue($metric->check($files)["status"]);
    }

    public function testSomeExternalLinksNotExist()
    {
        $metric = new Checker\NoBrokenLinksInReadmeMetric();
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
            "headlines" => array(),
            "links" => array(
                0 => array(
                    "raw" => "[External Link 1](http://www.example.com/page1)",
                    "links" => array(
                        0 => array(
                            "text" => "External Link 1",
                            "url"  => "http://www.example.com/page1"
                        )
                    )
                ),
                1 => array(
                    "raw" => "[External Link 2](http://www.example.com/page2)",
                    "links" => array(
                        0 => array(
                            "text" => "External Link 2",
                            "url"  => "http://www.example.com/page2"
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

        $response = $this->getMockBuilder('\GuzzleHttp\Psr7\Response')->setMethods(['getStatusCode'])
        ->getMock();
        $response->method('getStatusCode')->will($this->returnValue("404"));

        $browser = $this->getMockBuilder('\GuzzleHttp\Client')->setMethods(['request'])
        ->getMock();
        $browser->method('request')->will($this->returnValue($response));

        $metric->setBrowser($browser);

        $this->assertFalse($metric->check($files)["status"]);
    }

    public function testSomeExternalLinksFails()
    {
        $metric = new Checker\NoBrokenLinksInReadmeMetric();
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
            "headlines" => array(),
            "links" => array(
                0 => array(
                    "raw" => "[External Link 1](http://www.example.com/page1)",
                    "links" => array(
                        0 => array(
                            "text" => "External Link 1",
                            "url"  => "http://www.example.com/page1"
                        )
                    )
                ),
                1 => array(
                    "raw" => "[External Link 2](http://www.example.com/page2)",
                    "links" => array(
                        0 => array(
                            "text" => "External Link 2",
                            "url"  => "http://www.example.com/page2"
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

        $request = $this->getMockBuilder('\GuzzleHttp\Psr7\Request')->disableOriginalConstructor()->getMock();
        $exception = $this->getMockBuilder('\GuzzleHttp\Exception\RequestException')->disableOriginalConstructor()->getMock();

        $browser = $this->getMockBuilder('\GuzzleHttp\Client')->setMethods(['request'])
        ->getMock();
        $browser->method('request')->willThrowException($exception);

        $metric->setBrowser($browser);

        $this->assertFalse($metric->check($files)["status"]);
    }
}
