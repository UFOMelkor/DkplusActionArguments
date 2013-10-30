<?php
namespace DkplusActionArguments\Configuration;

use Zend\Mvc\Router\RouteMatch;

/**
 * Method configuration.
 */
class Method
{
    /** @var mixed[] */
    private $assertions = array();

    /** @var Argument[] */
    private $arguments = array();

    /** @var array */
    private $runtimeCache = array();

    /**
     * @param mixed       $assertion
     * @param string|null $permission
     * @return void
     */
    public function addAssertion($assertion, $permission = null)
    {
        if ($permission === null) {
            $this->assertions[] = $assertion;
            return;
        }
        $this->assertions[$permission] = $assertion;
    }

    /** @return array */
    public function getAssertions()
    {
        return $this->assertions;
    }

    /**
     * @param Argument $argument
     * @return void
     */
    public function addArgument(Argument $argument)
    {
        $this->arguments[$argument->getPosition()] = $argument;
    }

    /**
     * @param RouteMatch $routeMatch
     * @return array
     */
    public function assembleArgumentList(RouteMatch $routeMatch)
    {
        return array_values($this->assembleArgumentMap($routeMatch));
    }

    /**
     * @param RouteMatch $routeMatch
     * @return array
     */
    public function assembleArgumentMap(RouteMatch $routeMatch)
    {
        $key = serialize($routeMatch->getParams());

        if (!array_key_exists($key, $this->runtimeCache)) {
            $this->runtimeCache[$key] = array();
            foreach ($this->arguments as $each) {
                $this->runtimeCache[$key][$each->getName()] = $each->grabValue($routeMatch);
            }
        }

        return $this->runtimeCache[$key];
    }

    /**
     * @param array $values
     * @return string[]
     */
    public function getMissingArgumentNames(array $values)
    {
        $result = array();
        foreach ($this->arguments as $position => $argument) {
            if ($argument->isMissing($values[$position])) {
                $result[] = $argument->getName();
            }
        }
        return $result;
    }
}
