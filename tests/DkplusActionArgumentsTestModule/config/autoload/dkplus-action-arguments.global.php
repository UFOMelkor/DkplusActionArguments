<?php
return array(
    'DkplusActionArguments' => array(
        'options' => array(
            'guards' => array(
                'DkplusActionArguments\Guard\ArgumentsGuard',
            ),
            'missing_arguments_template' => 'error/404-missing-arguments',
            'cache_file_path'            => __DIR__ . '/dkplus-action-arguments.spec.global.php',
        ),
        'controllers' => array(
        ),
    ),
);
