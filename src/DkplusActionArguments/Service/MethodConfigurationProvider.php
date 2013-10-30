<?php
namespace DkplusActionArguments\Service;

use DkplusActionArguments\Annotation\AnnotationBuilder;
use DkplusActionArguments\Configuration\Method;
use DkplusActionArguments\Configuration\MethodFactory;
use DkplusActionArguments\Specification\Writer;

/**
 * Class MethodConfigurationProvider
 *
 * @package DkplusActionArguments\Service
 */
class MethodConfigurationProvider
{
    /** @var \DkplusActionArguments\Annotation\AnnotationBuilder */
    private $annotationBuilder;
    /** @var MethodFactory */
    private $configurationFactory;
    /** @var array */
    private $runtimeCache = array();
    /** @var array */
    private $specifications = array();
    /** @var Writer */
    private $writer = array();

    /**
     * @param AnnotationBuilder    $annotationBuilder
     * @param MethodFactory $configurationFactory
     * @param Writer               $writer
     * @param array                $specifications
     */
    public function __construct(
        AnnotationBuilder $annotationBuilder,
        MethodFactory $configurationFactory,
        Writer $writer,
        array $specifications
    ) {
        $this->annotationBuilder    = $annotationBuilder;
        $this->configurationFactory = $configurationFactory;
        $this->writer               = $writer;
        $this->specifications       = $specifications;
    }

    /**
     * @param string $classOrObject
     * @param string $method
     * @return Method
     */
    public function computeMethodConfiguration($classOrObject, $method)
    {
        if (is_object($classOrObject)) {
            $classOrObject = get_class($classOrObject);
        }

        if (empty($this->specifications[$classOrObject])) {
            $this->specifications[$classOrObject] = array();
        }

        if (empty($this->specifications[$classOrObject][$method])) {
            $this->specifications[$classOrObject][$method] = $this->annotationBuilder->getMethodSpecification(
                $classOrObject,
                $method
            );
            $this->writer->writeSpecification($this->specifications);
        }

        $key = $classOrObject . '::' . $method;

        if (empty($this->runtimeCache[$key])) {
            $this->runtimeCache[$key] = $this->configurationFactory->createConfiguration(
                $this->specifications[$classOrObject][$method]
            );
        }

        return $this->runtimeCache[$key];
    }
}
