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
        $result = array();

        // First dependencies are checked
        foreach ($this->metrics as $code => $metric) {
            foreach ($metric->dependency as $dcode) {
                if (!isset($result[$dcode])) {
                    $result[$dcode] = $this->metrics[$dcode]->check($files);
                }
            }
        }

        foreach ($this->metrics as $code => $metric) {
            $fail = false;

            foreach ($metric->dependency as $dcode) {
                if ($result[$dcode]["status"] === false) {
                    $fail = true;
                    break;
                }
            }

            if ($fail === true) {
                $result[$code] = $metric->fail();
                continue;
            }

            $result[$code] = $metric->check($files);
        }

        return $result;
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
