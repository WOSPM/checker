<?php
use WOSPM\Checker;
/**
 * Shows the commandline options
 */
function showOptions()
{
    echo PHP_EOL;
    ?>
WOSPM Checker version: <?php echo VERSION . PHP_EOL; ?>
Options:
    --output            The format of output. JSON, READABLE (Default), NO.
    --no-colors         Disable the console colors. It is enabled by default.
    --version           Show version.
    --help              Print this help.
    <?php
    echo PHP_EOL;
}

/**
 * Prints the result array in according to the argument
 *
 * @param array     $array     Array of metric results
 * @param Arguments $arguments Arguments object containing the commandline arguments
 *
 * @return void
 */
function output($array, $arguments)
{
    if ($arguments->output === 'JSON') {
        outputJSON($array);
        return;
    }

    if ($arguments->output === 'NO') {
        return;
    }

    banner();
    outputREADABLE($array, $arguments->colors);
}

/**
 * Prints the result array in readable format
 *
 * @param array   $array  Array of metric results
 * @param boolean $colors Boolean flag to use colors or not
 *
 * @return void
 */
function outputREADABLE($array, $colors = true)
{
    $succes = "+ ";
    $fail   = "X ";

    foreach ($array as $code => $metric) {
        if ($colors === true) {
            $succes = "\e[0;42;30m+\e[0m ";
            $fail   = "\e[0;41;30mX\e[0m ";

            switch ($metric["type"]) {
            case Checker\MetricType::WARNING:
                $fail   = "\e[0;43;30mX\e[0m ";
                break;
            case Checker\MetricType::INFO:
                $fail   = "\e[0;44;30mX\e[0m ";
                break;
            default:
                $fail   = "\e[0;41;30mX\e[0m ";
                break;
            }
        }
        if ($metric["status"] === true) {
            echo $succes;
        } else {
            echo $fail;
        }
        echo "$code - " . $metric["title"] . ": " .$metric["message"] . PHP_EOL;
    }
    echo PHP_EOL;
}

/**
 * Prints the result json
 *
 * @param array $array Array of metric results
 *
 * @return void
 */
function outputJSON($array)
{
    echo json_encode(array_values($array), true);
    echo PHP_EOL;
}

/**
 * Prints banner
 *
 * @return void
 */
function banner()
{
    echo "-------------------------------------------------------------------";
    echo PHP_EOL;
    echo "| Welcoming Open Source Project Metrics " . PHP_EOL;
    echo "|" . PHP_EOL;
    echo "| Checker " . VERSION ." is analysing your project..." . PHP_EOL;
    echo "-------------------------------------------------------------------";
    echo PHP_EOL;
    echo PHP_EOL;
    echo "Here is the result;" . PHP_EOL . PHP_EOL;
}

/**
 * Register metrics to processor class
 *
 * @return Checker\Processor
 */
function processor()
{
    $processor = new Checker\Processor();
    
    $processor->addMetric(new Checker\ReadmeExistsMetric());
    $processor->addMetric(new Checker\LicenseExistsMetric());
    $processor->addMetric(new Checker\ContributingExistsMetric());
    $processor->addMetric(new Checker\CocExistsMetric());
    $processor->addMetric(new Checker\ReadmeContributingLinkExistsMetric());
    $processor->addMetric(new Checker\ReadmeCocLinkExistsMetric());
    $processor->addMetric(new Checker\ReadmeTocExistsMetric());

    return $processor;
}
