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
     * Add metric to the metric list
     *
     * @param Metric $metric Metric object
     *
     * @return void
     */
    public function addMetric($metric)
    {
        $this->metrics[$metric->code] = $metric;
    }

    /**
     * Get the list of metrics
     *
     * @return array The list of metrics
     */
    public function getMetrics()
    {
        return $this->metrics;
    }
}
