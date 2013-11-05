<?php
namespace DkplusActionArgumentsTestModule;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return array(
            'router' => array(
                'routes' => array(
                    'view' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'       => '/view/:user',
                            'constraints' => array(
                                'user' => '[1-9][0-9]*'
                            ),
                            'defaults'    => array(
                                'controller' => 'Index',
                                'action'     => 'view'
                            )
                        )
                    ),
                    'view-all' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/view-all',
                            'defaults' => array(
                                'controller' => 'Index',
                                'action'     => 'view-all'
                            )
                        )
                    ),
                )
            ),
            'service_manager' => array(
                'invokables' => array(
                    'AclAssertion' => 'DkplusActionArgumentsTestModule\Assertion\AclAssertion'
                )
            ),
            'controllers' => array(
                'invokables' => array(
                    'Index' => 'DkplusActionArgumentsTestModule\Controller\IndexController'
                )
            ),
            'view_manager' => array(
                'display_not_found_reason' => true,
                'display_exceptions'       => true,
                'doctype'                  => 'HTML5',
                'not_found_template'       => 'error/404',
                'exception_template'       => 'error/index',
                'template_map' => array(
                    'layout/layout' => __DIR__ . '/../../view/layout/layout.phtml',
                    'error/404'     => __DIR__ . '/../../view/error/404.phtml',
                    'error/index'   => __DIR__ . '/../../view/error/index.phtml',
                    'dkplus-action-arguments-test-module/index/view'
                    => __DIR__ . '/../../view/dkplus-action-arguments-test-module/index/view.phtml',
                    'dkplus-action-arguments-test-module/index/view-all'
                    => __DIR__ . '/../../view/dkplus-action-arguments-test-module/index/view-all.phtml',
                ),
                'template_path_stack' => array(
                    __DIR__ . '/../../view',
                ),
            )
        );
    }
}
