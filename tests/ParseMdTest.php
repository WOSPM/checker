<?php
use WOSPM\Checker;

class ParseMdTest extends PHPUnit_Framework_TestCase
{
    private $project;

    public function __construct()
    {
        $this->parseMd = new Checker\ParseMd();
    }

    public function testGetReadmeFileName()
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
}
