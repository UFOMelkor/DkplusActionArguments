<?php
return array(
    'aliases' => array(
        'DkplusActionArguments\Guard\AclGuard' => 'DkplusActionArguments\Guard\AssertionGuard',
    ),
    'invokables' => array(
        'DkplusActionArguments\Annotation\AnnotationListener' => 'DkplusActionArguments\Annotation\AnnotationListener'
    ),
    'factories' => array(
        'DkplusActionArguments\Annotation\AnnotationBuilder'
            => 'DkplusActionArguments\Annotation\AnnotationBuilderFactory',

        'DkplusActionArguments\Configuration\MethodFactory'
            => 'DkplusActionArguments\Configuration\MethodServiceFactory',
        'DkplusActionArguments\Configuration\ArgumentFactory'
            => 'DkplusActionArguments\Configuration\ArgumentServiceFactory',

        'DkplusActionArguments\Converter\ConverterFactory' => 'DkplusActionArguments\Converter\ConverterServiceFactory',

        'DkplusActionArguments\Guard\AssertionGuard'       => 'DkplusActionArguments\Guard\AssertionGuardFactory',
        'DkplusActionArguments\Guard\ArgumentsGuard'       => 'DkplusActionArguments\Guard\ArgumentsGuardFactory',
        'DkplusActionArguments\Guard\Guards'               => 'DkplusActionArguments\Guard\GuardsFactory',
        'DkplusActionArguments\Guard\SpiffyAuthorizeGuard' => 'DkplusActionArguments\Guard\SpiffyAuthorizeGuardFactory',
        'DkplusActionArguments\Guard\ZfcRbacGuard'         => 'DkplusActionArguments\Guard\ZfcRbacGuardFactory',

        'DkplusActionArguments\Options\ModuleOptions' => 'DkplusActionArguments\Options\ModuleOptionsFactory',

        'DkplusActionArguments\Service\ArgumentsService'
            => 'DkplusActionArguments\Service\ArgumentsServiceFactory',
        'DkplusActionArguments\Service\MethodConfigurationProvider'
            => 'DkplusActionArguments\Service\MethodConfigurationProviderFactory',
        'DkplusActionArguments\Service\SpiffyAssertionPermissionConnector'
            => 'DkplusActionArguments\Service\SpiffyAssertionPermissionConnectorFactory',
        'DkplusActionArguments\Service\ZfcRbacServiceDecorator'
            => 'DkplusActionArguments\Service\ZfcRbacServiceDecoratorFactory',

        'DkplusActionArguments\Specification\Writer'
            => 'DkplusActionArguments\Specification\WriterFactory',

        'DkplusActionArguments\View\MissingArgumentsStrategy'
            => 'DkplusActionArguments\View\MissingArgumentsStrategyFactory',

        'ZfcRbac\Service\Rbac' => 'ZfcRbac\Service\RbacFactory'
    )
);
