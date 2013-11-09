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
    /** @var string */
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
     * @param string          $source
     * @param int             $position
     * @param string          $name
     * @param ArgumentChecker $checker
     * @param Converter       $converter
     */
    public function __construct($source, $position, $name, ArgumentChecker $checker, Converter $converter = null)
    {
        $this->source    = $source;
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
        $value = $routeMatch->getParam($this->source);
        if ($this->converter) {
            $value = $this->converter->apply($value);
        }
        return $value;
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
