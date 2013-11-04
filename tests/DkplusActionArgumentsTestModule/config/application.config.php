<?php
return array(
    'modules' => array(
        'DkplusActionArguments',
        'DkplusActionArgumentsTestModule',
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            __DIR__ . '/../..',
            __DIR__ . '/../../../..'
        ),
        'config_glob_paths' => array(
            __DIR__ . '/autoload/{,*.}{global,local}.php',
        ),
    ),
);
