<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class ReadmeContributingLinkExistsMetric
 */
class ReadmeContributingLinkExistsMetric extends Metric
{
    private $project;

    /**
     * Contructor function that initializes the Metric definitions
     */
    public function __construct()
    {
        $this->code       = "WOSPM0007";
        $this->title      = "LINK_TO_CONTRIBUTE";
        $this->message    = "README should have a link to CONTRIBUTING file.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0002", "WOSPM0004");
        $this->project    = new Project();
    }

    /**
     * Checks if there is a link to contributing file in readme file
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $contributing = array(
            "CONTRIBUTING", "CONTRIBUTING.md", "CONTRIBUTE", "CONTRIBUTE.md",
            "contributing", "contributing.md", "contribute", "contribute.md"
        );

        $files = array_map(
            function ($file) {
                return basename($file);
            },
            $files
        );

        $file   = array_values(array_intersect($contributing, $files))[0];
        $readme = $this->project->getReadme($files);
        $count  = substr_count($readme, $file . ')');

        if ($count !== 0) {
            $this->addVerboseDetail(
                "Contributing file is linked from README."
            );

            return $this->success();
        }
        
        $this->addVerboseDetail(
            "No link to Contributing file."
        );

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
