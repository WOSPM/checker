<?php
namespace WOSPM\Checker\Tests;

use WOSPM\Checker;
use PHPUnit\Framework\TestCase;

class ReadmeExistsMetricTest extends TestCase
{
    public function testReadmeExists()
    {
        $metric = new Checker\ReadmeExistsMetric();
        // Upper case 1
        $files = array(
            "README"
        );

        $this->assertTrue($metric->check($files)["status"]);

        // Upper case 2
        $files = array(
            "README.md"
        );

        $this->assertTrue($metric->check($files)["status"]);
        
        // Lower case 1
        $files = array(
            "readme"
        );

        $this->assertTrue($metric->check($files)["status"]);
        
        // Lower case 2
        $files = array(
            "readme.md"
        );

        $this->assertTrue($metric->check($files)["status"]);
    
    }

    public function testReadmeNotExists()
    {
        $metric = new Checker\ReadmeExistsMetric();

        $files = array(
            "NOREADME"
        );

        $this->assertFalse($metric->check($files)["status"]);
    }

}
