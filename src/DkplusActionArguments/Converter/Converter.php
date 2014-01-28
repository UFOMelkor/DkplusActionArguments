<?php
namespace DkplusActionArguments\Converter;

/**
 * Converts the scalar values from the route match into objects.
 */
abstract class Converter
{
    /**
     * @param array $values
     * @return mixed
     */
    abstract public function apply(array $values);
}
