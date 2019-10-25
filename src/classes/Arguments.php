<?php
namespace WOSPM\Checker;

/**
 * Doc comment for class Arguments
 */
class Arguments
{
    /**
     * Full path of the installation to check
     * 
     * @var string
     */
    public $path = '.';

    /**
     * Use colors in console output
     *
     * @var bool
     */
    public $colors = true;

    /**
     * Format of console output
     * 
     * @var string
     */
    public $output = 'READABLE';

    /**
     * @param array $arguments
     *
     * @return Settings
     *
     * @throws Exception\InvalidArgument
     */
    public static function parseArguments(array $arguments)
    {
        $arguments       = new ArrayIterator(array_slice($arguments, 1));
        $setting         = new self;

        foreach ($arguments as $argument) {
            if ($argument{0} !== '-') {
                throw new \Exception("Invalid argument $argument");
            } else {
                switch ($argument) {
                    case '--output':
                        $setting->output = trim($arguments->getNext());
                        break;
                    case '--no-colors':
                        $setting->colors = false;
                        break;
                    default:
                        throw new \Exception("Invalid argument $argument");
                }
            }
        }

        return $setting;
    }
}
