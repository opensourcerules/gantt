<?php

use Phalcon\Mvc\Router\Group as RouterGroup;
use Phalcon\Mvc\Router;

$router = new Router(false);

/**
 * Set 404 path
 */
$router->notFound([
    'controller' => 'Index',
    'action'     => 'notFound',
]);

/**
 * Create a group with index controller
 */
$index = new RouterGroup([
    'controller' => 'Index',
]);

$index->add('/', [
    'action' => 'index',
])->setName('home');

$index->add('/index/notfound', [
    'action' => 'notFound',
])->setName('notFound');

$router->mount($index);

/**
 * Create a group with a Worker controller
 */
$worker = new RouterGroup([
    'controller' => 'Worker',
]);

$worker->setPrefix('/worker');

$worker->add('/register', [
    'action' => 'register',
])->setName('registerWorker');

$worker->add('/edit/{id:[0-9]+}', [
    'action' => 'edit',
])->setName('editWorker');

$worker->add('/edit', [
    'action' => 'beforeEdit',
])->setName('beforeEditWorker');

$worker->add('/logout', [
    'action' => 'logout',
]);

$worker->add('/login/{accessKey:[a-zA-Z0-9]+}', [
    'action' => 'login',
]);

$router->mount($worker);

$router->handle();

return $router;
