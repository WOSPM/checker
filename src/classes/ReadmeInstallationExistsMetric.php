<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class ReadmeInstallationExistsMetric
 */
class ReadmeInstallationExistsMetric extends Metric
{
    private $project;

    /**
     * Contructor function that initializes the Metric definitions
     */
    public function __construct()
    {
        $this->code       = "WOSPM0010";
        $this->title      = "INSTALLATION_SECTION";
        $this->message    = "README file should have an installation section.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0002");
        $this->project    = new Project();
        $this->parser     = new ParseMd();
    }

    /**
     * Checks if there is a install section in readme file
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

        if ($this->checkInstall($parsed)) {
            return $this->success();
        }

        return $this->fail();
    }

    /**
     * Check if there is an install section
     *
     * @param array $parsed The array creating by parsing the MD document
     *
     * @return boolean
     */
    private function checkInstall($parsed)
    {
        // Traverse the headlines
        foreach ($parsed["headlines"] as $ln => $headline) {
            if (strpos(strtolower($headline["parsed"]), 'install') !== false) {
                return true;
            }
        }

        // Traverse  the links texts
        foreach ($parsed["links"] as $ln => $line) {
            foreach ($line["links"] as $key => $link) {
                if (strpos(strtolower($link["text"]), 'install') !== false) {
                    return true;
                }
            }
        }

        return false;
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

    /**
     * Setter for the parser property
     *
     * @param Project $parser Parse object
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
    }
}
