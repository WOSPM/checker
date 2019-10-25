<?php
use WOSPM\Checker;
class ArgumentsTest extends PHPUnit_Framework_TestCase
{
    private $metric;
    public function __construct()
    {
        $this->arguments = new Checker\Arguments();
    }

    public function testArguments()
    {
        // Upper case 1
        $inputs = array(
            "./wospm-checker", "--output", "JSON" , "--no-colors"
        );

        $arguments = Checker\Arguments::parseArguments($inputs);

        $this->assertEquals("JSON", $arguments->output);
        $this->assertFalse($arguments->colors);
    }
}
