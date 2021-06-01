<?php
namespace WOSPM\Checker\Tests;

use WOSPM\Checker;
use PHPUnit\Framework\TestCase;

class ParseMdTest extends TestCase
{
    public function testParseHeadline()
    {
        $parseMd = new Checker\ParseMd();
        // Upper case 1
        $lines = array(
            "## README", "", "## INTRO", "", "Intro line bla bla."
        );

        $parseMd->setContent($lines);
        $result = $parseMd->parse();
        $this->assertEquals($result["headlines"][0]["raw"], $lines[0]);
        $this->assertEquals($result["headlines"][0]["slug"], "readme");
    }

    public function testParseLink()
    {
        $parseMd = new Checker\ParseMd();

        // No link
        $line = "Line with no link.";

        $result = $parseMd->parseLinks($line);
        $this->assertEquals(0, count($result["links"]));

        // One link
        $line = "Line with 1 [link](url).";

        $result = $parseMd->parseLinks($line);
        $this->assertEquals(1, count($result["links"]));
        $this->assertEquals("link", $result["links"][0]["text"]);
        $this->assertEquals("url", $result["links"][0]["url"]);

        // One link
        $line = "Line with 3 links. 1 [link1](url1). 2 [link2](url2). 3 [link3](url3).";

        $result = $parseMd->parseLinks($line);
        $this->assertEquals(3, count($result["links"]));
        $this->assertEquals("link1", $result["links"][0]["text"]);
        $this->assertEquals("url1", $result["links"][0]["url"]);
        $this->assertEquals("link2", $result["links"][1]["text"]);
        $this->assertEquals("url2", $result["links"][1]["url"]);
        $this->assertEquals("link3", $result["links"][2]["text"]);
        $this->assertEquals("url3", $result["links"][2]["url"]);
    }

    public function testParseAsRawText()
    {
        $parseMd = new Checker\ParseMd();

        // No link
        $line = "Line with no link.";

        $result = $parseMd->parseAsRawText($line);
        $this->assertEquals($line, $result["raw"]);
        $this->assertEquals($line, $result["parsed"]);

        // One link
        $line = "Line with 1 [link](url).";

        $result = $parseMd->parseAsRawText($line);
        $this->assertEquals($line, $result["raw"]);
        $this->assertEquals("Line with 1 link.", $result["parsed"]);

        // Multiple link
        $line = "Line with [link1](url1), [link2](url2), [link3](url3).";

        $result = $parseMd->parseAsRawText($line);
        $this->assertEquals($line, $result["raw"]);
        $this->assertEquals("Line with link1, link2, link3.", $result["parsed"]);
    }

    public function testParseDashedHeadline()
    {
        $parseMd = new Checker\ParseMd();
        // Upper case 1
        $lines = array(
            "README", "------", "INTRO", "-----", "Intro line bla bla."
        );

        $parseMd->setContent($lines);
        $result = $parseMd->parse();

        $this->assertEquals($result["headlines"][0]["raw"], $lines[0]);
        $this->assertEquals($result["headlines"][0]["slug"], "readme");
        $this->assertEquals($result["headlines"][2]["raw"], $lines[2]);
        $this->assertEquals($result["headlines"][2]["slug"], "intro");
    }
}
