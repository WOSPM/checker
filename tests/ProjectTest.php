<?php
use WOSPM\Checker;
class ProjectTest extends PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        
    }

    public function testGetReadmeFileName()
    {
        // Upper case 1
        $files = array(
            "README"
        );

        $this->assertEquals($files[0], Checker\Project::getReadmeFileName($files));

        // Upper case 2
        $files = array(
            "README.md"
        );

        $this->assertEquals($files[0], Checker\Project::getReadmeFileName($files));
        
        // Lower case 1
        $files = array(
            "readme"
        );

        $this->assertEquals($files[0], Checker\Project::getReadmeFileName($files));
        
        // Lower case 2
        $files = array(
            "readme.md"
        );

        $this->assertEquals($files[0], Checker\Project::getReadmeFileName($files));
    
    }
}
