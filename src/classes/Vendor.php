<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class Vendor
 */
class Vendor
{
    /**
     * Repo name
     *
     * @var string|null
     */
    private $repo = null;

    /**
     * API url of the vendor
     *
     * @var string|null
     */
    private $api  = null;

    /**
     * HTTP client
     *
     * @var null
     */
    private $client = null;

    /**
     * Get the topics from vendor service
     *
     * @return array Array of topic names
     */
    public function getTopics()
    {
        return array();
    }

    /**
     * Get the description from vendor service
     *
     * @return string Short description of the repo
     */
    public function getDescription()
    {
        return array();
    }

    /**
     * Get the list of labels from vendor service
     *
     * @return array Array of topic names
     */
    public function getLabels()
    {
        return array();
    }

    /**
     * Get the list of issues from vendor service
     *
     * @param array $params The fields of issues to be used in filtering the search result
     *
     * @return array Array of topic names
     */
    public function getIssues($params)
    {
        return array();
    }

    /**
     * Get default headers
     *
     * @return Array
     */
    public function getDefaultHeaders()
    {
        return array();
    }

    /**
     * Setter of repo property
     *
     * @param string $repo The slug for the repo like "user/repo"
     */
    public function setRepo($repo)
    {
        $this->repo = $repo;
    }

    /**
     * Setter of api property
     *
     * @param string $api The url of the API
     */
    public function setAPI($api)
    {
        $this->api = $api;
    }

    /**
     * Setter of client property
     *
     * @param Client $client Client object
     */
    public function setClient($client)
    {
        $this->client = $client;
    }
}
