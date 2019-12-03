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
        $this->code       = "WOSPM0005";
        $this->title      = "CODE_OF_CONDUCT";
        $this->message    = "Every open source project should " . 
        "have a CODE_OF_CONDUCT file.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array();
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
        $cocs = array(
            "CODE_OF_CONDUCT",
            "CODE_OF_CONDUCT.md",
            "code_of_conduct",
            "code_of_conduct.md"
        );

        $files = array_map(
            function ($file) {
                return basename($file);
            },
            $files
        );

        $check = (bool)array_intersect($cocs, $files);

        if ($check) {
            $this->addVerboseDetail(
                "Code Of Conduct file is " .
                implode(",", array_intersect($cocs, $files))
            );

            return $this->success();
        }

        $this->addVerboseDetail(
            "No Code Of Conduct file exists under root or .github folder."
        );

        return $this->fail();
    }
}
