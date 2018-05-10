<?php

$router = $di->getRouter();

// Define your routes here

$router->add('/', [
    'controller' => 'Index',
    'action'     => 'index',
    ]);

$router->add('/worker/login', [
    'controller' => 'Worker',
    'action'     => 'login',
    ]);

$router->add('/worker/logout', [
    'controller' => 'Worker',
    'action'     => 'logout',
]);

$router->handle();
