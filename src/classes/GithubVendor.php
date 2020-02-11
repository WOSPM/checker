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
     * Get default headers
     *
     * @return Array
     */
    public function getDefaultHeaders()
    {
        $return = array();

        if ($this->atoken !== "") {
            $return["Authorization"] = 'token ' . $this->atoken;
        }

        return $return;
    }

    /**
     * Get the topics from vendor service
     *
     * @return array Array of topic names
     */
    public function getTopics()
    {
        $headers = $this->getDefaultHeaders();

        // Accecpt header is needed to fetch the topics
        $headers["Accept"] = "application/vnd.github.mercy-preview+json";

        $response = $this->client->request(
            'GET',
            $this->api . $this->repo . "/topics",
            array(
                "headers" => $headers
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
        $headers  = $this->getDefaultHeaders();
        $response = $this->client->request(
            'GET',
            $this->api . $this->repo,
            array(
                "headers" => $headers
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
     * Get the license from vendor service
     *
     * @return string License name of the repo
     */
    public function getLicense()
    {
        $headers  = $this->getDefaultHeaders();
        $response = $this->client->request(
            'GET',
            $this->api . $this->repo,
            array(
                "headers" => $headers
            )
        );

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $body = json_decode($body, true);
            return $body["license"];
        }
        return null;
    }

    /**
     * Get the list of labels from vendor service
     *
     * @return array Array of topic names
     */
    public function getLabels()
    {
        $headers  = $this->getDefaultHeaders();
        $response = $this->client->request(
            'GET',
            $this->api . $this->repo . "/labels",
            array(
                "headers" => $headers
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
        $parameters = array(
            "labels" => $label,
            "state"  => "all"
        );

        return $this->getIssues($parameters);
    }

    /**
     * Get the list of issues of repo
     *
     * @param array $params The fields to be used in filtering the search result
     *
     * @return array Array of topic names
     */
    public function getIssues($params = array())
    {
        if (!is_array($params)) {
            return array();
        }

        $headers = $this->getDefaultHeaders();
        $filters = "";

        foreach ($params as $key => $value) {
            $filters .= "$key=$value" . "&";
        }

        if ($filters !== "") {
            $filters = "?" . trim($filters, "&");
        }

        $response = $this->client->request(
            'GET',
            $this->api . $this->repo . "/issues" . $filters,
            array(
                "headers" => $headers
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
     * Get the list of contributors
     *
     * @return array Array of contributors
     */
    public function getContributors()
    {
        $headers  = $this->getDefaultHeaders();
        $response = $this->client->request(
            'GET',
            $this->api . $this->repo . "/contributors",
            array(
                "headers" => $headers
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
