<?php
namespace DkplusActionArguments\Converter;

/**
 * Converter that can use every method to convert the value.
 */
class CallbackConverter extends Converter
{
    /** @var callable */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function convert($value)
    {
        return call_user_func($this->callback, $value);
    }
}
