<?php
namespace DkplusActionArguments\Exception;

/**
 * Should be thrown if the specification cannot be written into the file.
 */
class SpecificationWriteError extends \BadMethodCallException
{
    /**
     * @param string $filePath File to write into
     */
    public function __construct($filePath)
    {
        parent::__construct(sprintf('Could not write specification to %s', $filePath));
    }
}
