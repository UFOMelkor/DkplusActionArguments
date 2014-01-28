<?php
namespace DkplusActionArguments\Configuration;

use DkplusActionArguments\Converter\Converter;
use DkplusActionArguments\Guard\Guard;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Argument configuration.
 */
class Argument
{
    /** @var string[] */
    private $source;
    /** @var int */
    private $position;
    /** @var string */
    private $name;
    /** @var Converter */
    private $converter;
    /** @var ArgumentChecker */
    private $checker;

    /**
     * @param string|string[] $source
     * @param int             $position
     * @param string          $name
     * @param ArgumentChecker $checker
     * @param Converter       $converter
     */
    public function __construct($source, $position, $name, ArgumentChecker $checker, Converter $converter = null)
    {
        $this->source    = (array) $source;
        $this->position  = $position;
        $this->name      = $name;
        $this->checker   = $checker;
        $this->converter = $converter;
    }

    /** @return string */
    public function getName()
    {
        return $this->name;
    }

    /** @return int */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param RouteMatch $routeMatch
     * @return mixed
     */
    public function grabValue(RouteMatch $routeMatch)
    {
        $values = array();
        foreach ($this->source as $eachSource) {
            $values[] = $routeMatch->getParam($eachSource);
        }

        if (!$this->converter) {
            return array_shift($values);
        }

        return $this->converter->apply($values);
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    public function isMissing($value)
    {
        return $this->checker->isMissing($value);
    }
}
