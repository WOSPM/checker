<?php
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
 * @param array $array Array of metric results
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
    outputREADABLE($array);
}

/**
 * Prints the result array in readable format
 *
 * @param array $array Array of metric results
 *
 * @return void
 */
function outputREADABLE($array)
{
    foreach ($array as $code => $metric) {
        if ($metric["status"] === true) {
            echo "\e[0;42;30m+\e[0m ";
        } else {
            echo "\e[0;41;30mX\e[0m ";
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