<?php
namespace DkplusActionArguments\Converter;

/**
 * Converts the scalar values from the route match into objects.
 */
abstract class Converter
{
    /**
     * @param mixed $value
     * @return mixed
     */
    abstract public function convert($value);
}
