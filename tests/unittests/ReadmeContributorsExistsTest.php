<?php
use WOSPM\Checker;

class ReadmeContributorsExistsTest extends PHPUnit_Framework_TestCase
{
    public function testContributorsNotExists()
    {
        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getContributors'])
        ->getMock();

        $repo->method('getContributors')->will(
            $this->returnValue(
                array(
                    array('login' => 'user1')
                )
            )
        );

        $metric = new Checker\ReadmeContributorsExistsMetric($repo);

        $this->assertTrue($metric->check($files)["status"]);
    }

    public function testContributorsExists()
    {

        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getContributors'])
        ->getMock();

        $repo->method('getContributors')->will(
            $this->returnValue(
                array(
                    array('login' => 'user1'),
                    array('login' => 'user2')
                )
            )
        );

        $metric = new Checker\ReadmeContributorsExistsMetric($repo);
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
                    "raw"    => "## Local Contributors",
                    "parsed" => "Local Contributors",
                    "slug"   => "local-Contributors",
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
                    "raw" => "- [Local Contributors](#local-Contributors)",
                    "links" => array(
                        0 => array(
                            "text" => "Local Contributors",
                            "url"  => "#local-Contributors"
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

    public function testContributorsNotExists()
    {
        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getContributors'])
        ->getMock();

        $repo->method('getContributors')->will(
            $this->returnValue(
                array(
                    array('login' => 'user1'),
                    array('login' => 'user2')
                )
            )
        );

        $metric = new Checker\ReadmeContributorsExistsMetric($repo);
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

        $metric->setParser($parser);


        $this->assertFalse($metric->check($files)["status"]);
    }

    public function testContributorsLinkExists()
    {
        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['getContributors'])
        ->getMock();

        $repo->method('getContributors')->will(
            $this->returnValue(
                array(
                    array('login' => 'user1'),
                    array('login' => 'user2')
                )
            )
        );
        
        $metric = new Checker\ReadmeContributorsExistsMetric($repo);
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
                    "raw" => "[Contributors](https://doc.repo.io/doc)",
                    "links" => array(
                        0 => array(
                            "text" => "Contributors",
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
