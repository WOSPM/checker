<?php
use WOSPM\Checker;

require_once "." . DIRECTORY_SEPARATOR .
    "src" . DIRECTORY_SEPARATOR . "functions" . DIRECTORY_SEPARATOR .
    "functions.php";

class FunctionsTest extends PHPUnit_Framework_TestCase
{
    public function testStatusEmptyTrue()
    {
        $array = array();

        $this->assertTrue(status($array));
    }

    public function testStatusTrue()
    {
        $array = array(
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
        );

        $this->assertTrue(status($array));
    }

    public function testStatusFalse()
    {
        $array = array(
            array("status" => false),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
        );

        $this->assertFalse(status($array));
    }

    public function testPercent()
    {
        $array = array(
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
        );

        $this->assertEquals(100, percent($array));

        $array = array(
            array("status" => false),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
        );

        $this->assertEquals(90, percent($array));

        $array = array(
            array("status" => false),
            array("status" => false),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
            array("status" => true),
        );

        $this->assertEquals(80, percent($array));
        $array = array(
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => true),
            array("status" => true),
        );

        $this->assertEquals(20, percent($array));

        $array = array(
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => true),
        );

        $this->assertEquals(10, percent($array));

        $array = array(
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
            array("status" => false),
        );

        $this->assertEquals(0, percent($array));
    }

    public function testBadge()
    {
        $this->assertEquals("![Perfect](https://img.shields.io/badge/WOSPM-Perfect-blue)", badge(100));
        $this->assertEquals("![Welcoming](https://img.shields.io/badge/WOSPM-Welcoming-green)", badge(95));
        $this->assertEquals("![Welcoming](https://img.shields.io/badge/WOSPM-Welcoming-green)", badge(90));
        $this->assertEquals("![Bad](https://img.shields.io/badge/WOSPM-Bad-red)", badge(40));
        $this->assertEquals("![Bad](https://img.shields.io/badge/WOSPM-Bad-red)", badge(30));
        $this->assertEquals("![Bad](https://img.shields.io/badge/WOSPM-Bad-red)", badge(0));
        $this->assertEquals("![Not Ready](https://img.shields.io/badge/WOSPM-Not--Ready-orange)", badge(80));
        $this->assertEquals("![Not Ready](https://img.shields.io/badge/WOSPM-Not--Ready-orange)", badge(60));
        $this->assertEquals("![Not Ready](https://img.shields.io/badge/WOSPM-Not--Ready-orange)", badge(50));
    }

    public function testProcessor()
    {
        $repo = $this->getMockBuilder('Checker\GithubVendor')->setMethods(['setAToken', 'setMethods'])->getMock();
        $repo->method('setAToken')->will($this->returnValue(null));

        $arguments = $this->getMockBuilder('Checker\Arguments')->getMock();
        $arguments->verbose = 'NO';
        //define('PROJECT_FOLDER', '/tmp/');
        $processor = processor($arguments, $repo);

        $this->assertInstanceOf('WOSPM\Checker\Processor', $processor);

        $metrics = $processor->getMetrics();

        $this->assertTrue(is_array($metrics));

        foreach ($metrics as $key => $metric) {
            $this->assertInstanceOf('WOSPM\Checker\Metric', $metric);
        }
    }
}
