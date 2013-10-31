<?php
namespace DkplusActionArgumentsTest;

use DkplusActionArguments\Configuration\Method;
use PHPUnit_Framework_TestCase as TestCase;

class MethodTest extends TestCase
{
    /** @var Method */
    protected $method;

    protected function setUp()
    {
        parent::setUp();
        $this->method = new Method();
    }

    public function testShouldProvideAssertions()
    {
        $assertion = $this->getMockForAbstractClass('Zend\\Permissions\\Acl\\Assertion\\AssertionInterface');
        $this->method->addAssertion($assertion);

        $this->assertSame(array($assertion), $this->method->getAssertions());
    }

    public function testCanProvideAssertionsConnectedWithPermissions()
    {
        $assertion = $this->getMockForAbstractClass('Zend\\Permissions\\Acl\\Assertion\\AssertionInterface');
        $this->method->addAssertion($assertion, 'write');

        $this->assertSame(array('write' => $assertion), $this->method->getAssertions());
    }

    public function testShouldAssembleArgumentMap()
    {
        $routeMatch = $this->getMockBuilder('Zend\\Mvc\\Router\\RouteMatch')
                           ->disableOriginalConstructor()
                           ->getMock();

        $argument = $this->getMockBuilder('DkplusActionArguments\\Configuration\\Argument')
                         ->disableOriginalConstructor()
                         ->getMock();
        $argument->expects($this->any())->method('getPosition')->will($this->returnValue(0));
        $argument->expects($this->any())->method('getName')->will($this->returnValue('foo'));
        $argument->expects($this->any())->method('grabValue')->with($routeMatch)->will($this->returnValue('bar'));

        $this->method->addArgument($argument);
        $this->assertSame(array('foo' => 'bar'), $this->method->assembleArgumentMap($routeMatch));
    }

    public function testShouldAssembleArgumentList()
    {
        $routeMatch = $this->getMockBuilder('Zend\\Mvc\\Router\\RouteMatch')
                           ->disableOriginalConstructor()
                           ->getMock();

        $argument = $this->getMockBuilder('DkplusActionArguments\\Configuration\\Argument')
                         ->disableOriginalConstructor()
                         ->getMock();
        $argument->expects($this->any())->method('getPosition')->will($this->returnValue(0));
        $argument->expects($this->any())->method('grabValue')->with($routeMatch)->will($this->returnValue('bar'));

        $this->method->addArgument($argument);
        $this->assertSame(array('bar'), $this->method->assembleArgumentList($routeMatch));
    }

    public function testShouldAssembleArgumentsOnlyOnceAtRuntime()
    {

        $routeMatch = $this->getMockBuilder('Zend\\Mvc\\Router\\RouteMatch')
                           ->disableOriginalConstructor()
                           ->getMock();
        $routeMatch->expects($this->any())->method('getParams')->will($this->returnValue(array('foo' => 'bar')));

        $argument = $this->getMockBuilder('DkplusActionArguments\\Configuration\\Argument')
                         ->disableOriginalConstructor()
                         ->getMock();
        $argument->expects($this->once())->method('getPosition')->will($this->returnValue(0));
        $argument->expects($this->once())->method('grabValue')->with($routeMatch)->will($this->returnValue('bar'));

        $this->method->addArgument($argument);
        $this->assertSame(
            $this->method->assembleArgumentList($routeMatch),
            $this->method->assembleArgumentList($routeMatch)
        );
    }

    public function testShouldProvideNameOfNotFoundArguments()
    {
        $argumentA = $this->getMockBuilder('DkplusActionArguments\\Configuration\\Argument')
                          ->disableOriginalConstructor()
                          ->getMock();
        $argumentA->expects($this->any())
                  ->method('getPosition')
                  ->will($this->returnValue(0));
        $argumentA->expects($this->any())
                  ->method('isMissing')
                  ->with(null)
                  ->will($this->returnValue(true));
        $argumentA->expects($this->any())
                  ->method('getName')
                  ->will($this->returnValue('foo'));
        $argumentB = $this->getMockBuilder('DkplusActionArguments\\Configuration\\Argument')
                          ->disableOriginalConstructor()
                          ->getMock();
        $argumentB->expects($this->any())
                  ->method('getPosition')
                  ->will($this->returnValue(1));
        $argumentB->expects($this->any())
                  ->method('isMissing')
                  ->with('bar')
                  ->will($this->returnValue(false));

        $this->method->addArgument($argumentA);
        $this->method->addArgument($argumentB);
        $this->assertSame(array('foo'), $this->method->getMissingArgumentNames(array(null, 'bar')));
    }
}
