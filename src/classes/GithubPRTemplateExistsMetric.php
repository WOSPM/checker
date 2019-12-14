<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class GithubPRTemplateExistsMetric
 */
class GithubPRTemplateExistsMetric extends Metric
{
    /**
     * Contructor function that initializes the Metric definitions
     */
    public function __construct()
    {
        $this->code       = "WOSPM0012";
        $this->title      = "GITHUB_PR_TEMPLATE";
        $this->message    = "You should have PR template on Github.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0003");
    }

    /**
     * Checks if there are files under .github/PULL_REQUEST_TEMPLATE folder.
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $templates = array_filter(
            $files,
            function ($value) {
                return (stripos($value, 'PULL_REQUEST_TEMPLATE') !== false);
            }
        );

        $this->addVerboseDetail(
            "The pull request templates for GitHub are " . implode(", ", $templates)
        );

        if (count($templates) > 0) {
            return $this->success();
        }

        return $this->fail();
    }
}
