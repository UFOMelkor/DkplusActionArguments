<?php
namespace DkplusActionArguments\View;

use DkplusActionArguments\Guard\ArgumentsGuard;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;

/**
 * Dispatch error handler, catches exceptions related with missing arguments and configures the application response.
 */
class MissingArgumentsStrategy extends AbstractListenerAggregate
{
    /** @var string */
    protected $template;

    /**
     * @param $template Name of the template to use.
     */
    public function __construct($template)
    {
        $this->template = $template;
    }

    /**
     * @param string $template
     * @return void
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /** @return string */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), -100);
    }

    /**
     * @param MvcEvent $event
     * @return void
     */
    public function onDispatchError(MvcEvent $event)
    {
        $result   = $event->getResult();
        $response = $event->getResponse();

        if ($result instanceof Response) {
            return;
        }

        if ($response
            && ! $response instanceof HttpResponse
        ) {
            return;
        }

        if ($event->getError() != ArgumentsGuard::ERROR) {
            return;
        }


        $viewVariables = array(
            'error'      => $event->getParam('error'),
            'controller' => $event->getParam('controller'),
            'action'     => $event->getParam('action'),
            'route'      => $event->getParam('route'),
            'arguments'  => $event->getParam('arguments'),
            //'reason'     => $event->getParam('exception')->getMessage()
        );

        $model = new ViewModel($viewVariables);
        $model->setTemplate($this->getTemplate());
        $event->getViewModel()->addChild($model);

        $response = $response ? : new HttpResponse();
        $response->setStatusCode(404);
        $event->setResponse($response);
        //$event->setResult('my-message');
        //$event->stopPropagation(true);
    }
}
 