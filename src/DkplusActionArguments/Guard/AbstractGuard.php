<?php
namespace DkplusActionArguments\Guard;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Mvc\Router\RouteMatch;

/**
 * Guards protect routes / controllers by hooking into the mvc workflow.
 */
abstract class AbstractGuard extends AbstractListenerAggregate
{
    /** @var ControllerManager */
    private $controllerManager;

    /** @param ControllerManager $controllerManager */
    public function __construct(ControllerManager $controllerManager)
    {
        $this->controllerManager = $controllerManager;
    }

    /**
     * @param string $controllerClass
     * @return boolean
     */
    protected function isControllerAccepted($controllerClass)
    {
        return is_subclass_of($controllerClass, 'DkplusActionArguments\Controller\AbstractActionController');
    }

    /**
     * @param RouteMatch $routeMatch
     * @return string
     */
    protected function getControllerClass(RouteMatch $routeMatch)
    {
        $controller = $this->controllerManager->get($routeMatch->getParam('controller'));
        return get_class($controller);
    }

    /**
     * @param string     $controllerClass
     * @param RouteMatch $routeMatch
     * @return string
     */
    protected function getActionMethod($controllerClass, RouteMatch $routeMatch)
    {
        return call_user_func(array($controllerClass, 'getMethodFromAction'), $routeMatch->getParam('action'));
    }
}
