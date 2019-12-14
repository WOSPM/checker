<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class ReadmeAdequateMetric
 */
class ReadmeAdequateMetric extends Metric
{
    private $project;

    /**
     * Contructor function that initializes the Metric definitions
     */
    public function __construct()
    {
        $this->code       = "WOSPM0009";
        $this->title      = "README_ADEQUATE";
        $this->message    = "README should have atleast 200 words.";
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
        $readme = $this->project->getReadme($files);
        $readme = $this->parser->parseAsRawText($readme);
        $readme = $readme["parsed"];
        $readme = explode(" ", $readme);
        $readme = array_filter(
            $readme,
            function ($x) {
                return strlen($x) > 2;
            }
        );

        $count  = count($readme);

        $this->addVerboseDetail(
            "There are $count word(s) in README."
        );

        if ($count > 200) {
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
