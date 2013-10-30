<?php
namespace DkplusActionArguments\Configuration;

/**
 * Checks whether a value exists or not.
 */
class ArgumentChecker
{
    /** @var string */
    protected $type;

    /** @var boolean */
    protected $isOptional;

    /**
     * @param string $type
     * @param boolean $isOptional
     */
    public function __construct($type, $isOptional)
    {
        $this->type       = $type;
        $this->isOptional = $isOptional;
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    public function isMissing($value)
    {
        if ($this->isOptional
            && $value === null
        ) {
            return false;
        }

        if (class_exists($this->type)) {
            return !$value instanceof $this->type;
        }

        return gettype($value) != $this->type;
    }
}
