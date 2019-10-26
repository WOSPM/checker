<?php
use WOSPM\Checker;
class ProcessorTest extends PHPUnit_Framework_TestCase
{
    private $metric;
    public function __construct()
    {
        $this->processor = new Checker\Processor();
    }

    public function testAddMetric()
    {
        $metric = $this->getMockBuilder('Checker\Metric')->setMethods(['execute'])
        ->getMock();
        $metric->code         = "code1";
        $metric->dependencies = array();

        $this->processor->addMetric($metric);

        $this->assertEquals(1, count($this->processor->getMetrics()));
        $this->assertTrue(isset($this->processor->getMetrics()[$metric->code]));
    }

    public function testProcess()
    {
        $metric1 = $this->getMockBuilder('Checker\Metric')->setMethods(['check'])
        ->getMock();
        $metric1->code       = "code1";
        $metric1->dependency = array();
        $metric1->method('check')->will($this->returnValue(true));

        $metric2 = $this->getMockBuilder('Checker\Metric')->setMethods(['check'])
        ->getMock();
        $metric2->code       = "code2";
        $metric2->dependency = array();
        $metric2->method('check')->will($this->returnValue(true));

        $files = array("README");

        $this->processor->addMetric($metric1);
        $this->processor->addMetric($metric2);

        $result = $this->processor->process($files);

        $this->assertEquals(2, count($result));

    }

    public function testProcessWithDependencyFail()
    {
        $metric1 = $this->getMockBuilder('Checker\Metric')->setMethods(['check', 'fail'])
        ->getMock();
        $metric1->code       = "code1";
        $metric1->dependency = array();
        $metric1->method('check')->will($this->returnValue(array("code" => "code1", "status" => false)));
        $metric1->method('fail')->will($this->returnValue(array("code" => "code1", "status" => false)));

        $metric2 = $this->getMockBuilder('Checker\Metric')->setMethods(['check', 'fail'])
        ->getMock();
        $metric2->code       = "code2";
        $metric2->dependency = array("code1");
        $metric2->method('check')->will($this->returnValue(array("code" => "code2", "status" => true)));

        $metric2->method('fail')->will($this->returnValue(array("code" => "code1", "status" => false)));

        $files = array("README");

        $this->processor->addMetric($metric1);
        $this->processor->addMetric($metric2);

        $result = $this->processor->process($files);

        $this->assertEquals(2, count($result));
        $this->assertFalse($result["code1"]["status"]);
        $this->assertFalse($result["code2"]["status"]);
    }
}
