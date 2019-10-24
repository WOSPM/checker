<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class ContributingExistsMetric
 */
class ContributingExistsMetric extends Metric
{
    /**
     * Contructor function that initializes the Metric definitions
     */
    public function __construct()
    {
        $this->code    = "WOSPM0004";
        $this->title   = "NO_CONTRIBUTING";
        $this->message = "Every open source project should " . 
        "have a CONTRIBUTING file.";
        $this->type    = MetricType::ERROR;
    }

    /**
     * Checks if there is a contributing file in root directory
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $check = in_array("CONTRIBUTING", $files) ||
            in_array("CONTRIBUTING.md", $files) ||
            in_array("CONTRIBUTE", $files) ||
            in_array("CONTRIBUTE.md", $files);

        if ($check === true) {
            return $this->success();
        }

        return $this->fail();
    }
}
