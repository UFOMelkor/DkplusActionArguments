<?php
namespace DkplusActionArguments\Guard;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ArgumentsGuardFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ArgumentsGuard
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ArgumentsGuard(
            $serviceLocator->get('ControllerLoader'),
            $serviceLocator->get('DkplusActionArguments\Service\ArgumentsService')
        );
    }
}
