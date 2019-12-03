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
        $this->code       = "WOSPM0002";
        $this->title      = "README";
        $this->message    = "Every open source project should have a README file.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array();
        $this->verboseDetail = array();
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
        $readme = array(
            "README", "README.md", "readme", "readme.md"
        );

        $check = (bool)array_intersect($readme, $files);

        if ($check === true) {
            $this->addVerboseDetail(
                "Readme file is " . implode(",", array_intersect($readme, $files))
            );
            
            return $this->success();
        }
        
        $this->addVerboseDetail("No readme file exists under root folder.");
        return $this->fail();
    }
}
