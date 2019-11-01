<?php
use WOSPM\Checker;

class ReadmeAdequateMetricTest extends PHPUnit_Framework_TestCase
{
    private $metric;
    public function __construct()
    {
        $this->metric = new Checker\ReadmeAdequateMetric();
    }

    public function testReadmeAdequateFail()
    {
        $files = array(
            "README"
        );

        $project = $this->getMockBuilder('Checker\Project')->setMethods(['getReadme'])
        ->getMock();
        $project->method('getReadme')->will($this->returnValue("Lorem ipsum [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md)"));

        $this->metric->setProject($project);
        $this->assertFalse($this->metric->check($files)["status"]);
    }


    public function testReadmeAdequateSuccess()
    {
        $text = "[![Contributor Covenant](https://img.shields.io/badge/Contributor%20Covenant-v1.4%20adopted-ff69b4.svg)](CONTRIBUTING.md) Scelerisque eleifend donec pretium vulputate sapien nec sagittis aliquam malesuada. Bibendum arcu vitae elementum curabitur vitae nunc sed velit dignissim sodales ut eu sem integer vitae justo eget magna. Fermentum iaculis eu non diam phasellus vestibulum lorem sed risus ultricies tristique nulla aliquet enim tortor at auctor urna nunc id cursus metus. Aliquam eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci a scelerisque purus semper eget duis at tellus at urna condimentum mattis. Pellentesque id nibh tortor id aliquet lectus proin nibh nisl condimentum id venenatis a condimentum vitae sapien pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas sed tempus urna et pharetra pharetra massa massa ultricies mi quis hendrerit. Dolor magna eget est lorem ipsum dolor sit amet consectetur adipiscing elit pellentesque habitant morbi tristique senectus et netus et. Malesuada fames ac turpis egestas integer eget aliquet nibh praesent tristique. Magna sit amet purus gravida quis blandit turpis cursus in hac habitasse platea dictumst quisque sagittis purus sit amet volutpat. Consequat mauris nunc congue nisi vitae suscipit tellus mauris a diam maecenas sed enim ut sem viverra aliquet eget sit amet. Tellus cras adipiscing enim eu turpis egestas pretium aenean pharetra magna ac placerat vestibulum lectus. Mauris ultrices eros in cursus turpis massa tincidunt dui ut ornare lectus sit amet est. Placerat in egestas erat imperdiet sed euismod nisi porta. [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md) Lorem mollis aliquam ut porttitor leo a diam sollicitudin tempor id eu nisl nunc mi ipsum faucibus vitae aliquet nec ullamcorper sit.";

        $files = array(
            "README"
        );

        $project = $this->getMockBuilder('Checker\Project')->setMethods(['getReadme'])
        ->getMock();
        $project->method('getReadme')->will($this->returnValue($text));

        $this->metric->setProject($project);
        $this->assertTrue($this->metric->check($files)["status"]);
    }
}
