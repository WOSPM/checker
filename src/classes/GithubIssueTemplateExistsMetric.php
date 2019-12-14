<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class GithubIssueTemplateExistsMetric
 */
class GithubIssueTemplateExistsMetric extends Metric
{
    /**
     * Contructor function that initializes the Metric definitions
     */
    public function __construct()
    {
        $this->code       = "WOSPM0011";
        $this->title      = "GITHUB_ISSUE_TEMPLATE";
        $this->message    = "You should have issue templates on Github.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0003");
    }

    /**
     * Checks if there are files under .github/ISSUE_TEMPLATE folder.
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
                return (
                    (stripos($value, 'ISSUE_TEMPLATE') !== false) &&
                    (stripos($value, '.md') !== false)
                );
            }
        );

        $this->addVerboseDetail(
            "The issue templates for GitHub are " . implode(", ", $templates)
        );

        if (count($templates) >= 1) {
            return $this->success();
        }

        return $this->fail();
    }
}
