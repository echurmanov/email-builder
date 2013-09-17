<?php
$config = array(
    'phpSettings' => array(
        'display_startup_errors' => 0,
        'display_errors' => 0,
        'date' => array(
            'timezone' => 'US/Eastern'
        )
    ),
    'bootstrap' => array(
        'path'  => APPLICATION_PATH . '/Bootstrap.php',
        'class' => 'Bootstrap'
    ),
    'resources' => array(
        'frontController' => array(
            'controllerDirectory' => APPLICATION_PATH . '/controllers',
        ),
        'layout' => array(
            'layoutpath' => APPLICATION_PATH . '/layouts',
            'layout' => 'main',
        ),
        'view' => '',
    ),
    'twig' => array(
        'templateDir' => APPLICATION_PATH . '/templates',
        'options' => array(
            'cache' => VAR_PATH . '/cache/twig',
        ),
    ),
);

if (APPLICATION_ENV == 'development') {
    $config['phpSettings']['display_startup_errors'] = 1;
    $config['phpSettings']['display_errors'] = 1;
    $config['twig']['options']['debug'] = true;
    $config['twig']['options']['auto_reload'] = true;
}


return $config;