<?php
namespace DkplusActionArguments\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MethodConfigurationProviderFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return MethodConfigurationProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $annotationBuilder    = $serviceLocator->get('DkplusActionArguments\\Annotation\\AnnotationBuilder');
        $configurationFactory = $serviceLocator->get('DkplusActionArguments\\Configuration\\MethodFactory');
        $writer               = $serviceLocator->get('DkplusActionArguments\\Specification\\Writer');
        $config               = $serviceLocator->get('Config');
        $specifications       = isset($config['DkplusActionArguments']['controllers'])
                              ? $config['DkplusActionArguments']['controllers']
                              : array();
        return new MethodConfigurationProvider($annotationBuilder, $configurationFactory, $writer, $specifications);
    }
}
