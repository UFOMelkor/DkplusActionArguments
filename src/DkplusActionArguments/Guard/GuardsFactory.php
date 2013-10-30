<?php
namespace DkplusActionArguments\Guard;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GuardsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AbstractGuard[]
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $options \DkplusActionArguments\Options\ModuleOptions */
        $options = $serviceLocator->get('DkplusActionArguments\Options\ModuleOptions');
        $result  = array();
        foreach ($options->getGuards() as $each) {
            $result[] = $serviceLocator->get($each);
        }
        return $result;
    }
}
