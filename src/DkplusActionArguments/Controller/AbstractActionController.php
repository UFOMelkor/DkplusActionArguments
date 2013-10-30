<?php
namespace DkplusActionArguments\Controller;

use DkplusActionArguments\ArgumentCollection;
use Zend\Mvc\Controller\AbstractActionController as BaseController;
use Zend\Mvc\Exception;
use Zend\Mvc\MvcEvent;

/**
 * Provides the ability to use action arguments.
 */
abstract class AbstractActionController extends BaseController
{
    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        if (!$routeMatch) {
            /**
             * @todo Determine requirements for when route match is missing.
             *       Potentially allow pulling directly from request metadata?
             */
            throw new Exception\DomainException('Missing route matches; unsure how to retrieve action');
        }

        $action = $routeMatch->getParam('action', 'not-found');
        $method = static::getMethodFromAction($action);

        if (!method_exists($this, $method)) {
            $method = 'notFoundAction';
        }

        $actionResponse = $this->dispatchAction($method, $e);

        $e->setResult($actionResponse);

        return $actionResponse;
    }

    /**
     * @param string   $method
     * @param MvcEvent $event
     * @return mixed
     */
    protected function dispatchAction($method, MvcEvent $event)
    {
        /* @var $service \DkplusActionArguments\Service\ArgumentsService */
        $service   = $this->getServiceLocator()->get('DkplusActionArguments\Service\ArgumentsService');
        $arguments = $service->getArgumentsList($this, $method, $event->getRouteMatch());
        return call_user_func_array(array($this, $method), $arguments);
    }
}
