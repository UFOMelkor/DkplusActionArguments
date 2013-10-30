<?php
namespace DkplusActionArguments\Annotation;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;

/**
 * Configures the specifications.
 */
class AnnotationListener extends AbstractListenerAggregate
{
    /**
     * @param EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('configureMethod', array($this, 'configureGuard'));
        $this->listeners[] = $events->attach('configureArgument', array($this, 'configureMapping'));
    }

    /**
     * @param EventInterface $event
     * @return void
     */
    public function configureGuard(EventInterface $event)
    {
        $annotation = $event->getParam('annotation');
        $spec       = $event->getParam('spec');

        if (!$annotation instanceof Guard) {
            return;
        }

        $spec['guards'][] = array(
            'permission' => $annotation->permission,
            'assertion'  => $annotation->assertion,
        );
    }

    /**
     * @param EventInterface $event
     * @return void
     */
    public function configureMapping(EventInterface $event)
    {
        $annotation = $event->getParam('annotation');
        $spec       = $event->getParam('spec');

        if (!$annotation instanceof MapParam) {
            return;
        }

        if ($spec['name'] !== $annotation->to) {
            return;
        }

        $spec['source']    = $annotation->from
                           ? $annotation->from
                           : $annotation->to;
        $spec['converter'] = $annotation->using;
    }
}
