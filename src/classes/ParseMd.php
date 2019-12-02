<?php

namespace WOSPM\Checker;

/**
 * Doc comment for class ParseMd
 */
class ParseMd
{
    private $file = null;

    private $content = null;

    private $parsed  = null;

    private $patterns = array();

    /**
     * Constructor for the class
     * 
     * @param string|null $file The file path to be parsed
     *
     * @return void
     */
    public function __construct($file = null)
    {
        if ($file !== null) {
            $this->setFile($file);
        }

        $pattern_square = '\[(.*?)\]';
        $pattern_round  = "\((.*?)\)";

        $this->patterns = array(
            "link" => "/" . $pattern_square . $pattern_round . "/",
            "image" => array(
                "inline"    => '/(?:!\[(.*?)\]\((.*?)\))/',
                "reference" => '/(?:!\[(.*?)\]\[(.*?)\])/'
            ),
            "code" => '/(```[a-z]\n*[\s\S]*?\n```)/',
            "html" => array(
                "comment" => '/<!--(.|\s)*?-->/'
            )
        );
    }

    /**
     * Parse the string content in to a structured array
     *
     * @return void
     */
    public function parse()
    {
        $this->parsed = array(
            "headlines" => array(),
            "links"     => array(),
            "rawtexts"  => array()
        );

        foreach ($this->content as $ln => $line) {
            $line = trim($line, "\n");

            if (trim($line) === '') {
                continue;
            }

            $this->parsed["links"][$ln]    = $this->parseLinks($line);

            if (trim($line)[0] === '#') {
                $this->parsed["headlines"][$ln] = $this->parseHeadline($line);
            } else {
                if (strlen(str_replace('-', '', $line)) === 0) {
                    // This means that it is line containing only "-"
                    // And it just after an headline
                    $this->parsed["headlines"][$ln-1] =
                        $this->parseHeadline($this->content[$ln-1]);
                } else {
                    $this->parsed["rawtexts"][$ln] = $this->parseAsRawText($line);
                }
            }
        }

        return $this->parsed;
    }

    /**
     * Parse links from MD
     * 
     * @param string $line One single line
     *
     * @return array
     */
    public function parseLinks($line)
    {
        preg_match_all($this->patterns["link"], $line, $matches);

        $links = array();
        
        foreach ($matches[0] as $key => $val) {
            $links[] = array(
                "text" => $matches[1][$key],
                "url"  => $matches[2][$key]
            );
        }

        return array(
            "raw"   => $line,
            "links" => $links
        );
    }

    /**
     * Parse headline from MD
     * 
     * @param string $line One single line
     *
     * @return array
     */
    public function parseHeadline($line)
    {
        $parsed = trim(trim(trim($line), '#'), "\n");

        return array(
            "raw"    => $line,
            "parsed" => $parsed,
            "slug"   => Project::createSlug($parsed),
            "level"  => substr_count($line, '#')
        );
    }

    /**
     * Parse line from MD and make it raw
     * 
     * @param string $line One single line
     *
     * @return array
     */
    public function parseAsRawText($line)
    {
        // Cleans images
        $parsed = trim(
            preg_replace(
                $this->patterns["image"]["reference"],
                "",
                $line
            )
        );
        $parsed = trim(
            preg_replace(
                $this->patterns["image"]["inline"],
                "",
                $parsed
            )
        );

        // Replace the links with the text of the link
        $parsed = trim(
            preg_replace(
                $this->patterns["link"],
                "$1",
                $parsed
            )
        );

        // Remove code blocks
        $parsed = trim(
            preg_replace(
                $this->patterns["code"],
                "",
                $parsed
            )
        );

        // Remove html comment blocks
        $parsed = trim(
            preg_replace(
                $this->patterns["html"]["comment"],
                "",
                $parsed
            )
        );

        $parsed = str_replace(PHP_EOL, " ", $parsed);
        $parsed = str_replace("-", "", $parsed);
        $parsed = str_replace("|", "", $parsed);
        $parsed = str_replace("#", "", $parsed);

        return array(
            "raw"    => $line,
            "parsed" => $parsed
        );
    }

    /**
     * Setter for file property
     * 
     * @param string $file The file path
     *
     * @return void
     */
    public function setFile($file)
    {
        $this->file    = $file;
        $this->content = file($file);
    }

    /**
     * Setter for content property
     * 
     * @param array $content Array of lines in the file
     *
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}
