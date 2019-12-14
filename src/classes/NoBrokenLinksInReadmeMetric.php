<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class ReadmeTocExistsMetric
 */
class NoBrokenLinksInReadmeMetric extends Metric
{
    private $project;

    /**
     * Contructor function that initializes the Metric definitions
     */
    public function __construct()
    {
        $this->code       = "WOSPM0023";
        $this->title      = "NO_BROKEN_LINKS_IN_README";
        $this->message    = "No broken links exists in README.";
        $this->type       = MetricType::ERROR;
        $this->dependency = array("WOSPM0002");
        $this->project    = new Project();
        $this->parser     = new ParseMd();
        $this->browser    = new \GuzzleHttp\Client();
    }

    /**
     * Checks if there is a link to code of conduct file in readme file
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

        if ($this->checkLinks($parsed)) {
            return $this->success();
        }

        return $this->fail();
    }

    /**
     * Check the list of heading if all of them is linked
     *
     * @param array $parsed The array creating by parsing the MD document
     *
     * @return boolean
     */
    private function checkLinks($parsed)
    {
        $slugs   = array();

        // Collect the headline slugs
        foreach ($parsed["headlines"] as $ln => $headline) {
            $slugs['#' . $headline["slug"]] = '#' . $headline["slug"];
        }

        $return = true;
        $links  = array();
        // Collect the links urls
        foreach ($parsed["links"] as $ln => $line) {
            foreach ($line["links"] as $key => $link) {
                $this->addVerboseDetail(
                    "Checking link with target " . $link["url"]
                );
                if ($link["url"][0] === '#') {
                    if (!isset($slugs[$link["url"]])) {
                        $this->addVerboseDetail(
                            "\t Link is broken."
                        );
                        $return = false;
                        continue;
                    }
                } elseif (filter_var($link["url"], FILTER_VALIDATE_URL)) {
                    try {
                        $response = $this->browser->request(
                            'GET',
                            $link["url"]
                        );
                    } catch (\GuzzleHttp\Exception\RequestException $e) {
                        $this->addVerboseDetail(
                            "\t Link is broken."
                        );
                        $return = false;
                        continue;
                    } catch (Exception $e) {
                        $this->addVerboseDetail(
                            "\t Link is broken."
                        );
                        $return = false;
                        continue;
                    }

                    if ($response->getStatusCode() == "404") {
                        $this->addVerboseDetail(
                            "\t Link is broken."
                        );
                        $return = false;
                        continue;
                    }
                } else {
                    // It is not an anchor or a url, so it is file
                    // Clear the anchor text with  file name
                    // like CONTRIBUTING.md#add-new-metric
                    $file = $link["url"];
                    $file = explode('#', $link["url"]);
                    $file = $file[0];

                    if (!file_exists($file)) {
                        $return = false;
                        continue;
                    }
                }

                $links[] = $link["url"];
            }
        }

        $this->addVerboseDetail(
            "There is/are " . count($links) . " link(s) in README."
        );

        return $return;
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

    /**
     * Setter for the browser property
     *
     * @param GuzzleHttp\Client $browser Parse object
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;
    }
}
