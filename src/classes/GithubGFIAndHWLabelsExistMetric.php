<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class GithubGFIAndHWLabelsExistMetric
 */
class GithubGFIAndHWLabelsExistMetric extends Metric
{
    /**
     * Constructor function that initializes the Metric definitions
     *
     * @param Vendor $repo The repository object of the project
     */
    public function __construct($repo)
    {
        $this->code       = "WOSPM0018";
        $this->title      = "GITHUB_LABELS_GFI_HW";
        $this->message    = '"good first issue" and "help wanted" labels exist.';
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0015");
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
        $labels = $this->repo->getLabels();

        $gfi = array_filter(
            $labels,
            function ($label)
            {
                return ($label['name'] === 'good first issue');
            }
        );

        if (count($gfi) === 0) {
            return $this->fail();
        }

        $hw = array_filter(
            $labels,
            function ($label)
            {
                return ($label['name'] === 'help wanted');
            }
        );

        if (count($hw) === 0) {
            return $this->fail();
        }

        return $this->success();
    }
}
