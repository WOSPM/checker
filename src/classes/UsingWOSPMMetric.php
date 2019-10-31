<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class UsingWOSPMMetric
 */
class UsingWOSPMMetric extends Metric
{
    /**
     * Contructor function that initializes the Metric definitions
     */
    public function __construct()
    {
        $this->code       = "WOSPM0001";
        $this->title      = "USING_WOSPM";
        $this->message    = "You are using WOSPM checker. It is a good start.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array();
    }

    /**
     * Always returns true
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        return $this->success();
    }
}
