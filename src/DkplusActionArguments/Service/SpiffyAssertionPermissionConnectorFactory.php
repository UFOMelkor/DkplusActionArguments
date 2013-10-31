<?php
namespace DkplusActionArguments\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SpiffyAssertionPermissionConnectorFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return SpiffyAssertionPermissionConnector
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SpiffyAssertionPermissionConnector($serviceLocator->get('SpiffyAuthorize\Service\RbacService'));
    }
}
