<?php
use WOSPM\Checker;
class CocExistsMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;
    public function __construct()
    {
        $this->metric = new Checker\CocExistsMetric();
    }

    public function testCocExists()
    {
        $metric = new Checker\CocExistsMetric();
        // Upper case 1
        $files = array(
            "CODE_OF_CONDUCT"
        );

        $this->assertTrue($metric->check($files)["status"]);

        // Upper case 2
        $files = array(
            "CODE_OF_CONDUCT.md"
        );

        $this->assertTrue($metric->check($files)["status"]);
        
        // Lower case 1
        $files = array(
            "code_of_conduct"
        );

        $this->assertTrue($metric->check($files)["status"]);
        
        // Lower case 2
        $files = array(
            "code_of_conduct.md"
        );

        $this->assertTrue($metric->check($files)["status"]);
    
    }

    public function testCocNotExists()
    {
        $metric = new Checker\CocExistsMetric();
        $files = array(
            "NO_CODE_OF_CONDUCT"
        );

        $this->assertFalse($metric->check($files)["status"]);
    }

}
