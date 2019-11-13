<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class GithubVendor
 */
class GithubVendor extends Vendor
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
     * Contructore for GithubVendor class
     *
     * @param string $repo The name of the repo
     *
     * @return void
     */
    public function __construct($repo)
    {
        $this->repo = $repo;
        $this->api  = "https://api.github.com/repos/";

        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * Get the topics from vendor service
     *
     * @return array Array of topic names
     */
    public function getTopics()
    {
        $response = $this->client->request(
            'GET',
            $this->api . $this->repo . "/topics",
            array(
                "headers" => array(
                    "Accept" => "application/vnd.github.mercy-preview+json"
                )
            )
        );

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $body = json_decode($body, true);
            return $body["names"];
        }

        return array();
    }

    /**
     * Get the description from vendor service
     *
     * @return string Short description of the repo
     */
    public function getDescription()
    {
        $response = $this->client->request(
            'GET',
            $this->api . $this->repo
        );

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $body = json_decode($body, true);
            return $body["description"];
        }
        return '';
    }

    /**
     * Get the list of labels from vendor service
     *
     * @return array Array of topic names
     */
    public function getLabels()
    {
        $response = $this->client->request(
            'GET',
            $this->api . $this->repo . "/labels"
        );

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $body = json_decode($body, true);
            return $body;
        }

        return array();
    }

    /**
     * Get the array of issues of the given label
     *
     * @param string $label The name of the label
     *
     * @return array
     */
    public function getLabelIssues($label)
    {
        $response = $this->client->request(
            'GET',
            $this->api . $this->repo . "/issues?labels=" . $label . "&state=all"
        );

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $body = json_decode($body, true);
            return $body;
        }

        return array();
    }
}
