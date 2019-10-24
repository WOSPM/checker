<?php

namespace WOSPM\Checker;

/**
 * Doc comment for class Processor
 */
class Processor
{
    private $metrics = array();

    /**
     * Constructor of the processor class
     */
    public function __construct()
    {
        $metric = new ReadmeExistsMetric();

        $this->metrics[$metric->code] = $metric;
    }

    /**
     * Process all the metric checks
     *
     * @param array $files Array of files
     *
     * @return array
     */
    public function process($files)
    {
        $array  = array();

        foreach ($this->metrics as $code => $metric) {
            $array[$code] = $metric->check($files);
        }

        return $array;
    }

    /**
     * Prints the result array
     *
     * @param array $array Array of metric results
     *
     * @return array
     */
    public function output($array)
    {
        foreach ($array as $code => $metric) {
            if ($metric["status"] === true) {
                echo "\e[0;41;30mX\e[0m ";
            } else {
                echo "\e[0;42;30m+\e[0m ";
            }
            echo "$code - " . $metric["title"] . ": " .$metric["message"] . PHP_EOL;
        }
    }
}