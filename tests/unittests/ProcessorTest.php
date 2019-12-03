<?php
namespace WOSPM\Test;

use WOSPM\Checker;

class ProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testAddMetric()
    {
        $processor = new Checker\Processor();
        $metric = $this->getMockBuilder('Checker\Metric')->setMethods(['execute'])
        ->getMock();
        $metric->code         = "code1";
        $metric->dependencies = array();

        $processor->addMetric($metric);

        $this->assertEquals(1, count($processor->getMetrics()));
        $this->assertTrue(isset($processor->getMetrics()[$metric->code]));
    }

    public function testProcess()
    {
        $processor = new Checker\Processor();

        $metric1 = $this->getMockBuilder('Checker\Metric')->setMethods(['check', 'verboseStart', 'verboseEnd'])
        ->getMock();
        $metric1->code       = "code1";
        $metric1->dependency = array();
        $metric1->method('check')->will($this->returnValue(true));
        $metric1->method('verboseStart')->will($this->returnValue(null));
        $metric1->method('verboseEnd')->will($this->returnValue(null));

        $metric2 = $this->getMockBuilder('Checker\Metric')->setMethods(['check', 'verboseStart', 'verboseEnd'])
        ->getMock();
        $metric2->code       = "code2";
        $metric2->dependency = array();
        $metric2->method('check')->will($this->returnValue(true));
        $metric2->method('verboseStart')->will($this->returnValue(null));
        $metric2->method('verboseEnd')->will($this->returnValue(null));

        $files = array("README");

        $processor->addMetric($metric1);
        $processor->addMetric($metric2);

        $result = $processor->process($files);

        $this->assertEquals(2, count($result));

    }

    public function testProcessWithDependencySuccess()
    {
        $processor = new Checker\Processor();

        $metric1 = $this->getMockBuilder('Checker\Metric')->setMethods(['check', 'verboseStart', 'verboseEnd'])
        ->getMock();
        $metric1->code       = "code1";
        $metric1->dependency = array();
        $metric1->method('check')->will($this->returnValue(array("code" => "code1", "status" => true)));
        $metric1->method('verboseStart')->will($this->returnValue(null));
        $metric1->method('verboseEnd')->will($this->returnValue(null));

        $metric2 = $this->getMockBuilder('Checker\Metric')->setMethods(['check', 'verboseStart', 'verboseEnd'])
        ->getMock();
        $metric2->code       = "code2";
        $metric2->dependency = array("code1");
        $metric2->method('check')->will($this->returnValue(array("code" => "code2", "status" => true)));
        $metric2->method('verboseStart')->will($this->returnValue(null));
        $metric2->method('verboseEnd')->will($this->returnValue(null));

        $files = array("README");

        $processor->addMetric($metric1);
        $processor->addMetric($metric2);

        $result = $processor->process($files);

        $this->assertEquals(2, count($result));
        $this->assertTrue($result["code1"]["status"]);
        $this->assertTrue($result["code2"]["status"]);
    }

    public function testProcessWithDependencyFail()
    {
        $processor = new Checker\Processor();

        $metric1 = $this->getMockBuilder('Checker\Metric')->setMethods(['check', 'fail', 'verboseStart', 'verboseEnd'])
        ->getMock();
        $metric1->code       = "code1";
        $metric1->dependency = array();
        $metric1->method('check')->will($this->returnValue(array("code" => "code1", "status" => false)));
        $metric1->method('fail')->will($this->returnValue(array("code" => "code1", "status" => false)));
        $metric1->method('verboseStart')->will($this->returnValue(null));
        $metric1->method('verboseEnd')->will($this->returnValue(null));

        $metric2 = $this->getMockBuilder('Checker\Metric')->setMethods(['check', 'fail', 'verboseStart', 'verboseEnd'])
        ->getMock();
        $metric2->code       = "code2";
        $metric2->dependency = array("code1");
        $metric2->method('check')->will($this->returnValue(array("code" => "code2", "status" => true)));
        $metric2->method('fail')->will($this->returnValue(array("code" => "code1", "status" => false)));
        $metric2->method('verboseStart')->will($this->returnValue(null));
        $metric2->method('verboseEnd')->will($this->returnValue(null));

        $files = array("README");

        $processor->addMetric($metric1);
        $processor->addMetric($metric2);

        $result = $processor->process($files);

        $this->assertEquals(2, count($result));
        $this->assertFalse($result["code1"]["status"]);
        $this->assertFalse($result["code2"]["status"]);
    }
}
