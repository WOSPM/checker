<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class GithubCustomLabelExistsMetric
 */
class GithubCustomLabelExistsMetric extends Metric
{
    /**
     * Constructor function that initializes the Metric definitions
     *
     * @param Vendor $repo The repository object of the project
     */
    public function __construct($repo)
    {
        $this->code       = "WOSPM0017";
        $this->title      = "GITHUB_CUSTOM_LABELS";
        $this->message    = "Creating custom labels is a good practice.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0015");
        $this->repo       = $repo;
    }

    /**
     * Checks if there is/are custom label(s)
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

        if (count($custom) === 0) {
            $this->addVerboseDetail("There is no custom label on GitHub.");
            return $this->fail();
        }

        $this->addVerboseDetail("There is/are " . count($custom) . " custom label(s) on GitHub.");

        $custom = array_map(
            function($label) { return $label['name']; },
            $custom
        );

        $this->addVerboseDetail("Custom label(s); " . implode(", ", $custom));

        return $this->success();
    }
}
