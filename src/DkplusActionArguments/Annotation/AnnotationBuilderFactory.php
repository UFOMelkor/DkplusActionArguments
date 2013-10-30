<?php
namespace DkplusActionArguments\Annotation;

use Zend\Code\Annotation\AnnotationManager;
use Zend\Code\Annotation\Parser\DoctrineAnnotationParser;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AnnotationBuilderFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AnnotationBuilder
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $annotationManager = new AnnotationManager();
        $parser            = new DoctrineAnnotationParser();
        $events            = $serviceLocator->get('Application')->getEventManager();
        return new AnnotationBuilder(
            $events,
            $annotationManager,
            $parser
        );
    }
}
