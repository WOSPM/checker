<?php
use WOSPM\Checker;

class ParseMdTest extends PHPUnit_Framework_TestCase
{
    private $project;

    public function __construct()
    {
        $this->parseMd = new Checker\ParseMd();
    }

    public function testParseHeadline()
    {
        // Upper case 1
        $lines = array(
            "## README", "", "## INTRO", "", "Intro line bla bla."
        );

        $this->parseMd->setContent($lines);
        $result = $this->parseMd->parse();
        $this->assertEquals($result["headlines"][0]["raw"], $lines[0]);
        $this->assertEquals($result["headlines"][0]["slug"], "readme");
    }

    public function testParseLink()
    {
        // No link
        $line = "Line with no link.";

        $result = $this->parseMd->parseLinks($line);
        $this->assertEquals(0, count($result["links"]));

        // One link
        $line = "Line with 1 [link](url).";

        $result = $this->parseMd->parseLinks($line);
        $this->assertEquals(1, count($result["links"]));
        $this->assertEquals("link", $result["links"][0]["text"]);
        $this->assertEquals("url", $result["links"][0]["url"]);

        // One link
        $line = "Line with 3 links. 1 [link1](url1). 2 [link2](url2). 3 [link3](url3).";

        $result = $this->parseMd->parseLinks($line);
        $this->assertEquals(3, count($result["links"]));
        $this->assertEquals("link1", $result["links"][0]["text"]);
        $this->assertEquals("url1", $result["links"][0]["url"]);
        $this->assertEquals("link2", $result["links"][1]["text"]);
        $this->assertEquals("url2", $result["links"][1]["url"]);
        $this->assertEquals("link3", $result["links"][2]["text"]);
        $this->assertEquals("url3", $result["links"][2]["url"]);
    }
}
