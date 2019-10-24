<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class ReadmeExistsMetric
 */
class ReadmeExistsMetric extends Metric
{
    /**
     * Contructor function that initializes the Metric definitions
     */
    public function __construct()
    {
        $this->code    = "WOSPM0002";
        $this->title   = "README file shoud be created";
        $this->message = "Every open source project should have a README file.";
        $this->type    = MetricType::ERROR;
    }

    /**
     * Checks if there is a readme file in root directory
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $check = in_array("README", $files) ||
            in_array("README.md", $files) ||
            in_array("readme", $files) ||
            in_array("readme.md", $files);

        if ($check === true) {
            return $this->success();
        }

        return $this->fail();
    }
}