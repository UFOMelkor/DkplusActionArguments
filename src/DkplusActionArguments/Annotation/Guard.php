<?php
namespace DkplusActionArguments\Annotation;

/**
 * Defines a assertion [and its permission] that should receive the action arguments.
 *
 * @Annotation
 * @Target({"METHOD"})
 */
class Guard
{
    /** * @var string The service manager key of the assertion. */
    public $assertion;

    /** @var string Connects the assertion with a permission (optional, not supported by all guards). */
    public $permission;
}
