<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class ReadmeContributingLinkExistsMetric
 */
class ReadmeContributingLinkExistsMetric extends Metric
{
    /**
     * Contructor function that initializes the Metric definitions
     */
    public function __construct()
    {
        $this->code       = "WOSPM0007";
        $this->title      = "NO_LINK_TO_CONTRIBUTE";
        $this->message    = "README should have a link to CONTRIBUTING file.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0002", "WOSPM0004");
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

        $file   = array_values(array_intersect($contributing, $files))[0];
        $readme = file_get_contents(Project::getReadmeFileName($files));
        $count  = substr_count($readme, $file);

        if ($count !== 0) {
            return $this->success();
        }

        return $this->fail();
    }
}
