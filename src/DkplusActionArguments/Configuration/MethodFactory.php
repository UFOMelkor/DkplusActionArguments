<?php
namespace DkplusActionArguments\Configuration;

/**
 * Creates method configurations from specifications.
 */
class MethodFactory
{
    /** @var ArgumentFactory */
    private $argumentFactory;

    /**
     * @param ArgumentFactory $argumentFactory
     */
    public function __construct(ArgumentFactory $argumentFactory)
    {
        $this->argumentFactory = $argumentFactory;
    }

    /**
     * @param array $spec
     * @return Method
     */
    public function createConfiguration(array $spec)
    {
        $result = new Method();

        if (isset($spec['guards'])) {
            $this->addGuardsTo($result, $spec['guards']);
        }

        if (isset($spec['arguments'])) {
            $this->addArgumentsTo($result, $spec['arguments']);
        }

        return $result;
    }

    /**
     * @param Method $config
     * @param array  $specs
     * @return void
     */
    private function addGuardsTo(Method $config, array $specs)
    {
        foreach ($specs as $each) {
            $config->addAssertion($each['assertion'], $each['permission']);
        }
    }

    /**
     * @param Method $config
     * @param array  $specs
     * @return void
     */
    private function addArgumentsTo(Method $config, array $specs)
    {
        foreach ($specs as $each) {
            $config->addArgument($this->argumentFactory->createConfiguration($each));
        }
    }
}
