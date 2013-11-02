<?php
namespace DkplusActionArgumentsTest\View;

use DkplusActionArguments\Guard\ArgumentsGuard;
use DkplusActionArguments\View\MissingArgumentsStrategy;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Mvc\MvcEvent;

class MissingArgumentsStrategyTest extends TestCase
{
    /** @var string */
    protected $template = 'error/404-missing-arguments';
    /** @var MissingArgumentsStrategy */
    protected $strategy;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    protected function setUp()
    {
        parent::setUp();
        $this->strategy = new MissingArgumentsStrategy($this->template);
        $this->event    = $this->getMock('Zend\\Mvc\\MvcEvent');
    }

    public function testShouldListenToTheDispatchErrorEvent()
    {
        $callback = $this->getMockBuilder('Zend\\Stdlib\\CallbackHandler')
                         ->disableOriginalConstructor()
                         ->getMock();

        $events = $this->getMockForAbstractClass('Zend\\EventManager\\EventManagerInterface');
        $events->expects($this->once())
               ->method('attach')
               ->with(MvcEvent::EVENT_DISPATCH_ERROR, array($this->strategy, 'onDispatchError'), -100)
               ->will($this->returnValue($callback));

        $this->strategy->attach($events);

        $events->expects($this->once())
               ->method('detach')
               ->with($callback);

        $this->strategy->detach($events);
    }

    public function testShouldIgnoreAlreadyHandledEvents()
    {
        $response = $this->getMockForAbstractClass('Zend\\Stdlib\\ResponseInterface');
        $this->event->expects($this->once())
                    ->method('getResult')
                    ->will($this->returnValue($response));

        $this->event->expects($this->never())
                    ->method('setResponse');
        $this->event->expects($this->never())
                    ->method('getViewModel');

        $this->strategy->onDispatchError($this->event);
    }

    public function testShouldNotHandleNonHttpResponses()
    {
        $response = $this->getMockForAbstractClass('Zend\\Stdlib\\ResponseInterface');
        $this->event->expects($this->once())
                    ->method('getResponse')
                    ->will($this->returnValue($response));

        $this->event->expects($this->never())
                    ->method('setResponse');
        $this->event->expects($this->never())
                    ->method('getViewModel');

        $this->strategy->onDispatchError($this->event);
    }

    public function testShouldIgnoreOtherErrors()
    {
        $response = $this->getMock('Zend\\Http\\Response');
        $this->event->expects($this->once())
                    ->method('getResponse')
                    ->will($this->returnValue($response));
        $this->event->expects($this->once())
                    ->method('getError')
                    ->will($this->returnValue('anotherErrorConstant'));

        $this->event->expects($this->never())
                    ->method('setResponse');
        $this->event->expects($this->never())
                    ->method('getViewModel');

        $this->strategy->onDispatchError($this->event);
    }

    public function testShouldAddAViewModel()
    {
        $this->event->expects($this->once())
                    ->method('getError')
                    ->will($this->returnValue(ArgumentsGuard::ERROR));

        $viewModel = $this->getMockForAbstractClass('Zend\\View\\Model\\ModelInterface');
        $viewModel->expects($this->once())
                  ->method('addChild')
                  ->with($this->isInstanceOf('Zend\\View\\Model\\ViewModel'));
        $this->event->expects($this->once())
                    ->method('getViewModel')
                    ->will($this->returnValue($viewModel));

        $this->strategy->onDispatchError($this->event);
    }

    public function testShouldSetAResponse()
    {
        $this->event->expects($this->once())
                    ->method('getError')
                    ->will($this->returnValue(ArgumentsGuard::ERROR));

        $viewModel = $this->getMockForAbstractClass('Zend\\View\\Model\\ModelInterface');
        $this->event->expects($this->once())
                    ->method('getViewModel')
                    ->will($this->returnValue($viewModel));

        $this->event->expects($this->once())
                    ->method('setResponse')
                    ->with($this->isInstanceOf('Zend\\Http\\Response'));

        $this->strategy->onDispatchError($this->event);
    }

    public function testShouldSetTheResponseStatusCodeTo404()
    {
        $response = $this->getMock('Zend\\Http\\Response');
        $this->event->expects($this->once())
                    ->method('getResponse')
                    ->will($this->returnValue($response));
        $this->event->expects($this->once())
                    ->method('getError')
                    ->will($this->returnValue(ArgumentsGuard::ERROR));
        $this->event->expects($this->once())
                    ->method('getViewModel')
                    ->will($this->returnValue($this->getMockForAbstractClass('Zend\\View\\Model\\ModelInterface')));

        $response->expects($this->once())
                 ->method('setStatusCode')
                 ->with(404);
        $this->event->expects($this->once())
                    ->method('setResponse')
                    ->with($response);

        $this->strategy->onDispatchError($this->event);
    }
}
