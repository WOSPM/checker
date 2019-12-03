<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class GithubLabelExistsMetric
 */
class GithubLabelExistsMetric extends Metric
{
    /**
     * Constructor function that initializes the Metric definitions
     *
     * @param Vendor $repo The repository object of the project
     */
    public function __construct($repo)
    {
        $this->code       = "WOSPM0015";
        $this->title      = "GITHUB_LABELS";
        $this->message    = "Project should have issue labels.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0003");
        $this->repo       = $repo;
    }

    /**
     * Checks if there is/are topic(s)
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $labels = $this->repo->getLabels();

        if (count($labels) === 0) {
            $this->addVerboseDetail("There is no label on GitHub.");
            return $this->fail();
        }

        $this->addVerboseDetail(
            "There is/are " . count($labels) . " label(s) on GitHub."
        );

        $labels = array_map(
            function ($label) { 
                return $label['name'];
            },
            $labels
        );

        $this->addVerboseDetail("Label(s); " . implode(", ", $labels));

        return $this->success();
    }
}
