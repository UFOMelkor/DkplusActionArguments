<?php
namespace DkplusActionArguments\Specification;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WriterFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Writer
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $options \DkplusActionArguments\Options\ModuleOptions */
        $options = $serviceLocator->get('DkplusActionArguments\Options\ModuleOptions');
        return new Writer($options->getCacheFilePath());
    }
}
