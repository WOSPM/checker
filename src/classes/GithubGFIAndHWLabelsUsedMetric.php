<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class GithubGFIAndHWLabelsUsedMetric
 */
class GithubGFIAndHWLabelsUsedMetric extends Metric
{
    /**
     * Constructor function that initializes the Metric definitions
     *
     * @param Vendor $repo The repository object of the project
     */
    public function __construct($repo)
    {
        $this->code       = "WOSPM0020";
        $this->title      = "GITHUB_LABELS_GFI_HW_USED";
        $this->message    = '_good first issue_ and _help wanted_ ' .
        'labels should be used.';
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0003", "WOSPM0018");
        $this->repo       = $repo;
    }

    /**
     * Checks if the two labels exist in label list
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $issues = $this->repo->getLabelIssues("good first issue");

        if (count($issues) > 0) {
            $this->addVerboseDetail(
                "Label with name good first issue is used in " .
                count($issues) . " issues"
            );
            return $this->success();
        }

        $issues = $this->repo->getLabelIssues("help wanted");

        if (count($issues) > 0) {
            $this->addVerboseDetail(
                "Label with name help wanted is used in " .
                count($issues) . " issues"
            );
            return $this->success();
        }

        $this->addVerboseDetail(
            "good first issue or help wanted labels are " .
            "not used in issues."
        );

        return $this->fail();
    }
}
