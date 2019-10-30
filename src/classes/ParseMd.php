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
            "links"     => array()
        );

        foreach ($this->content as $lineNumber => $line) {
            $line = trim($line, "\n");

            if (trim($line) === '') {
                continue;
            }

            if (trim($line)[0] === '#') {
                $this->parsed["headlines"][$lineNumber] = $this->parseHeadline($line);
            } else {
                $this->parsed["links"][$lineNumber] = $this->parseLinks($line);
            }
        }

        return $this->parsed;
    }

    public function parseLinks($line)
    {
        $pattern_square = '\[(.*?)\]';
        $pattern_round  = "\((.*?)\)";
        $pattern        = "/".$pattern_square.$pattern_round."/";

        preg_match_all($pattern, $line, $matches);

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