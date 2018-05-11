<?php

$router = $di->getRouter();

/**
 * Add index route with index action
 */
$router->addGet('/', [
    'controller' => 'Index',
    'action'     => 'index',
]);

/**
 * Add worker route with login action
 */
$router->addGet('/worker/login', [
    'controller' => 'Worker',
    'action'     => 'login',
]);

/**
 * Add worker route with logout action
 */
$router->addGet('/worker/logout', [
    'controller' => 'Worker',
    'action'     => 'logout',
]);

$router->handle();
