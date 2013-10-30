<?php
namespace DkplusActionArguments\Guard;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ZfcRbacGuardFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return RbacPermissionAssertionConnectorGuard
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RbacPermissionAssertionConnectorGuard(
            $serviceLocator->get('ControllerLoader'),
            $serviceLocator->get('DkplusActionArguments\Service\ArgumentsService'),
            $serviceLocator->get('DkplusActionArguments\Service\ZfcRbacServiceDecorator')
        );
    }
}
