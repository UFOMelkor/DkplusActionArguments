<?php
namespace DkplusActionArgumentsTest\Guard;

use DkplusActionArguments\Guard\ArgumentsGuard;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Mvc\MvcEvent;

/**
 * @covers DkplusActionArguments\Guard\ArgumentsGuard
 */
class ArgumentsGuardTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $controllerManager;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $argumentsService;
    /** @var ArgumentsGuard */
    protected $guard;

    protected function setUp()
    {
        parent::setUp();
        $this->controllerManager = $this->getMock('Zend\\Mvc\\Controller\\ControllerManager');
        $this->argumentsService  = $this->getMockBuilder('DkplusActionArguments\\Service\\ArgumentsService')
                                        ->disableOriginalConstructor()
                                        ->getMock();
        $this->guard             = new ArgumentsGuard($this->controllerManager, $this->argumentsService);
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

    public function testShouldDoNothingIfAllArgumentsCouldBeFound()
    {
        $controller = $this->getMockForAbstractClass('DkplusActionArguments\\Controller\\AbstractActionController');
        $this->controllerManager->expects($this->any())
                                ->method('get')
                                ->with('MyController')
                                ->will($this->returnValue($controller));

        $event = $this->createEventMop('MyController', 'index');
        $event->expects($this->never())->method('setError');

        $this->argumentsService->expects($this->once())
                               ->method('getNamesOfMissingArguments')
                               ->with(get_class($controller), 'indexAction', $event->getRouteMatch())
                               ->will($this->returnValue(array()));

        $this->guard->onRoute($event);
    }

    public function testShouldTriggerADispatchErrorEventOnMissingArguments()
    {
        $controller = $this->getMockForAbstractClass('DkplusActionArguments\\Controller\\AbstractActionController');
        $this->controllerManager->expects($this->any())
                                ->method('get')
                                ->with('MyController')
                                ->will($this->returnValue($controller));

        $event = $this->createEventMop('MyController', 'index');

        $events = $this->getMockForAbstractClass('Zend\\EventManager\\EventManagerInterface');
        $events->expects($this->once())
               ->method('trigger')
               ->with(MvcEvent::EVENT_DISPATCH_ERROR, $event);

        $application = $this->getMockForAbstractClass('Zend\\Mvc\\ApplicationInterface');
        $application->expects($this->any())
                    ->method('getEventManager')
                    ->will($this->returnValue($events));

        $event->expects($this->any())
              ->method('getTarget')
              ->will($this->returnValue($application));

        $this->argumentsService->expects($this->once())
                               ->method('getNamesOfMissingArguments')
                               ->with(get_class($controller), 'indexAction', $event->getRouteMatch())
                               ->will($this->returnValue(array('foo')));

        $this->guard->onRoute($event);
    }
}
