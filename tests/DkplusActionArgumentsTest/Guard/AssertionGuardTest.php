<?php
namespace DkplusActionArgumentsTest\Guard;

use DkplusActionArguments\Guard\AssertionGuard;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Mvc\MvcEvent;

/**
 * Class AssertionGuardTest
 *
 * @package DkplusActionArgumentsTest\Guard
 */
class AssertionGuardTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $controllerManager;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $argumentsService;

    /** @var AssertionGuard */
    protected $guard;

    protected function setUp()
    {
        parent::setUp();
        $this->controllerManager = $this->getMock('Zend\\Mvc\\Controller\\ControllerManager');
        $this->argumentsService  = $this->getMockBuilder('DkplusActionArguments\\Service\\ArgumentsService')
                                        ->disableOriginalConstructor()
                                        ->getMock();
        $this->guard             = new AssertionGuard($this->controllerManager, $this->argumentsService);
    }

    public function testShouldListenToTheRouteEvent()
    {
        $events = $this->getMockForAbstractClass('Zend\\EventManager\\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with(MvcEvent::EVENT_ROUTE, array($this->guard, 'onRoute'));

        $this->guard->attach($events);
    }

    public function testShouldRefuseControllersThatDoesNotSupportArguments()
    {
        $controller = $this->getMockForAbstractClass('Zend\\Mvc\\Controller\\AbstractActionController');

        $this->controllerManager->expects($this->once())
                                ->method('get')
                                ->with('MyController')
                                ->will($this->returnValue($controller));

        $event = $this->createEventMop('MyController', 'index');

        $this->guard->onRoute($event);
    }

    protected function createEventMop($controller, $action)
    {
        $routeMatch = $this->getMockBuilder('Zend\\Mvc\\Router\\RouteMatch')->disableOriginalConstructor()->getMock();
        $routeMatch->expects($this->any())
                   ->method('getParam')
                   ->will($this->returnValueMap(array(
                                                     array('controller', null, $controller),
                                                     array('action', null, $action)
                                                )));

        $event = $this->getMock('Zend\\Mvc\MvcEvent');
        $event->expects($this->any())
              ->method('getRouteMatch')
              ->will($this->returnValue($routeMatch));
        return $event;
    }

    public function testShouldInjectArgumentsIntoAssertions()
    {
        $controller = $this->getMockForAbstractClass('DkplusActionArguments\\Controller\\AbstractActionController');
        $this->controllerManager->expects($this->once())
                                ->method('get')
                                ->with('MyController')
                                ->will($this->returnValue($controller));

        $this->argumentsService->expects($this->once())
                               ->method('injectArgumentsIntoAssertions')
                               ->with(
                                   get_class($controller),
                                   'indexAction',
                                   $this->isInstanceOf('Zend\\Mvc\\Router\\RouteMatch')
                               );

        $event = $this->createEventMop('MyController', 'index');

        $this->guard->onRoute($event);
    }
}
