<?php
use WOSPM\Checker;

class ProjectTest extends PHPUnit_Framework_TestCase
{
    private $project;

    public function __construct()
    {
        $this->project = new Checker\Project();
    }

    public function testGetReadmeFileName()
    {
        // Upper case 1
        $files = array(
            "README"
        );

        $this->assertEquals($files[0], $this->project->getReadmeFileName($files));

        // Upper case 2
        $files = array(
            "README.md"
        );

        $this->assertEquals($files[0], $this->project->getReadmeFileName($files));
        
        // Lower case 1
        $files = array(
            "readme"
        );

        $this->assertEquals($files[0], $this->project->getReadmeFileName($files));
        
        // Lower case 2
        $files = array(
            "readme.md"
        );

        $this->assertEquals($files[0], $this->project->getReadmeFileName($files));
    }

    public function testCreateSlug()
    {
        $str  = "Word1 word2 word3";
        $slug = Checker\Project::createSlug($str);

        $this->assertEquals("word1-word2-word3", $slug);

        $str  = "Word1";
        $slug = Checker\Project::createSlug($str);

        $this->assertEquals("word1", $slug);
    }
}
