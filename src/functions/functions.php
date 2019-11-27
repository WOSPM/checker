<?php
use WOSPM\Checker;
use Symfony\Component\Yaml\Yaml;

/**
 * Shows the commandline options
 */
function showOptions()
{
    echo PHP_EOL;
    ?>
WOSPM Checker version: <?php echo VERSION . PHP_EOL; ?>
Options:
    --output            Format of output. JSON, READABLE (Default), NO, HTML.
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
    if ($percent == 100) {
        return '![Perfect](https://img.shields.io/badge/WOSPM-Perfect-blue)';
    }

    if ($percent >= 90) {
        return '![Welcoming](https://img.shields.io/badge/WOSPM-Welcoming-green)';
    }

    if ($percent < 50) {
        return '![Bad](https://img.shields.io/badge/WOSPM-Bad-red)';
    }

    return '![Not Ready](https://img.shields.io/badge/WOSPM-Not--Ready-orange)';
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
            ) * 100
        )
    );
}

/**
 * Prints the result array in according to the argument
 *
 * @param array     $array     Array of metric results
 * @param Arguments $arguments Arguments object containing the commandline arguments
 * @param Repo      $repo      Repo object
 *
 * @return void
 */
function output($array, $arguments, $repo)
{
    if ($arguments->output === 'JSON') {
        outputJSON($array);
        return;
    }

    if ($arguments->output === 'HTML') {
        outputHTML($array, $arguments, $repo);
        return;
    }

    if ($arguments->output === 'NO') {
        return;
    }

    echo PHP_EOL . PHP_EOL . "Here is the result;" . PHP_EOL . PHP_EOL;

    outputREADABLE($array, $arguments->colors);

    echo "WOSPM badge for your project is below. You can use it in your README.";
    echo PHP_EOL;

    $percent = percent($array);

    echo badge($percent);
    echo PHP_EOL;
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
    $status  = status($array);
    $percent = percent($array);

    $result = array(
        "status"  => $status,
        "percent" => $percent,
        "badge"   => badge($percent),
        'metrics' => array_values($array)
    );

    echo json_encode($result);
    echo PHP_EOL;
}

/**
 * Prints the result in an HTML file
 *
 * @param array     $array     Array of metric results
 * @param Arguments $arguments Arguments object
 * @param Repo      $repo      Repo object
 *
 * @return void
 */
function outputHTML($array, $arguments, $repo)
{
    $status  = status($array);
    $percent = percent($array);
    $badge   = badge($percent);

    preg_match("/(?:!\[(.*?)\]\((.*?)\))/", $badge, $matches);

    $cssArray = array(
        "Perfect"   => "primary",
        "Welcoming" => "success",
        "Not Ready" => "warning",
        "Bad"       => "danger"
    );

    $statusText = $matches[1];
    $statusCSS  = $cssArray[$statusText];
    
    $resultHTML = '
<table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Code</th>
      <th scope="col">Title</th>
      <th scope="col">Message</th>
    </tr>
  </thead>
  <tbody>
    ';

    $i = 1;
    foreach ($array as $key => $value) {
        $css = "danger";
        if ($value["status"] === true) {
            $css = "success";
        }
        $resultHTML .= '
    <tr class="table-' . $css . '">
      <th scope="row">' . $i . '</th>
      <td><a href="https://github.com/WOSPM/checker/blob/master/mdocs/' .
        $key .'.md" target="_blank">' . $key .'</a></td>
      <td>' . $value['title'] . '</td>
      <td>' . $value['message'] . '</td>
    </tr>
        ';
        $i++;
    }
    $resultHTML .= '</tbody></table>';

    $template = file_get_contents(
        $arguments->path . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR .
        "templates" . DIRECTORY_SEPARATOR . "report.html"
    );

    $template = str_replace("{{PROJECT}}", $repo->getRepo(), $template);
    $template = str_replace("{{STATUS_CSS}}", $statusCSS, $template);
    $template = str_replace("{{STATUS_TEXT}}", $statusText, $template);
    $template = str_replace("{{STATUS_PERCENT}}", $percent, $template);
    $template = str_replace("{{RESULT_TABLE}}", $resultHTML, $template);

    file_put_contents(
        $arguments->path . DIRECTORY_SEPARATOR . $arguments->htmlOutFile,
        $template
    );

    echo "The report is generated and saved as " .
    $arguments->path . DIRECTORY_SEPARATOR . $arguments->htmlOutFile;
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
    $file = PROJECT_FOLDER . DIRECTORY_SEPARATOR . ".wospm";
    // Check if .wospm file exists for configuration
    if (file_exists($file)) {
        $config = Yaml::parseFile($file);

        $repo->setAToken($config["github"]["auth_token"]);
    }

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
    $processor->addMetric(new Checker\GithubDescriptionExistsMetric($repo));
    $processor->addMetric(new Checker\GithubTopicExistsMetric($repo));
    $processor->addMetric(new Checker\GithubLabelExistsMetric($repo));
    $processor->addMetric(new Checker\GithubLabelUsedMetric($repo));
    $processor->addMetric(new Checker\GithubCustomLabelExistsMetric($repo));
    $processor->addMetric(new Checker\GithubGFIAndHWLabelsExistMetric($repo));
    $processor->addMetric(new Checker\GithubCustomLabelUsedMetric($repo));
    $processor->addMetric(new Checker\GithubGFIAndHWLabelsUsedMetric($repo));

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
            //$elfiles  = preg_filter('/^/', $el, $elfiles);
            $subfiles = array_merge($subfiles, $elfiles);

            //$files[$key] = $path . DIRECTORY_SEPARATOR . $element;
            unset($files[$key]);
            continue;
        } else {
            $files[$key] = trim($path, DIRECTORY_SEPARATOR) .
            DIRECTORY_SEPARATOR . $element;
        }
    }

    $files = array_merge($files, $subfiles);

    return $files;
}
