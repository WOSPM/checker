<?php

namespace WOSPM\Checker;

/**
 * Doc comment for class Processor
 */
class Processor
{
    private $metrics = array();

    private $verbose = null;

    /**
     * Constructor of the processor class
     *
     * @param string|null $verbose The verbose flag of the processor
     */
    public function __construct($verbose = null)
    {
        if ($verbose !== null) {
            $this->setVerbose($verbose);
        }
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
                    $this->metrics[$dcode]->verbose($this->verbose);
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

            $metric->verbose($this->verbose);
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

    /**
     * Setter for the verbose property
     *
     * @param string|null $verbose The verbosity flag
     *
     * @return void
     */
    public function setVerbose($verbose = null)
    {
        $this->verbose = $verbose;
    }
}
