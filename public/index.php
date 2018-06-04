<?php

namespace GanttDashboard;

use GanttDashboard\App\Providers\MyDi;

/**
 * Application environment
 */
$environment = isset($_SERVER['APP_ENV']) ? strtolower($_SERVER['APP_ENV']) : 'development';

/**
 * Error reporting
 */
if (false === in_array($environment, ['testing', 'production', 'development'])) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'The application environment is not set correctly.';
    exit(1);
}

$errorReporting = E_ALL;
$iniDisplayErrors  = 1;
$iniLogErrors      = 1;

if ($environment !== 'development') {
    $errorReporting = 0;
    $iniDisplayErrors = 0;
    $iniLogErrors     = 0;
}

error_reporting($errorReporting);
ini_set('display_errors', $iniDisplayErrors);
ini_set('log_errors', $iniLogErrors);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {
    /**
     * Use composer autoloader to load vendor classes
     */
    require_once BASE_PATH . "/vendor/autoload.php";

    $di = new MyDi();

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);
    $application->setEventsManager($di->getShared('eventsManager'));

    $application->handle()->send();
} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
