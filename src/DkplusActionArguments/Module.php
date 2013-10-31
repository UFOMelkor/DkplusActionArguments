<?php
namespace DkplusActionArguments;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements
    AutoloaderProviderInterface,
    BootstrapListenerInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    /**
     * @param EventInterface $event
     * @return array
     */
    public function onBootstrap(EventInterface $event)
    {
        /** @var \Zend\Mvc\Application $application */
        $application = $event->getTarget();
        $services    = $application->getServiceManager();
        $events      = $application->getEventManager();

        $guards = $services->get('DkplusActionArguments\Guard\Guards');
        foreach ($guards as $each) {
            $events->attach($each);
        }

        $missingArgsStrategy = $services->get('DkplusActionArguments\View\MissingArgumentsStrategy');
        $events->attach($missingArgsStrategy);

        $annotationListener = $services->get('DkplusActionArguments\Annotation\AnnotationListener');
        $events->attach($annotationListener);
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return include __DIR__ . '/../../config/service.config.php';
    }
}
