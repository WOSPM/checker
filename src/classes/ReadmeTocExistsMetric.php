<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class ReadmeTocExistsMetric
 */
class ReadmeTocExistsMetric extends Metric
{
    private $project;

    /**
     * Contructor function that initializes the Metric definitions
     */
    public function __construct()
    {
        $this->code       = "WOSPM0006";
        $this->title      = "NO_README_TOC";
        $this->message    = "README file should have a ToC.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0002");
        $this->project    = new Project();
        $this->parser     = new ParseMd();
    }

    /**
     * Checks if there is a link to code of conduct file in readme file
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $readme = $this->project->getReadmeFileName($files);
        $this->parser->setFile(PROJECT_FOLDER . $readme);

        $parsed = $this->parser->parse();

        if (count($parsed) !== 0) {
            return $this->success();
        }

        return $this->fail();
    }

    /**
     * Setter for the project property
     *
     * @param Project $project Project object
     */
    public function setProject($project)
    {
        $this->project = $project;
    }
}
