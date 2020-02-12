<?php
namespace WOSPM\Checker;

/**
 * Class for getting the projects git info
 */
class ProjectRepository extends \Cz\Git\GitRepository
{
    /**
     * Get information of project git remote
     *
     * @return array
     */
    public function getRemoteOrigin()
    {
        $remote = $this->extractFromCommand(
            'git config --get remote.origin.url',
            'trim'
        );

        //$remote[0] = "https://github.com/WOSPM/checker-github-action";

        if (substr($remote[0], 0, 3) == "git") {
            $remote[0] = str_replace('.git', '', $remote[0]);
            $output    = explode('@', str_replace(':', '@', $remote[0]));

            return array(
                "vendor" => $output[1],
                "repo"   => $output[2]
            );
        }

        if (substr($remote[0], 0, 4) == "http") {
            $parsed = parse_url($remote[0]);
    
            return array(
                "vendor" => $parsed["host"],
                "repo"   => trim($parsed["path"], '/')
            );
        }
        
        return array(
            "vendor" => "",
            "repo"   => ""
        );
    }

    /**
     * Get git vendor object
     *
     * @return GithubVendor|null
     */
    public function getVendorObject()
    {
        $remote = $this->getRemoteOrigin();

        if ($remote["vendor"] === "github.com") {
            return new GithubVendor($remote["repo"]);
        }

        return null;
    }
}
