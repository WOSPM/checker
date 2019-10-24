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
        $readme = new ReadmeExistsMetric();
        $this->metrics[$readme->code] = $readme;

        $license = new LicenseExistsMetric();
        $this->metrics[$license->code] = $license;

        $contribute = new ContributingExistsMetric();
        $this->metrics[$contribute->code] = $contribute;

        $coc = new CocExistsMetric();
        $this->metrics[$coc->code] = $coc;
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
}
