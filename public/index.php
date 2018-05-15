<?php

namespace GanttDashboard;

use Phalcon\Di\FactoryDefault;

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
$displayErrorsParam  = 1;
$logErrorsParam      = 1;

if ($environment !== 'development') {
    $errorReporting = 0;
    $displayErrorsParam = 0;
    $logErrorsParam     = 0;
}

error_reporting($errorReporting);
ini_set('display_errors', $displayErrorsParam);
ini_set('log_errors', $logErrorsParam);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    /**
     * Handle routes
     */
    include APP_PATH . '/config/router.php';

    /**
     * Read services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());
} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
