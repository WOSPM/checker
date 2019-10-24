<?php
use WOSPM\Checker;
class ReadmeExistsMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;
    public function __construct()
    {
        $this->metric = new Checker\ReadmeExistsMetric();
    }

    public function testReadmeExists()
    {
        // Upper case 1
        $files = array(
            "README"
        );

        $this->assertTrue($this->metric->check($files)["status"]);

        // Upper case 2
        $files = array(
            "README.md"
        );

        $this->assertTrue($this->metric->check($files)["status"]);
        
        // Lower case 1
        $files = array(
            "readme"
        );

        $this->assertTrue($this->metric->check($files)["status"]);
        
        // Lower case 2
        $files = array(
            "readme.md"
        );

        $this->assertTrue($this->metric->check($files)["status"]);
        
    }
}