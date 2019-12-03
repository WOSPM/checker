<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class LicenseExistsMetric
 */
class LicenseExistsMetric extends Metric
{
    /**
     * Contructor function that initializes the Metric definitions
     *
     * @param GithubVendor $repo Repo object
     */
    public function __construct($repo)
    {
        $this->code       = "WOSPM0003";
        $this->title      = "LICENSE";
        $this->message    = "Every open source project should have a LICENSE file.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array('WOSPM0001');
        $this->repo       = $repo;
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
        $license = $this->repo->getLicense();

        if (is_array($license)) {
            $this->addVerboseDetail($license["name"] . " is used.");
            return $this->success();
        }

        $this->addVerboseDetail("No license is used.");
        return $this->fail();
    }
}
