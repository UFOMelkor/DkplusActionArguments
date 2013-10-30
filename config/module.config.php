<?php
 return array(
     'DkplusActionArguments' => array(
         'options' => array(
             'guards'                     => array(),
             'missing_arguments_template' => 'error/404-missing-argument'
         )
     ),

    'view_manager' => array(
        'template_map' => array(
            'error/404-missing-arguments' => __DIR__ . '/../view/error/404-missing-arguments.phtml',
        ),
    ),
);