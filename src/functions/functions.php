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
    --verbose           Show the progress or not. (0 => No, 1 => Detailed,
                        2 => Dots)
    --no-colors         Disable the console colors. It is enabled by default.
    --version           Show version.
    --help              Print this help.
    <?php
    echo PHP_EOL;
}

/**
 * Calculate the status of the result
 *
 * @param array $array Array of metric results
 *
 * @return boolean
 */
function status($array)
{
    return array_reduce(
        $array,
        function ($status, $metric) {
            return (
                $status and $metric["status"]
            );
        },
        true
    );
}

/**
 * Echoes the badge markdown
 *
 * @param int $percent Percent of the success
 *
 * @return void
 */
function badge($percent)
{
    echo "WOSPM badge for your project is below. You can use it in your README.";
    echo PHP_EOL;
    if ($percent >= 90) {
        echo '![Welcoming](https://img.shields.io/badge/WOSPM-Welcoming-green)';
        echo PHP_EOL;
        return;
    }

    if ($percent < 50) {
        echo '![Bad](https://img.shields.io/badge/WOSPM-Bad-red)';
        echo PHP_EOL;
        return;
    }

    echo '![Not Ready](https://img.shields.io/badge/WOSPM-Not--Ready-orange)';
    echo PHP_EOL;
    return;
}

/**
 * Calculate the status of the result
 *
 * @param array $array Array of metric results
 *
 * @return double
 */
function percent($array)
{
    return intval(
        ceil(
            (
                array_reduce(
                    $array,
                    function ($success, $metric) {
                        if ($metric["status"] === true) {
                            return ($success+1);
                        } else {
                            return $success;
                        }
                    },
                    0
                ) / count($array)
            )
        ) * 100
    );
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
    echo PHP_EOL . PHP_EOL . "Here is the result;" . PHP_EOL . PHP_EOL;

    if ($arguments->output === 'JSON') {
        outputJSON($array);
        return;
    }

    if ($arguments->output === 'NO') {
        return;
    }

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
}

/**
 * Register metrics to processor class
 *
 * @param Arguments         $arguments Commandline arguments
 * @param ProjectRepository $repo      Repo object
 *
 * @return Checker\Processor
 */
function processor($arguments, $repo)
{
    $processor = new Checker\Processor($arguments->verbose);
    
    $processor->addMetric(new Checker\UsingWOSPMMetric());
    $processor->addMetric(new Checker\ReadmeExistsMetric());
    $processor->addMetric(new Checker\LicenseExistsMetric());
    $processor->addMetric(new Checker\ContributingExistsMetric());
    $processor->addMetric(new Checker\CocExistsMetric());
    $processor->addMetric(new Checker\ReadmeContributingLinkExistsMetric());
    $processor->addMetric(new Checker\ReadmeCocLinkExistsMetric());
    $processor->addMetric(new Checker\ReadmeTocExistsMetric());
    $processor->addMetric(new Checker\ReadmeAdequateMetric());
    $processor->addMetric(new Checker\ReadmeInstallationExistsMetric());
    $processor->addMetric(new Checker\GithubIssueTemplateExistsMetric());
    $processor->addMetric(new Checker\GithubPRTemplateExistsMetric());

    return $processor;
}

/**
 * Scan the given folder as project folder
 *
 * @param string $path The path of the directory to be checked
 *
 * @return array
 */
function scanProjectDir($path)
{
    $allFiles = scandir($path);
    $files    = array_diff($allFiles, array('.', '..'));

    // If there is a .github folder
    if (in_array(".github", $files)) {
        $gitpath  = $path . DIRECTORY_SEPARATOR . ".github";
        $github   = scanAllDir($gitpath);
        $files    = array_merge($files, $github);
    }

    return $files;
}

/**
 * Scan the given folder recursively
 *
 * @param string $path The path of the directory to be checked
 *
 * @return array
 */
function scanAllDir($path)
{
    $allFiles = scandir($path);
    $files    = array_diff($allFiles, array('.', '..'));
    $subfiles = array(); 

    foreach ($files as $key => $element) {
        if ($element == "." || $element == ".." || $element == ".git") {
            continue;
        }

        $el = $path . DIRECTORY_SEPARATOR . $element . DIRECTORY_SEPARATOR;
        if (is_dir($el)) {
            $elfiles  = scanAllDir($el);
            $elfiles  = preg_filter('/^/', $el, $elfiles);
            $subfiles = array_merge($subfiles, $elfiles);

            $files[$key] = $path . DIRECTORY_SEPARATOR . $element;
            continue;
        }
    }

    $files = array_merge($files, $subfiles);

    return $files;
}
