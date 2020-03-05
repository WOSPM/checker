<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class ChangelogExistsMetric
 */
class ChangelogExistsMetric extends Metric
{
    private $project;

    /**
     * Contructor function that initializes the Metric definitions
     *
     * @param Vendor $repo The repository object of the project
     */
    public function __construct($repo)
    {
        $this->code       = "WOSPM0025";
        $this->title      = "CHANGELOG";
        $this->message    = "Project should have a changelog published.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array();
        $this->project    = new Project();
        $this->parser     = new ParseMd();
        $this->repo       = $repo;
    }

    /**
     * Checks if there is a changlog section/link in readme file or
     * there is a changlog file.
     * 
     * @param array $files Array of the files in root directory
     *
     * @return array
     */
    public function check($files)
    {
        $readme = $this->project->getReadmeFileName($files);
        $this->parser->setFile(PROJECT_FOLDER . $readme);

        $parsed = $this->parser->parse();

        if ($this->checkChangelog($parsed, $files)) {
            return $this->success();
        }

        return $this->fail();
    }

    /**
     * Check if there is an changelog section
     *
     * @param array $parsed The array creating by parsing the MD document
     * @param array $files  Array of the files in root directory
     *
     * @return boolean
     */
    private function checkChangelog($parsed, $files)
    {
        $cocs = array(
            "CHANGELOG",
            "CHANGELOG.md",
            "changelog",
            "changelog.md",
            "HISTORY",
            "HISTORY.md",
            "history",
            "history.md",
            "NEWS",
            "NEWS.md",
            "news",
            "news.md",
            "RELEASES",
            "RELEASES.md",
            "releases",
            "releases.md"
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
                "Changelog file is " .
                implode(",", array_intersect($cocs, $files))
            );

            return true;
        }

        // Traverse the headlines
        foreach ($parsed["headlines"] as $ln => $headline) {
            if (strpos(strtolower($headline["parsed"]), 'changelog') !== false) {
                return true;
            }
        }

        // Traverse  the links texts
        foreach ($parsed["links"] as $ln => $line) {
            foreach ($line["links"] as $key => $link) {
                if (strpos(strtolower($link["text"]), 'changelog') !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Setter for the project property
     *
     * @param Project $project Project object
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * Setter for the parser property
     *
     * @param Project $parser Parse object
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
    }
}
