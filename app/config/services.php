<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Security;
use GanttDashboard\App\Models\Workers;
use GanttDashboard\App\Services\Authentication as AuthenticationService;
use GanttDashboard\App\Services\Worker as WorkerService;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager;
use GanttDashboard\App\Plugins\NotFound;
use GanttDashboard\App\Validators\Worker as WorkerValidator;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . '/config/config.php';
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Register Volt as a service
 */
$di->set('voltService', function ($view) {
    $config = $this->getConfig();

    $volt = new VoltEngine($view, $this);

    $volt->setOptions([
        'compiledPath' => $config->application->cacheDir,
        'compiledSeparator' => '_'
    ]);

    return $volt;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);
    $view->setMainView('main');

    $view->registerEngines([
        '.phtml' => 'voltService'
    ]);

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared(MetaDataAdapter::class, MetaDataAdapter::class);

/**
 * Register the flash service with the Twitter Bootstrap classes
 */
$di->setShared('flash', function () {
    return new FlashDirect([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->setShared('flashSession', function () {
    return new FlashSession([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

/**
 * Register security services
 */
$di->setShared('security', function () {
    $security = new Security();

    /**
     * Set the password hashing factor to 12 rounds
     */
    $security->setWorkFactor(12);

    return $security;
});

/**
 * Register Worker model
 */
$di->setShared(Workers::class, Workers::class);

/**
 * Register Worker Validator
 */
$di->setShared(WorkerValidator::class, function () {
    return new WorkerValidator(
        $this->get(Workers::class)
    );
});

/**
 * Register Authentication service
 */
$di->setShared(AuthenticationService::class, function () {
        return new AuthenticationService(
            $this->get('session'),
            $this->get('security'),
            $this->get(Workers::class),
            $this->get('flashSession'),
            $this->get('view')
        );
});

/**
 * Register Worker service
 */
$di->setShared(WorkerService::class, function () {
    return new WorkerService(
        $this->get(AuthenticationService::class),
        $this->get(WorkerValidator::class),
        $this->get(Workers::class),
        $this->get('flashSession'),
        $this->get('view')
    );
});

/**
 * Register a dispatcher
 */
$di->set('dispatcher', function () {
    $dispatcher = new Dispatcher();

    /**
     * Create an EventManager
     */
    $eventsManager = new Manager();

    /**
     * Create not found exceptions
     */
    $notFoundExceptions = new NotFound();

    /**
     * Attach a listener
     */
    $eventsManager->attach('dispatch:beforeException', $notFoundExceptions);

    /**
     * Bind the EventsManager to the dispatcher
     */
    $dispatcher->setEventsManager($eventsManager);

    $dispatcher->setDefaultNamespace(
        'GanttDashboard\\App\\Controllers'
    );

    return $dispatcher;
});
