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
        $source    = $spec['source'];
        $position  = (int) $spec['position'];
        $name      = $spec['name'];
        $checker   = new ArgumentChecker($spec['type'], $spec['optional']);
        $converter = $this->converterFactory->create($spec['converter'], $spec['type']);

        return new Argument($source, $position, $name, $checker, $converter);
    }
}
