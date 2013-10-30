<?php
namespace DkplusActionArguments\Annotation;

use DkplusActionArguments\Configuration\MethodFactory;
use Zend\Code\Annotation\AnnotationCollection;
use Zend\Code\Annotation\AnnotationManager;
use Zend\Code\Annotation\Parser\ParserInterface;
use Zend\Code\Reflection\MethodReflection;
use Zend\EventManager\EventManagerInterface;
use Zend\Stdlib\ArrayObject;
use Zend\Stdlib\ArrayUtils;

/**
 * Fetches specification from annotations and creates a configuration.
 */
class AnnotationBuilder
{
    /** @var AnnotationManager */
    protected $annotationManager;

    /** @var EventManagerInterface */
    protected $events;

    /** @var array Default annotations to register */
    protected $defaultAnnotations = array('Guard', 'MapParam');

    /**
     * @param MethodFactory         $configurationFactory
     * @param EventManagerInterface $events
     * @param AnnotationManager     $annotationManager
     * @param ParserInterface       $parser
     */
    public function __construct(
        EventManagerInterface $events,
        AnnotationManager $annotationManager,
        ParserInterface $parser
    ) {
        $this->events               = $events;
        $this->configureAnnotationManager($annotationManager, $parser);
    }

    /**
     * @param AnnotationManager $annotationManager
     * @param ParserInterface   $parser
     * @return void
     */
    protected function configureAnnotationManager(AnnotationManager $annotationManager, ParserInterface $parser)
    {
        foreach ($this->defaultAnnotations as $annotationName) {
            $class = __NAMESPACE__ . '\\' . $annotationName;
            $parser->registerAnnotation($class);
        }
        $annotationManager->attach($parser);
        $this->annotationManager = $annotationManager;
    }

    /**
     * @param string|object $classNameOrObject
     * @param string        $method
     * @return array
     */
    public function getMethodSpecification($classNameOrObject, $method)
    {
        $reflection = new MethodReflection($classNameOrObject, $method);
        $methodSpec = new ArrayObject();
        $this->configureMethod($methodSpec, $reflection);

        return ArrayUtils::iteratorToArray($methodSpec);
    }

    /**
     * @param ArrayObject      $spec
     * @param MethodReflection $reflection
     * @return void
     */
    protected function configureMethod(ArrayObject $spec, MethodReflection $reflection)
    {
        $annotations = $reflection->getAnnotations($this->annotationManager);

        $spec['guards'] = array();
        foreach ($annotations as $each) {
            $this->events->trigger(__FUNCTION__, $this, array('spec' => $spec, 'annotation' => $each));
        }

        $spec['arguments'] = array();
        foreach ($reflection->getParameters() as $each) {

            $argumentSpec             = new ArrayObject();
            $argumentSpec['type']     = $each->getType();
            $argumentSpec['default']  = $each->isOptional() ? $each->getDefaultValue() : null;
            $argumentSpec['optional'] = $each->isOptional();
            $argumentSpec['position'] = $each->getPosition();
            $argumentSpec['name']     = $each->getName();
            $spec['arguments'][]      = $argumentSpec;

            $this->configureArgument($argumentSpec, $annotations, $each->getName());
        }
    }

    /**
     * @param ArrayObject          $spec
     * @param AnnotationCollection $annotations
     * @param string               $argumentName
     * @return void
     */
    protected function configureArgument(ArrayObject $spec, AnnotationCollection $annotations, $argumentName)
    {
        foreach ($annotations as $each) {
            $this->events->trigger(
                 __FUNCTION__,
                 $this,
                 array('name' => $argumentName, 'spec' => $spec, 'annotation' => $each)
            );
        }
    }
}
