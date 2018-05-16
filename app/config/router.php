<?php

$router = $di->getRouter();

/**
 * Add index route with index action
 */
$router->addGet('/', [
    'controller' => 'Index',
    'action'     => 'index',
])->setName('home');

/**
 * Add not found route
 */
$router->addGet('/index/notFound', [
    'controller' => 'Index',
    'action'     => 'notFound',
])->setName('notFound');

/**
 * Add not found for numeric controllers and actions
 */
$router->addGet('/([0-9]+)/([0-9]+)', [
    'controller' => 'Index',
    'action'     => 'notFound',
]);

/**
 * Add not found for numeric controllers
 */
$router->addGet('/([0-9]+)', [
    'controller' => 'Index',
    'action'     => 'notFound',
]);

$router->handle();
