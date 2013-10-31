<?php
namespace DkplusActionArguments\Guard;

use DkplusActionArguments\Exception\MissingArgumentException;
use DkplusActionArguments\Service\ArgumentsService;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Mvc\MvcEvent;

/**
 * Checks for missing arguments.
 * If one or more missing arguments exist, it will trigger a event that can be caught by a listener.
 */
class ArgumentsGuard extends AbstractGuard
{
    /** Marker for missing arguments */
    const ERROR = 'error-missing-arguments';
    /** @var ArgumentsService */
    protected $guardService;

    /**
     * @param ControllerManager $controllerManager
     * @param ArgumentsService  $guardService
     */
    public function __construct(ControllerManager $controllerManager, ArgumentsService $guardService)
    {
        parent::__construct($controllerManager);
        $this->guardService = $guardService;
    }

    /**
     * @param EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), -800);
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

        $missingArguments = $this->guardService->getMissingArgumentNames(
            $controllerClass, $method,
            $event->getRouteMatch()
        );

        if (count($missingArguments) == 0) {
            return;
        }

        $event->setError(static::ERROR);
        $event->setParam('route', $event->getRouteMatch()->getMatchedRouteName());
        $event->setParam('controller', $controllerClass);
        $event->setParam('action', $event->getRouteMatch()->getParam('action'));
        $event->setParam('exception', new MissingArgumentException($missingArguments));
        $event->setParam('arguments', $missingArguments);

        /* @var $app \Zend\Mvc\Application */
        $app = $event->getTarget();
        $app->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
    }
}