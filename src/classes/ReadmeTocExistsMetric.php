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
        $this->title      = "README_TOC";
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


        if ($this->checkHeadings($parsed)) {
            return $this->success();
        }

        return $this->fail();
    }

    /**
     * Check the list of heading if all of them is linked
     *
     * @param array $parsed The array creating by parsing the MD document
     *
     * @return boolean
     */
    private function checkHeadings($parsed)
    {
        $slugs = array();

        // Collect the headline slugs
        foreach ($parsed["headlines"] as $ln => $headline) {
            $slugs[] = '#' . $headline["slug"];
        }

        $links = array();

        // Collect the links urls
        foreach ($parsed["links"] as $ln => $line) {
            foreach ($line["links"] as $key => $link) {
                if ($link["url"][0] !== '#') {
                    continue;
                }

                $links[] = $link["url"];
            }
        }

        $this->addVerboseDetail(
            "There is/are " . count($slugs) . " headline(s)."
        );

        $this->addVerboseDetail(
            "There is/are " . count($links) . " link(s)."
        );
        
        // First headline and the headline of the ToC may not be in the links.
        if ((count($slugs) - 2) <= count($links)) {
            return true;
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
