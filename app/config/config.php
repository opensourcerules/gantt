<?php

$settings = [
    'database' => [
    'adapter'     => 'Mysql',
    'host'        => 'localhost',
    'username'    => 'phalcon',
    'password'    => 'l.$u5/aKj@b7',
    'dbname'      => 'gantt',
    'charset'     => 'utf8',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'logsDir'        => APP_PATH . '/logs/',
        'servicesDir'    => APP_PATH . '/services/',
        'validatorsDir'  => APP_PATH . '/validators/',
        'middlewareDir'  => APP_PATH . '/middleware/',
        'providersDir'   => APP_PATH . '/providers/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the web space.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'        => '/',
        //'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
    ],
    'debug'   => 1,
    'logging' => 1
];
