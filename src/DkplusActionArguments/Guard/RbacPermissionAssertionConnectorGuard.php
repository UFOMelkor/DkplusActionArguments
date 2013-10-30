<?php
namespace DkplusActionArguments\Guard;

use DkplusActionArguments\Service\ArgumentsService;
use DkplusActionArguments\Service\RbacAssertionPermissionConnector;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Mvc\MvcEvent;

/**
 * Does not protect directly, but injects the action arguments into the given assertions and connects the assertions
 * to the given permissions, so other guards will use this assertions in case checking the permission.
 * You have to ensure that the given permissions exist.
 *
 * @link https://github.com/spiffyjr/spiffy-authorize/blob/master/doc/Guards.md#routeguard
 * @link https://github.com/ZF-Commons/ZfcRbac#firewalls
 */
class RbacPermissionAssertionConnectorGuard extends AbstractGuard
{
    /** @var ArgumentsService */
    protected $guardService;
    /** @var RbacAssertionPermissionConnector */
    protected $connector;

    /**
     * @param ControllerManager                $controllerManager
     * @param ArgumentsService                 $guardService
     * @param RbacAssertionPermissionConnector $connector
     */
    public function __construct(
        ControllerManager $controllerManager,
        ArgumentsService $guardService,
        RbacAssertionPermissionConnector $connector
    ) {
        parent::__construct($controllerManager);
        $this->guardService = $guardService;
        $this->connector    = $connector;
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

        $this->guardService->injectArgumentsIntoAssertions($controllerClass, $method, $event->getRouteMatch());

        foreach ($this->guardService->getAssertions($controllerClass, $method) as $permission => $assertion) {
            $this->connector->connect($permission, $assertion);
        }
    }
}
