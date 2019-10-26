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
    public static function getReadmeFileName($files)
    {
        $readme = array(
            "README", "README.md", "readme", "readme.md"
        );

        // Return first readme found
        return array_values(array_intersect($readme, $files))[0];
    }
}
