<?php
use WOSPM\Checker;

const VERSION = '0.0.1';

const SUCCESS   = 0,
    WITH_ERRORS = 1,
    FAILED      = 255;

if (PHP_VERSION < '5.4.0') {
    fwrite(STDERR, "WOSPM Checker requires PHP 5.4.0 and newer");
    die(FAILED);
}

require_once __DIR__ . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "functions" . 
DIRECTORY_SEPARATOR . "functions.php";

// Help
if (in_array('--help', $_SERVER['argv'])) {
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
    $arguments = Checker\Arguments::parseArguments($_SERVER['argv']);
    $files     = scandir($arguments->path);
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
