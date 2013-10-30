<?php
namespace DkplusActionArguments\Converter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConverterServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ConverterFactory
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ConverterFactory($serviceLocator);
    }
}
