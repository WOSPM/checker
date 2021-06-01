<?php
namespace WOSPM\Checker\Tests;

use WOSPM\Checker;
use PHPUnit\Framework\TestCase;

class ContributingExistsMetricTest extends TestCase
{
    public function testContributingExists()
    {
        $metric = new Checker\ContributingExistsMetric();
        // Upper case 1
        $files = array(
            "CONTRIBUTING"
        );

        $this->assertTrue($metric->check($files)["status"]);

        // Upper case 2
        $files = array(
            "CONTRIBUTING.md"
        );

        $this->assertTrue($metric->check($files)["status"]);
        
        // Lower case 1
        $files = array(
            "CONTRIBUTE"
        );

        $this->assertTrue($metric->check($files)["status"]);
        
        // Lower case 2
        $files = array(
            "CONTRIBUTE.md"
        );

        $this->assertTrue($metric->check($files)["status"]);
    
    }

    public function testContributingNotExists()
    {
        $metric = new Checker\ContributingExistsMetric();

        $files = array(
            "NOCONTRIBUTE"
        );

        $this->assertFalse($metric->check($files)["status"]);
    }

}
