<?php
namespace DkplusActionArguments\Configuration;

use DkplusActionArguments\Converter\ConverterFactory;

/**
 * Creates argument configurations from specifications.
 */
class ArgumentFactory
{
    /** @var  ConverterFactory */
    private $converterFactory;

    /**
     * @param ConverterFactory $converterFactory
     */
    public function __construct(ConverterFactory $converterFactory)
    {
        $this->converterFactory = $converterFactory;
    }

    /**
     * @param array $spec
     * @return Argument
     */
    public function createConfiguration(array $spec)
    {
        $source        = isset($spec['source']) ? $spec['source'] : $spec['name'];
        $position      = (int) $spec['position'];
        $name          = $spec['name'];
        $checker       = new ArgumentChecker($spec['type'], $spec['optional']);
        $converterName = isset($spec['converter']) ? $spec['converter'] : null;
        $converter     = $this->converterFactory->create($converterName, $spec['type']);

        return new Argument($source, $position, $name, $checker, $converter);
    }
}
