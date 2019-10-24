<?php
use WOSPM\Checker;
class ContributingExistsMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;
    public function __construct()
    {
        $this->metric = new Checker\ContributingExistsMetric();
    }

    public function testContributingExists()
    {
        // Upper case 1
        $files = array(
            "CONTRIBUTING"
        );

        $this->assertTrue($this->metric->check($files)["status"]);

        // Upper case 2
        $files = array(
            "CONTRIBUTING.md"
        );

        $this->assertTrue($this->metric->check($files)["status"]);
        
        // Lower case 1
        $files = array(
            "CONTRIBUTE"
        );

        $this->assertTrue($this->metric->check($files)["status"]);
        
        // Lower case 2
        $files = array(
            "CONTRIBUTE.md"
        );

        $this->assertTrue($this->metric->check($files)["status"]);
    
    }

    public function testContributingNotExists()
    {
        $files = array(
            "NOCONTRIBUTE"
        );

        $this->assertFalse($this->metric->check($files)["status"]);
    }

}
