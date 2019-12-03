<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class GithubDescriptionExistsMetric
 */
class GithubDescriptionExistsMetric extends Metric
{
    /**
     * Constructor function that initializes the Metric definitions
     *
     * @param Vendor $repo The repository object of the project
     */
    public function __construct($repo)
    {
        $this->code       = "WOSPM0013";
        $this->title      = "GITHUB_SHORT_DESCRIPTION";
        $this->message    = "Project should have a short description on Github.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0003");
        $this->repo       = $repo;
    }

    /**
     * Checks if there is a description text
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $description = $this->repo->getDescription();

        if (strlen($description) === 0) {
            return $this->fail();
        }

        return $this->success();
    }
}
