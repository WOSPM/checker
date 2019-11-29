<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class GithubResponsivenessMetric
 */
class GithubResponsivenessMetric extends Metric
{
    /**
     * Constructor function that initializes the Metric definitions
     *
     * @param Vendor $repo The repository object of the project
     */
    public function __construct($repo)
    {
        $this->code       = "WOSPM0021";
        $this->title      = "GITHUB_RESPONSIVENESS";
        $this->message    = "Responsive owners encourage users to be contributors.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array();
        $this->repo       = $repo;
    }

    /**
     * Checks the issues and find if all is replied in 24 hours
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $parameters = array(
            "state"    => "all",
            "per_page" => 100
        );

        $issues = $this->repo->getIssues($parameters);

        // Filter issues with author_association
        $issues = array_filter(
            $issues,
            function ($value, $key) {
                return ($value["author_association"] == 'NONE');
            },
            ARRAY_FILTER_USE_BOTH
        );

        if (count($issues) == 0) {
            return $this->success();
        }

        foreach ($issues as $issue) {
            $date1 = new \DateTime($issue["created_at"]);
            $date2 = new \DateTime($issue["updated_at"]);
            $now   = new \DateTime();

            // If the issue is not updated/responded in 24 hours
            if ($issue["created_at"] == $issue["updated_at"]) {
                $interval = $date1->diff($now);

                if ($interval->days >= 1) {
                    // TODO: we need to check if the owner of issue is not commented
                    return $this->fail();
                }
            }

            $interval = $date1->diff($date2);

            if ($interval->days >= 1) {
                // TODO: we need to check if the owner of issue is not commented
                return $this->fail();
            }
        }

        return $this->success();
    }
}
