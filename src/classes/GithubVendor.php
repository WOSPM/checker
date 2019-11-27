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
     * Personal access token got from GitHub
     *
     * @var string|null
     */
    private $atoken = "";

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
                    "Accept"        => "application/vnd.github.mercy-preview+json",
                    "Authorization" => 'token ' . $this->atoken
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
            $this->api . $this->repo,
            array(
                "headers" => array(
                    "Authorization" => 'token ' . $this->atoken
                )
            )
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
            $this->api . $this->repo . "/labels",
            array(
                "headers" => array(
                    "Authorization" => 'token ' . $this->atoken
                )
            )
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
            $this->api . $this->repo . "/issues?labels=" . $label . "&state=all",
            array(
                "headers" => array(
                    "Authorization" => 'token ' . $this->atoken
                )
            )
        );

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $body = json_decode($body, true);
            return $body;
        }

        return array();
    }

    /**
     * Getter for repo propoerty
     *
     * @return string
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * Setter for atoken property
     *
     * @param string $token Token string got from Github
     */
    public function setAToken($token)
    {
        $this->atoken = $token;
    }
}
