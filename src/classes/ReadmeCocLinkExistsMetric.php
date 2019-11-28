<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class ReadmeCocLinkExistsMetric
 */
class ReadmeCocLinkExistsMetric extends Metric
{
    private $project;

    /**
     * Contructor function that initializes the Metric definitions
     */
    public function __construct()
    {
        $this->code       = "WOSPM0008";
        $this->title      = "LINK_TO_CODE_OF_CONDUCT";
        $this->message    = "README should have a link to CODE_OF_CONDUCT.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0002", "WOSPM0005");
        $this->project    = new Project();
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
        $coc = array(
            "CODE_OF_CONDUCT", "CODE_OF_CONDUCT.md",
            "code_of_conduct", "code_of_conduct.md"
        );

        $files = array_map(
            function ($file) {
                return basename($file);
            },
            $files
        );

        $file   = array_values(array_intersect($coc, $files))[0];
        $readme = $this->project->getReadme($files);
        $count  = substr_count($readme, $file . ')');

        if ($count !== 0) {
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
