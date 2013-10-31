<?php
namespace DkplusActionArguments\View;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MissingArgumentsStrategyFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $options \DkplusActionArguments\Options\ModuleOptions */
        $options = $serviceLocator->get('DkplusActionArguments\Options\ModuleOptions');
        return new MissingArgumentsStrategy($options->getMissingArgumentsTemplate());
    }
}
