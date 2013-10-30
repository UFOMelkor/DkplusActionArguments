<?php
namespace DkplusActionArguments\Guard;

use DkplusActionArguments\Service\ArgumentsService;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Mvc\MvcEvent;

/**
 * Does not protect directly, but injects the action arguments into the given assertions, so other guards
 * (e.g. BjyAuthorize\Guard\Controller) can handle protection more accurate.
 * You have to ensure that the given assertions are connected to proper resources.
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize/blob/master/src/BjyAuthorize/Guard/Controller.php
 * @link https://github.com/bjyoungblood/BjyAuthorize#configuration
 */
class AssertionGuard extends AbstractGuard
{
    /** @var ArgumentsService */
    protected $argumentsService;

    /**
     * @param ControllerManager $controllerManager
     * @param ArgumentsService  $argumentsService
     */
    public function __construct(ControllerManager $controllerManager, ArgumentsService $argumentsService)
    {
        parent::__construct($controllerManager);
        $this->argumentsService = $argumentsService;
    }

    /**
     * @param EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), -900);
    }

    /**
     * @param MvcEvent $event
     * @return void
     */
    public function onRoute(MvcEvent $event)
    {
        $controllerClass = $this->getControllerClass($event->getRouteMatch());
        $method          = $this->getActionMethod($controllerClass, $event->getRouteMatch());

        if (!$this->isControllerAccepted($controllerClass)) {
            return;
        }

        $this->argumentsService->injectArgumentsIntoAssertions($controllerClass, $method, $event->getRouteMatch());
    }
}
