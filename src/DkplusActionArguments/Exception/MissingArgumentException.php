<?php
namespace DkplusActionArguments\Exception;

/**
 * Should be thrown if some arguments cannot be converted with the current route parameters.
 */
class MissingArgumentException extends \BadMethodCallException
{
    /** @var string[] */
    private $argumentNames = array();

    /** @param string[] $argumentNames Names of the arguments that could not be found. */
    public function __construct(array $argumentNames)
    {
        parent::__construct(sprintf('These arguments could not be found: %s', implode(', ', $argumentNames)));
        $this->argumentNames = $argumentNames;
    }

    /** @return string[] Names of the arguments that could not be found */
    public function getArgumentNames()
    {
        return $this->argumentNames;
    }
}
