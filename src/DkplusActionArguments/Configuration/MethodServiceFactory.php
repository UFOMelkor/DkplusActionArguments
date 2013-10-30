<?php
namespace DkplusActionArguments\Configuration;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MethodServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return MethodFactory
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MethodFactory($serviceLocator->get('DkplusActionArguments\Configuration\ArgumentFactory'));
    }
}
