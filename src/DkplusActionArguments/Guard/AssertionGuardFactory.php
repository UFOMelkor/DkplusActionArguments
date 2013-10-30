<?php
namespace DkplusActionArguments\Guard;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AssertionGuardFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AssertionGuard
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AssertionGuard(
            $serviceLocator->get('ControllerLoader'),
            $serviceLocator->get('DkplusActionArguments\Service\ArgumentsService')
        );
    }
}
