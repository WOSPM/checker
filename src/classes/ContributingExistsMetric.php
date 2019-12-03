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
        $this->code       = "WOSPM0004";
        $this->title      = "CONTRIBUTING";
        $this->message    = "Every open source project should " . 
        "have a CONTRIBUTING file.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array();
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
        $contributing = array(
            "CONTRIBUTING", "CONTRIBUTING.md", "CONTRIBUTE", "CONTRIBUTE.md",
            "contributing", "contributing.md", "contribute", "contribute.md"
        );

        $files = array_map(
            function ($file) {
                return basename($file);
            },
            $files
        );

        $check = (bool)array_intersect($contributing, $files);

        if ($check === true) {
            $this->addVerboseDetail("Contributing file is " . implode(",", array_intersect($contributing, $files)));
            return $this->success();
        }

        $this->addVerboseDetail("No contributing file exists under root folder or .github folder.");
        return $this->fail();
    }
}
