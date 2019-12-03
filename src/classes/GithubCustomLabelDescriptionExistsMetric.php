<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class GithubCustomLabelDescriptionExistsMetric
 */
class GithubCustomLabelDescriptionExistsMetric extends Metric
{
    /**
     * Constructor function that initializes the Metric definitions
     *
     * @param Vendor $repo The repository object of the project
     */
    public function __construct($repo)
    {
        $this->code       = "WOSPM0022";
        $this->title      = "GITHUB_CUSTOM_LABEL_DESCRIPTON";
        $this->message    = "Custom labels should have descriptions.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0003", "WOSPM0015", "WOSPM0017");
        $this->repo       = $repo;
    }

    /**
     * Checks if all labels has description
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $labels = $this->repo->getLabels();

        $hasNoDescription = array_filter(
            $labels,
            function ($label) {
                return (trim($label['description']) === "");
            }
        );

        if (count($hasNoDescription) === 0) {
            return $this->success();
        }

        return $this->fail();
    }
}
