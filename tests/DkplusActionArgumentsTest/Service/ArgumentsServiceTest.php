<?php
namespace DkplusActionArgumentsTest\Service;

use DkplusActionArguments\Service\ArgumentsService;
use PHPUnit_Framework_TestCase as TestCase;

class ArgumentsServiceTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $configProvider;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $services;
    /** @var ArgumentsService */
    protected $service;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $routeMatch;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $config;
    /** @var string */
    protected $controllerClass = 'My\\ControllerClass';
    /** @var string */
    protected $actionMethod = 'indexAction';

    protected function setUp()
    {
        parent::setUp();
        $this->configProvider = $this->getMockBuilder('DkplusActionArguments\\Service\\MethodConfigurationProvider')
                                     ->disableOriginalConstructor()
                                     ->getMock();
        $this->services       = $this->getMockForAbstractClass('Zend\\ServiceManager\\ServiceLocatorInterface');
        $this->service        = new ArgumentsService($this->configProvider, $this->services);
        $this->routeMatch     = $this->getMockBuilder('Zend\\Mvc\\Router\\RouteMatch')
                                     ->disableOriginalConstructor()
                                     ->getMock();
        $this->config         = $this->getMock('DkplusActionArguments\\Configuration\\Method');
    }

    public function testShouldAssembleAnArgumentListForAnActionMethod()
    {
        $list = array('foo', 'bar');
        $this->config->expects($this->once())
                     ->method('assembleArgumentList')
                     ->with($this->routeMatch)
                     ->will($this->returnValue($list));
        $this->configProvider->expects($this->once())
                             ->method('computeMethodConfiguration')
                             ->with($this->controllerClass, $this->actionMethod)
                             ->will($this->returnValue($this->config));

        $this->assertSame(
            $list,
            $this->service->getArgumentsList($this->controllerClass, $this->actionMethod, $this->routeMatch)
        );
    }

    public function testShouldProvideAssertionsForAnActionMethod()
    {
        $assertion = $this->getMock('stdClass');

        $this->configProvider->expects($this->any())
                             ->method('computeMethodConfiguration')
                             ->with($this->controllerClass, $this->actionMethod)
                             ->will($this->returnValue($this->config));
        $this->config->expects($this->once())
                     ->method('getAssertions')
                     ->will($this->returnValue(array('MyAssertion')));

        $this->services->expects($this->once())
                       ->method('get')
                       ->with('MyAssertion')
                       ->will($this->returnValue($assertion));

        $this->assertSame(
            array($assertion),
            $this->service->getAssertions($this->controllerClass, $this->actionMethod)
        );
    }

    public function testShouldInjectArgumentsIntoAssertions()
    {
        $userName  = 'foo';
        $assertion = $this->getMock('stdClass', array('setUserName'));
        $assertion->expects($this->once())
                  ->method('setUserName')
                  ->with($userName);

        $this->configProvider->expects($this->any())
                             ->method('computeMethodConfiguration')
                             ->will($this->returnValue($this->config));
        $this->config->expects($this->once())
                     ->method('assembleArgumentMap')
                     ->with($this->routeMatch)
                     ->will($this->returnValue(array('userName' => $userName)));
        $this->config->expects($this->once())
                     ->method('getAssertions')
                     ->will($this->returnValue(array('MyAssertion')));

        $this->services->expects($this->once())
                       ->method('get')
                       ->with('MyAssertion')
                       ->will($this->returnValue($assertion));

        $this->service->injectArgumentsIntoAssertions('MyController', 'indexAction', $this->routeMatch);
    }

    public function testShouldProvideNamesOfArgumentsThatCouldBeFound()
    {
        $list = array('foo', 'bar');
        $this->config->expects($this->once())
                     ->method('assembleArgumentList')
                     ->with($this->routeMatch)
                     ->will($this->returnValue($list));
        $this->config->expects($this->once())
                     ->method('getNamesOfMissingArguments')
                     ->with($list)
                     ->will($this->returnValue(array('bar')));
        $this->configProvider->expects($this->once())
                             ->method('computeMethodConfiguration')
                             ->will($this->returnValue($this->config));


        $this->assertSame(
            array('bar'),
            $this->service->getNamesOfMissingArguments($this->controllerClass, $this->actionMethod, $this->routeMatch)
        );
    }
}
