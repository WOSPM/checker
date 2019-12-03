<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class GithubCustomLabelUsedMetric
 */
class GithubCustomLabelUsedMetric extends Metric
{
    /**
     * Constructor function that initializes the Metric definitions
     *
     * @param Vendor $repo The repository object of the project
     */
    public function __construct($repo)
    {
        $this->code       = "WOSPM0019";
        $this->title      = "GITHUB_CUSTOM_LABELS_USED";
        $this->message    = "At least one custom label should be associated" .
        " to an issue.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0003", "WOSPM0017");
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

        $custom = array_filter(
            $labels,
            function ($label) {
                return ($label['default'] === false);
            }
        );

        foreach ($custom as $label) {
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
