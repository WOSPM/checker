<?php
use WOSPM\Checker;

const VERSION = '0.0.1';

const SUCCESS   = 0,
    WITH_ERRORS = 1,
    FAILED      = 255;

if (PHP_VERSION < '5.4.0') {
    fwrite(STDERR, "WP Vulnerability Check requires PHP 5.4.0 and newer");
    die(FAILED);
}

/**
 * Shows the commandline options
 */
function showOptions()
{
    ?>
WOSPM Checker version: <?php echo VERSION; ?>
Options:
    --output            The format of output. Valid values JSON, READABLE,
                        NO (Default).
    --no-colors         Disable the console colors. It is enabled by default.
    --version           Show version.
    --help              Print this help.
    <?php
}

/**
 * Prints the result array
 *
 * @param array $array Array of metric results
 *
 * @return array
 */
function output($array)
{
    foreach ($array as $code => $metric) {
        if ($metric["status"] === true) {
            echo "\e[0;42;30m+\e[0m ";
        } else {
            echo "\e[0;41;30mX\e[0m ";
        }
        echo "$code - " . $metric["title"] . ": " .$metric["message"] . PHP_EOL;
    }
}

/**
 * Prints banner
 *
 * @return void
 */
function banner() {
    echo "------------------------------------------" . PHP_EOL;
    echo " Welcoming Open Source Project Metrics " . PHP_EOL;
    echo PHP_EOL;
    echo " Checker is analysing your project..." . PHP_EOL;
    echo "------------------------------------------" . PHP_EOL;
    echo PHP_EOL;
    echo "Here is the result;" . PHP_EOL . PHP_EOL;
}
// Help
if (!isset($_SERVER['argv'][1]) || in_array('--help', $_SERVER['argv'])) {
    showOptions();
    exit;
}

// Version
if (in_array('--version', $_SERVER['argv'])) {
    echo VERSION . PHP_EOL;
    exit;
}


if (!function_exists('json_encode')) {
    echo 'PHP JSON extension is a must.' . PHP_EOL;
    die(FAILED);
}

$files = array(
    __DIR__ . '/../../autoload.php',
    __DIR__ . '/vendor/autoload.php'
);

$autoloadFileFound = false;
foreach ($files as $file) {
    if (file_exists($file)) {
        include $file;
        $autoloadFileFound = true;
        break;
    }
}

if (!$autoloadFileFound) {
    $message = 'Project dependencies are not installed (composer install):';

    fwrite(STDERR, $message . PHP_EOL);
    echo $message . PHP_EOL . PHP_EOL;
    die(FAILED);
}


try {
    $files     = scandir(".");
    $processor = new Checker\Processor();
    $result    = $processor->process($files);
    banner();
    output($result);
    echo PHP_EOL;
    $status = true;
    die($status ? SUCCESS : WITH_ERRORS);
} catch (\Exception $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    die(FAILED);
}
