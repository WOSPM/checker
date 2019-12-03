<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class GithubLabelExistsMetric
 */
class GithubLabelUsedMetric extends Metric
{
    /**
     * Constructor function that initializes the Metric definitions
     *
     * @param Vendor $repo The repository object of the project
     */
    public function __construct($repo)
    {
        $this->code       = "WOSPM0016";
        $this->title      = "GITHUB_LABELS_USED";
        $this->message    = "Labels should be used to highlight the issues.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0003","WOSPM0015");
        $this->repo       = $repo;
    }

    /**
     * Checks if one of the labels are assigned to the issues
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $labels = $this->repo->getLabels();

        foreach ($labels as $label) {
            $issues = $this->repo->getLabelIssues($label["name"]);

            if (count($issues) > 0) {
                $this->addVerboseDetail(
                    "Label with name " . $label["name"] . " is used in " .
                    count($issues) . " issues"
                );
                return $this->success();
            }
        }

        $this->addVerboseDetail("No label is used in issues.");
        return $this->fail();
    }
}
