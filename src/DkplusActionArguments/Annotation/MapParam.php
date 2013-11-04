<?php
namespace DkplusActionArguments\Annotation;

/**
 * Specifies how to map a route match value to an object.
 *
 * @Annotation
 * @Target({"METHOD"})
 */
class MapParam
{
    /**
     * @var mixed How to find the converted argument (optional). If just a value is given, it will search for an
     *            object-repository for the argument and call this method on the repository.
     *            If two values are given, it will look up in the service manager for the first value
     *            und uses the second value as method.
     */
    public $using;

    /**
     * @var string If the name of the parameter grabbed from the route match is not equal to the action-argument you
     *             can configure this here (optional).
     */
    public $from;

    /**
     * @var string Name of the parameter that will be configured.
     */
    public $to;
}
