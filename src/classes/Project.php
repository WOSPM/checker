<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class Project
 */
class Project
{
    /**
     * Vendor object
     *
     * @var Vendor|null
     */
    private $vendor = null;
    
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
     * Get the content of the README file
     *
     * @param array $files Array of files
     *
     * @return string
     */
    public function getReadme($files)
    {
        return file_get_contents(Project::getReadmeFileName($files));
    }

    /**
     * Create a slug from the given string
     *
     * @param string $str       The string to be sluged
     * @param string $delimiter The delimiter to be used in slug
     *
     * @return string
     */
    public static function createSlug($str, $delimiter = '-')
    {
        $slug = strtolower(
            trim(
                preg_replace(
                    '/[\s-]+/',
                    $delimiter,
                    preg_replace(
                        '/[^A-Za-z0-9-]+/',
                        $delimiter,
                        preg_replace(
                            '/[&]/',
                            'and',
                            preg_replace(
                                '/[\']/',
                                '',
                                iconv('UTF-8', 'utf-8//TRANSLIT', $str)
                            )
                        )
                    )
                ), 
                $delimiter
            )
        );
        return $slug;
    }
}
