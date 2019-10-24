<?php
use WOSPM\Checker;
class CocExistsMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;
    public function __construct()
    {
        $this->metric = new Checker\ContributingExistsMetric();
    }

    public function testCocExists()
    {
        // Upper case 1
        $files = array(
            "CODE_OF_CONDUCT"
        );

        $this->assertTrue($this->metric->check($files)["status"]);

        // Upper case 2
        $files = array(
            "CODE_OF_CONDUCT.md"
        );

        $this->assertTrue($this->metric->check($files)["status"]);
        
        // Lower case 1
        $files = array(
            "code_of_conduct"
        );

        $this->assertTrue($this->metric->check($files)["status"]);
        
        // Lower case 2
        $files = array(
            "code_of_conduct.md"
        );

        $this->assertTrue($this->metric->check($files)["status"]);
    
    }

    public function testCocNotExists()
    {
        $files = array(
            "NO_CODE_OF_CONDUCT"
        );

        $this->assertFalse($this->metric->check($files)["status"]);
    }

}
