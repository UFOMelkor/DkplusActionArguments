<?php
namespace DkplusActionArgumentsTest\Configuration;

use DkplusActionArguments\Configuration\Argument;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @covers DkplusActionArguments\Configuration\Argument
 */
class ArgumentTest extends TestCase
{
    const SOURCE = 'my-source';
    const POSITION = 0;
    const NAME = 'my-name';

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $converter;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $checker;

    protected function setUp()
    {
        parent::setUp();
        $this->converter = $this->getMockForAbstractClass('DkplusActionArguments\\Converter\\Converter');
        $this->checker   = $this->getMockBuilder('DkplusActionArguments\\Configuration\\ArgumentChecker')
                                ->disableOriginalConstructor()
                                ->getMock();
    }

    public function testShouldProvideItsPosition()
    {
        $argument = new Argument(self::SOURCE, self::POSITION, self::NAME, $this->checker);
        $this->assertSame(self::POSITION, $argument->getPosition());
    }

    public function testShouldProvideItsName()
    {
        $argument = new Argument(self::SOURCE, self::POSITION, self::NAME, $this->checker);
        $this->assertSame(self::NAME, $argument->getName());
    }

    public function testShouldGrabItsValueFromRouteMatch()
    {
        $param      = 5;
        $routeMatch = $this->getMockBuilder('Zend\\Mvc\\Router\\RouteMatch')->disableOriginalConstructor()->getMock();
        $routeMatch->expects($this->once())
                   ->method('getParam')
                   ->with(self::SOURCE)
                   ->will($this->returnValue($param));

        $argument = new Argument(self::SOURCE, self::POSITION, self::NAME, $this->checker);
        $this->assertSame($param, $argument->grabValue($routeMatch));
    }

    public function testCanConvertValues()
    {
        $param      = 5;
        $routeMatch = $this->getMockBuilder('Zend\\Mvc\\Router\\RouteMatch')->disableOriginalConstructor()->getMock();
        $routeMatch->expects($this->any())
                   ->method('getParam')
                   ->with(self::SOURCE)
                   ->will($this->returnValue($param));

        $convertedParam = new \stdClass();
        $this->converter->expects($this->once())
                        ->method('apply')
                        ->with(array($param))
                        ->will($this->returnValue($convertedParam));

        $argument = new Argument(self::SOURCE, self::POSITION, self::NAME, $this->checker, $this->converter);
        $this->assertSame($convertedParam, $argument->grabValue($routeMatch));
    }

    public function testCanConvertValuesWithMultipleSources()
    {
        $routeMatch = $this->getMockBuilder('Zend\\Mvc\\Router\\RouteMatch')->disableOriginalConstructor()->getMock();
        $routeMatch->expects($this->any())
                   ->method('getParam')
                   ->will($this->returnValueMap(
                       array(
                           array('source1', null, 'value1'),
                           array('source2', null, 'value2')
                       )
                   ));

        $convertedParam = new \stdClass();
        $this->converter->expects($this->once())
                        ->method('apply')
                        ->with(array('value1', 'value2'))
                        ->will($this->returnValue($convertedParam));

        $argument = new Argument(
            array('source1', 'source2'),
            self::POSITION,
            self::NAME,
            $this->checker,
            $this->converter
        );
        $this->assertSame($convertedParam, $argument->grabValue($routeMatch));
    }

    public function testCanDetectItselfAsMissing()
    {
        $value = null;
        $this->checker->expects($this->once())
                      ->method('isMissing')
                      ->with($value)
                      ->will($this->returnValue(true));

        $argument = new Argument(self::SOURCE, self::POSITION, self::NAME, $this->checker);
        $this->assertTrue($argument->isMissing($value));
    }
}
