<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class GithubTopicExistsMetric
 */
class GithubTopicExistsMetric extends Metric
{
    /**
     * Constructor function that initializes the Metric definitions
     *
     * @param Vendor $repo The repository object of the project
     */
    public function __construct($repo)
    {
        $this->code    = "WOSPM0014";
        $this->title   = "GITHUB_TOPICS";
        $this->message = "Related Github topics should be added to the repository.";
        $this->type    = MetricType::ERROR;

        $this->dependency = array("WOSPM0003");
        $this->repo       = $repo;
    }

    /**
     * Checks if there is/are topic(s)
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $topics = $this->repo->getTopics();

        $this->addVerboseDetail(
            "The topic(s) is/are " . implode(", ", $topics) . "."
        );

        if (count($topics) === 0) {
            return $this->fail();
        }

        return $this->success();
    }
}
