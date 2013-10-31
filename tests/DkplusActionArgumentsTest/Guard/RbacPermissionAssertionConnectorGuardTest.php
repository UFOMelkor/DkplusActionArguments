<?php
namespace DkplusActionArgumentsTest\Guard;

use DkplusActionArguments\Guard\RbacPermissionAssertionConnectorGuard;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Mvc\MvcEvent;

class RbacPermissionAssertionConnectorGuardTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $controllerManager;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $argumentsService;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $connector;
    /** @var RbacPermissionAssertionConnectorGuard */
    protected $guard;

    protected function setUp()
    {
        parent::setUp();
        $this->controllerManager = $this->getMock('Zend\\Mvc\\Controller\\ControllerManager');
        $this->argumentsService  = $this->getMockBuilder('DkplusActionArguments\\Service\\ArgumentsService')
                                        ->disableOriginalConstructor()
                                        ->getMock();
        $this->connector         = $this->getMockForAbstractClass(
            'DkplusActionArguments\\Service\\RbacAssertionPermissionConnector'
        );
        $this->guard             = new RbacPermissionAssertionConnectorGuard(
            $this->controllerManager,
            $this->argumentsService,
            $this->connector
        );
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
        $this->argumentsService->expects($this->any())->method('getAssertions')->will($this->returnValue(array()));

        $event = $this->createEventMop('MyController', 'index');
        $this->guard->onRoute($event);
    }

    public function testShouldConnectPermissionsAndAssertions()
    {
        $controller = $this->getMockForAbstractClass('DkplusActionArguments\\Controller\\AbstractActionController');
        $this->controllerManager->expects($this->once())
                                ->method('get')
                                ->with('MyController')
                                ->will($this->returnValue($controller));

        $assertion = $this->getMockForAbstractClass('Zend\\Permissions\\Rbac\\AssertionInterface');
        $this->argumentsService->expects($this->any())
                               ->method('getAssertions')
                               ->with(get_class($controller), 'indexAction')
                               ->will($this->returnValue(array('write' => $assertion)));

        $this->connector->expects($this->once())->method('connect')->with('write', $assertion);

        $event = $this->createEventMop('MyController', 'index');
        $this->guard->onRoute($event);
    }
}
