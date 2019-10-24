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
        $metric = new Checker\ReadmeExistsMetric();
    }
}