<?php
use WOSPM\Checker;

const VERSION = '0.0.1';

const SUCCESS   = 0,
    WITH_ERRORS = 1,
    FAILED      = 255;

if (PHP_VERSION < '5.4.0') {
    fwrite(STDERR,"WP Vulnerability Check requires PHP 5.4.0 and newer");
    die(FAILED);
}

function showOptions() {
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
        require $file;
        $autoloadFileFound = true;
        break;
    }
}

if (!$autoloadFileFound) {
    $message = 'You need to set up the project dependencies using composer commands:' . PHP_EOL;
    fwrite(STDERR,
        $message
    );
    echo $message . PHP_EOL;
    die(FAILED);
}


try {
    //$check  = new WPVulnerabilityCheck\Manager($settings);
    //$status = $check->check();

    die($status ? SUCCESS : WITH_ERRORS);
} catch (\Exception $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    die(FAILED);
}
