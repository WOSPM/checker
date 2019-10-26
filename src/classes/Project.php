<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class Project
 */
class Project
{
    /**
     * Get the readme file name of the project
     *
     * @param array $files Array of files
     *
     * @return string
     */
    public function getReadmeFileName($files)
    {
        $readme = array(
            "README", "README.md", "readme", "readme.md"
        );

        // Return first readme found
        return array_values(array_intersect($readme, $files))[0];
    }

    /**
     * Get hte content of the README file
     *
     * @param array $files Array of files
     *
     * @return string
     */
    public function getReadme($files)
    {
        return file_get_contents(Project::getReadmeFileName($files));
    }
}
