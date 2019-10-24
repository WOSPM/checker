<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class CocExistsMetric
 */
class CocExistsMetric extends Metric
{
    /**
     * Contructor function that initializes the Metric definitions
     */
    public function __construct()
    {
        $this->code    = "WOSPM0004";
        $this->title   = "NO_CODE_OF_CONDUCT";
        $this->message = "Every open source project should " . 
        "have a CODE_OF_CONDUCT file.";
        $this->type    = MetricType::ERROR;
    }

    /**
     * Checks if there is a code of conduct file in root directory
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $check = in_array("CODE_OF_CONDUCT", $files) ||
            in_array("CODE_OF_CONDUCT.md", $files) ||
            in_array("code_of_conduct", $files) ||
            in_array("code_of_conduct.md", $files);

        if ($check === true) {
            return $this->success();
        }

        return $this->fail();
    }
}
