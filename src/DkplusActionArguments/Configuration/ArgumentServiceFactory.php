<?php
namespace DkplusActionArguments\Configuration;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ArgumentServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ArgumentFactory
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ArgumentFactory($serviceLocator->get('DkplusActionArguments\Converter\ConverterFactory'));
    }
}
 