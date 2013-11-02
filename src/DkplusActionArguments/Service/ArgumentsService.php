<?php
namespace DkplusActionArguments\Service;

use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Interface for other packages.
 */
class ArgumentsService
{
    /** @var MethodConfigurationProvider */
    private $provider;

    /** @var ServiceLocatorInterface */
    private $services;

    /**
     * @param MethodConfigurationProvider $provider
     * @param ServiceLocatorInterface     $services
     */
    public function __construct(MethodConfigurationProvider $provider, ServiceLocatorInterface $services)
    {
        $this->provider = $provider;
        $this->services = $services;
    }

    /**
     * @param string     $controllerClass
     * @param string     $method
     * @param RouteMatch $routeMatch
     * @return array
     */
    public function getArgumentsList($controllerClass, $method, RouteMatch $routeMatch)
    {
        return $this->provider->computeMethodConfiguration($controllerClass, $method)
                              ->assembleArgumentList($routeMatch);
    }

    /**
     * @param string     $controllerClass
     * @param string     $method
     * @param RouteMatch $routeMatch
     */
    public function injectArgumentsIntoAssertions($controllerClass, $method, RouteMatch $routeMatch)
    {
        $assertions = $this->getAssertions($controllerClass, $method);
        $arguments  = $this->getArgumentsMaps($controllerClass, $method, $routeMatch);

        foreach ($assertions as $each) {
            $this->injectArgumentsIntoAssertion($each, $arguments);
        }
    }

    /**
     * @param mixed $assertion
     * @param array $arguments
     */
    protected function injectArgumentsIntoAssertion($assertion, array $arguments)
    {
        foreach ($arguments as $name => $value) {
            $method = 'set' . ucfirst($name);
            if (method_exists($assertion, $method)) {
                $assertion->$method($value);
            }
        }
    }

    /**
     * @param string     $controllerClass
     * @param string     $method
     * @param RouteMatch $routeMatch
     * @return array
     */
    protected function getArgumentsMaps($controllerClass, $method, RouteMatch $routeMatch)
    {
        return $this->provider->computeMethodConfiguration($controllerClass, $method)
                              ->assembleArgumentMap($routeMatch);
    }

    /**
     * @param string $controllerClass
     * @param string $method
     * @return array
     */
    public function getAssertions($controllerClass, $method)
    {
        $assertionNames = $this->provider->computeMethodConfiguration($controllerClass, $method)
                                         ->getAssertions();
        $result = array();
        foreach ($assertionNames as $each) {
            $result[] = $this->services->get($each);
        }
        return $result;
    }

    /**
     * @param string     $controllerClass
     * @param string     $method
     * @param RouteMatch $routeMatch
     * @return string[]
     */
    public function getNamesOfMissingArguments($controllerClass, $method, RouteMatch $routeMatch)
    {
        $method    = $this->provider->computeMethodConfiguration($controllerClass, $method);
        $arguments = $method->assembleArgumentList($routeMatch);

        return $method->getNamesOfMissingArguments($arguments);
    }
}
