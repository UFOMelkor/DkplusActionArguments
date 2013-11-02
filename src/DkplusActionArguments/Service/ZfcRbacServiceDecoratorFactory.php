<?php
namespace DkplusActionArguments\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcRbac\Service\RbacFactory;

class ZfcRbacServiceDecoratorFactory extends RbacFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ZfcRbacServiceDecorator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $rbacService = parent::createService($serviceLocator);
        return new ZfcRbacServiceDecorator($rbacService);
    }
}
