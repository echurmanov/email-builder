<?php

if (!defined("APPLICATION_ENV")) {
    define("APPLICATION_ENV", "development");
}

if (!defined("APPLICATION_PATH")) {
    define("APPLICATION_PATH", realpath(__DIR__ . "/../application"));
}

if (!defined("CONFIG_PATH")) {
    define("CONFIG_PATH", realpath(__DIR__ . "/../configs"));
}

if (!defined("VAR_PATH")) {
    define("VAR_PATH", realpath(__DIR__ . "/../var"));
}


require_once '../vendor/autoload.php';

$config = include (CONFIG_PATH . '/application.php');

$application = new Zend_Application(APPLICATION_ENV, $config);
$application->bootstrap();
$application->run();