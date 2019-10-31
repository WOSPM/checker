<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class LicenseExistsMetric
 */
class LicenseExistsMetric extends Metric
{
    /**
     * Contructor function that initializes the Metric definitions
     */
    public function __construct()
    {
        $this->code       = "WOSPM0003";
        $this->title      = "LICENSE";
        $this->message    = "Every open source project should have a LICENSE file.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array('WOSPM0002');
    }

    /**
     * Checks if there is a license file in root directory
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $check = in_array("LICENSE", $files);

        if ($check === true) {
            return $this->success();
        }

        return $this->fail();
    }
}
